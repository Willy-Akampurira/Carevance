<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;    // Import Prescription model
use App\Models\Drug;            // Import Drug model
use App\Models\StockLot;        // Import StockLot model
use App\Models\MedicalRecord;   // Import MedicalRecord model
use App\Models\FinancialRecord; // Import FinancialRecord model
use App\Models\FinancialRecordItem; // Import FinancialRecordItem model
use App\Models\Payment;         // Import Payment model
use App\Models\PatientAnalytics; // Import PatientAnalytics model  
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PatientController extends Controller
{
    // ------------------- Patients CRUD -------------------
    public function index(Request $request)
    {
        // Grab the search query from the request
        $search = $request->input('search');

        // Build query with optional search filter
        $patients = Patient::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('contact', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('gender', 'like', "%{$search}%")
                    ->orWhereDate('dob', $search)          // allow searching by DOB
                    ->orWhereDate('entry_date', $search); // ✅ allow searching by entry date
            })
            ->latest('entry_date') // ✅ order by entry_date instead of created_at
            ->paginate(10)
            ->withQueryString(); // keep search term in pagination links

        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'dob'             => 'required|date',
            'gender'          => 'required|string|max:20',
            'contact'         => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:255',
            'medical_history' => 'nullable|string|max:1000',
            'entry_date'      => 'nullable|date', // ✅ new field
        ]);

        Patient::create($validated);

        return redirect()->route('patients.index')
                        ->with('success', 'Patient registered successfully.');
    }

    public function show(int $id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.show', compact('patient'));
    }

    public function edit(int $id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'dob'             => 'required|date',
            'gender'          => 'required|string|max:20',
            'contact'         => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:255',
            'medical_history' => 'nullable|string|max:1000',
            'entry_date'      => 'nullable|date', // ✅ new field
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($validated);

        return redirect()->route('patients.index')
                        ->with('success', 'Patient updated successfully.');
    }

    public function destroy(int $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete(); // soft delete enabled in model

        return redirect()->route('patients.index')
                        ->with('success', 'Patient archived successfully.');
    }

    public function archived()
    {
        $patients = Patient::onlyTrashed()->paginate(10);
        return view('patients.archived', compact('patients'));
    }

    public function restore(int $id)
    {
        $patient = Patient::onlyTrashed()->findOrFail($id);
        $patient->restore();

        return redirect()->route('patients.archived')
                        ->with('success', 'Patient restored successfully.');
    }

    public function forceDelete(int $id)
    {
        $patient = Patient::onlyTrashed()->findOrFail($id);
        $patient->forceDelete();

        return redirect()->route('patients.archived')
                        ->with('success', 'Patient permanently deleted.');
    }

    // ------------------- Appointments -------------------
    public function appointments()
    {
        $upcoming = Appointment::upcoming()->paginate(10);
        $history  = Appointment::history()->paginate(10);
        $patients = Patient::all();

        return view('patients.appointments', compact('upcoming', 'history', 'patients'));
    }

    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'patient_id'    => 'required|exists:patients,id',
            'doctor'        => 'nullable|string|max:255',
            'scheduled_at'  => 'required|date',
            'reason'        => 'nullable|string|max:255',
            'notes'         => 'nullable|string',
            'visit_summary' => 'nullable|string',
            'status'        => 'required|in:scheduled,completed,cancelled',
        ]);

        Appointment::create($validated);

        return redirect()->route('patients.appointments')
                         ->with('success', 'Appointment scheduled successfully.');
    }

    // ------------------- Prescriptions -------------------
    public function prescriptions()
    {
        $active   = Prescription::with(['patient','drug','stockLot'])->active()->paginate(10);
        $history  = Prescription::with(['patient','drug','stockLot'])->history()->paginate(10);
        $renewals = Prescription::with(['patient','drug','stockLot'])->renewalRequests()->paginate(10);

        $patients = Patient::all();

        // Only active lots (not expired, not depleted)
        $lots = StockLot::whereDate('expiry_date', '>=', now())
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date')
            ->with('drug')
            ->get();

        return view('patients.prescriptions', compact('active','history','renewals','patients','lots'));
    }

    public function storePrescription(Request $request)
    {
        $validated = $request->validate([
            'appointment_id'    => 'nullable|exists:appointments,id', // optional
            'patient_id'        => 'required|exists:patients,id',
            'lot_id'            => 'required|exists:stock_lots,id',
            'dosage'            => 'required|string|max:255',
            'frequency'         => 'required|string|max:255',
            'duration_days'     => 'required|integer|min:1',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'issued_by'         => 'required|string|max:255',
            'status'            => 'required|in:active,dispensed,missed,completed,expired,renewal_requested',
            'category'          => 'nullable|string|max:100',
            'renewal_requested' => 'boolean',
            'notes'             => 'nullable|string',
            'quantity'          => 'required|integer|min:1',
            'unit'              => 'required|string|max:50', // added
        ]);

        // Fetch lot
        $lot = StockLot::findOrFail($validated['lot_id']);

        // Prevent prescribing from expired lot
        if ($lot->expiry_date < now()) {
            return back()->withErrors('Cannot prescribe from expired lot.');
        }

        // Check stock availability
        if ($lot->quantity < $validated['quantity']) {
            return back()->withErrors('Not enough stock in this lot.');
        }

        // Deduct stock
        $lot->decrement('quantity', $validated['quantity']);

        // Create prescription record
        Prescription::create([
            'appointment_id'    => $validated['appointment_id'],
            'patient_id'        => $validated['patient_id'],
            'drug_id'           => $lot->drug_id,
            'lot_id'            => $lot->id,
            'dosage'            => $validated['dosage'],
            'frequency'         => $validated['frequency'],
            'duration_days'     => $validated['duration_days'],
            'start_date'        => $validated['start_date'],
            'end_date'          => $validated['end_date'],
            'issued_by'         => $validated['issued_by'],
            'status'            => $validated['status'],
            'category'          => $validated['category'],
            'renewal_requested' => $validated['renewal_requested'] ?? false,
            'notes'             => $validated['notes'],
            'quantity'          => $validated['quantity'],
            'unit'              => $validated['unit'], // ✅ added
        ]);

        return redirect()->route('patients.prescriptions')
                        ->with('success', 'Prescription created successfully.');
    }

    public function exportPdf(Prescription $prescription)
    {
        // Load the Blade view and pass the prescription data
        $pdf = Pdf::loadView('prescriptions.pdf', compact('prescription'));

        // Download the file with a clear name
        return $pdf->download('prescription_'.$prescription->id.'.pdf');
    }

    // ------------------- Medical Records -------------------
    public function records()
    {
        $active   = MedicalRecord::with(['patient','appointment'])->active()->paginate(10);
        $archived = MedicalRecord::with(['patient','appointment'])->archived()->paginate(10);

        $patients     = Patient::all();
        $appointments = Appointment::all();

        return view('patients.records', compact('active','archived','patients','appointments'));
    }

    public function storeRecord(Request $request)
    {
        $validated = $request->validate([
            'patient_id'      => 'required|exists:patients,id',
            'appointment_id'  => 'nullable|exists:appointments,id',
            'diagnosis'       => 'required|string|max:255',
            'lab_results'     => 'nullable|string',
            'imaging_results' => 'nullable|string',
            'allergies'       => 'nullable|string',
            'notes'           => 'nullable|string',
            'recorded_by'     => 'required|string|max:255',
            'status'          => 'required|in:active,archived',
        ]);

        MedicalRecord::create($validated);

        return redirect()->route('patients.records')
                         ->with('success', 'Medical record created successfully.');
    }

   // ------------------- Billing & Insurance -------------------

    // Show billing tables
    public function billing()
    {
        $unpaid   = FinancialRecord::with(['patient','payments'])->where('status', 'unpaid')->latest()->paginate(10);
        $paid     = FinancialRecord::with(['patient','payments'])->where('status', 'paid')->latest()->paginate(10);
        $claims   = FinancialRecord::with('patient')->whereNotNull('claim_number')->latest()->paginate(10);
        $patients = Patient::all();

        return view('patients.billing', compact('unpaid','paid','claims','patients'));
    }

    // Store new invoice
    public function storeBilling(Request $request)
    {
        $validated = $request->validate([
            'patient_id'         => 'required|exists:patients,id',
            'invoice_date'       => 'required|date',
            'status'             => 'required|in:unpaid,paid,pending,cancelled',
            'insurance_provider' => 'nullable|string|max:255',
            'claim_number'       => 'nullable|string|max:100',
            'claim_status'       => 'nullable|in:submitted,approved,denied,pending',
            'payment_method'     => 'nullable|string|max:50',
            'payment_date'       => 'nullable|date',
            'notes'              => 'nullable|string',
            'items.*.description'=> 'required|string|max:255',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $datePart = now()->format('Ymd');
        $countToday = FinancialRecord::whereDate('created_at', now()->toDateString())->count() + 1;
        $invoiceNumber = 'INV-' . $datePart . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

        $financialRecord = FinancialRecord::create([
            'patient_id'         => $validated['patient_id'],
            'invoice_number'     => $invoiceNumber,
            'invoice_date'       => $validated['invoice_date'],
            'amount'             => 0,
            'status'             => $validated['status'],
            'insurance_provider' => $validated['insurance_provider'] ?? null,
            'claim_number'       => $validated['claim_number'] ?? null,
            'claim_status'       => ($validated['claim_number'] || $validated['insurance_provider'])
                                    ? ($validated['claim_status'] ?? 'pending')
                                    : null,
            'payment_method'     => $validated['payment_method'] ?? null,
            'payment_date'       => $validated['payment_date'] ?? null,
            'notes'              => $validated['notes'] ?? null,
        ]);

        $grandTotal = 0;
        foreach ($request->items as $item) {
            $lineTotal = $item['quantity'] * $item['unit_price'];
            $financialRecord->items()->create([
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
            ]);
            $grandTotal += $lineTotal;
        }

        $financialRecord->update(['amount' => $grandTotal]);

        return redirect()->route('patients.billing')
                        ->with('success', 'Financial record saved successfully with items.');
    }

    // ------------------- Print Invoice -------------------
    public function printInvoice($id)
    {
        $record = FinancialRecord::with(['patient','items','payments'])->findOrFail($id);
        $pdf = Pdf::loadView('patients.invoice', compact('record'));
        return $pdf->download('invoice_'.$record->invoice_number.'.pdf');
    }

    // ------------------- Action Methods -------------------

    // Show payment form (GET)
    public function showPaymentForm($id)
    {
        $invoice = FinancialRecord::with('payments')->findOrFail($id);

        $totalPaid = $invoice->payments->sum('amount');
        $balance   = $invoice->amount - $totalPaid;

        return view('patients.payment-form', compact('invoice', 'balance'));
    }

    // Record a payment (POST) – full or partial
    public function addPayment(Request $request, $id)
    {
        $invoice = FinancialRecord::findOrFail($id);

        $validated = $request->validate([
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:50',
            'payment_date'   => 'required|date',
        ]);

        // Save payment record
        $invoice->payments()->create($validated);

        // Recalculate totals
        $totalPaid = $invoice->payments()->sum('amount');
        $balance   = $invoice->amount - $totalPaid;

        if ($balance <= 0) {
            $invoice->update([
                'status'         => 'paid',
                'payment_method' => $validated['payment_method'],
                'payment_date'   => $validated['payment_date'],
                'notes'          => 'Invoice fully paid.',
            ]);
        } else {
            $invoice->update([
                'status'         => 'unpaid',
                'payment_method' => $validated['payment_method'],
                'payment_date'   => $validated['payment_date'],
                'notes'          => 'Partial payment of '.$validated['amount'].' recorded. Balance remaining: '.$balance,
            ]);
        }

        return redirect()->route('patients.billing')
                        ->with('success', 'Payment recorded successfully.');
    }

    // Cancel an invoice
    public function cancelInvoice($id)
    {
        $invoice = FinancialRecord::findOrFail($id);
        $invoice->update(['status' => 'cancelled']);
        return back()->with('success', 'Invoice cancelled successfully.');
    }

    // View payment details (history from payments table)
    public function viewPayments($id)
    {
        $invoice = FinancialRecord::with(['patient','payments'])->findOrFail($id);
        return view('patients.payments', compact('invoice'));
    }

    // Show claim update form (GET)
    public function showClaimForm($id)
    {
        $invoice = FinancialRecord::findOrFail($id);
        return view('patients.claim-form', compact('invoice'));
    }

    // Update insurance claim status (POST)
    public function updateClaim(Request $request, $id)
    {
        $invoice = FinancialRecord::findOrFail($id);

        $validated = $request->validate([
            'claim_status' => 'required|in:submitted,approved,denied,pending',
        ]);

        $invoice->update([
            'claim_status' => $validated['claim_status'],
            'notes'        => 'Claim status updated to: '.$validated['claim_status'],
        ]);

        return redirect()->route('patients.billing')
                        ->with('success', 'Claim status updated successfully.');
    }

   // ------------------- Reports & Analytics -------------------
    public function reports()
    {
        // Patient growth (monthly registrations)
        $patientGrowth = Patient::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Age distribution buckets
        $ageDistribution = [
            '0-18'  => Patient::whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 0 AND 18')->count(),
            '19-35' => Patient::whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 19 AND 35')->count(),
            '36-60' => Patient::whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 36 AND 60')->count(),
            '60+'   => Patient::whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) >= 61')->count(),
        ];

        // Disease categories (from medical records diagnoses)
        $diseaseCategories = MedicalRecord::selectRaw('diagnosis, COUNT(*) as total')
            ->groupBy('diagnosis')
            ->orderByDesc('total')
            ->get();

        // Billing stats
        $billingStats = [
            'unpaid_count' => FinancialRecord::where('status', 'unpaid')->count(),
            'unpaid_total' => FinancialRecord::where('status', 'unpaid')->sum('amount'),
            'paid_count'   => FinancialRecord::where('status', 'paid')->count(),
            'paid_total'   => FinancialRecord::where('status', 'paid')->sum('amount'),
        ];

        // Insurance claim stats
        $claimStats = [
            'submitted' => FinancialRecord::where('claim_status', 'submitted')->count(),
            'approved'  => FinancialRecord::where('claim_status', 'approved')->count(),
            'denied'    => FinancialRecord::where('claim_status', 'denied')->count(),
            'pending'   => FinancialRecord::where('claim_status', 'pending')->count(),
        ];

        return view('patients.reports', compact(
            'patientGrowth',
            'ageDistribution',
            'diseaseCategories',
            'billingStats',
            'claimStats'
        ));
    }
}