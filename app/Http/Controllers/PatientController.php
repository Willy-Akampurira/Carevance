<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;    // Import Prescription model
use App\Models\Drug;            // Import Drug model
use App\Models\MedicalRecord;   // Import MedicalRecord model
use App\Models\FinancialRecord; // Import FinancialRecord model
use App\Models\PatientAnalytics; // Import PatientAnalytics model   
use Carbon\Carbon;

class PatientController extends Controller
{
    // ------------------- Patients CRUD -------------------
    public function index()
    {
        $patients = Patient::latest()->paginate(10);
        return view('patients.index', compact('patients'));
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
        $active   = Prescription::with(['patient','drug'])->active()->paginate(10);
        $history  = Prescription::with(['patient','drug'])->history()->paginate(10);
        $renewals = Prescription::with(['patient','drug'])->renewalRequests()->paginate(10);

        $patients = Patient::all();
        $drugs    = Drug::all();

        return view('patients.prescriptions', compact('active','history','renewals','patients','drugs'));
    }

    public function storePrescription(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'patient_id'     => 'required|exists:patients,id',
            'drug_id'        => 'required|exists:drugs,id',
            'drug_name'      => 'required|string|max:255',
            'dosage'         => 'required|string|max:255',
            'frequency'      => 'required|string|max:255',
            'duration_days'  => 'required|integer|min:1',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'issued_by'      => 'required|string|max:255',
            'status'         => 'required|in:active,dispensed,missed,completed,expired,renewal_requested',
            'category'       => 'nullable|string|max:100',
            'renewal_requested' => 'boolean',
            'notes'          => 'nullable|string',
        ]);

        Prescription::create($validated);

        return redirect()->route('patients.prescriptions')
                         ->with('success', 'Prescription created successfully.');
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
    public function billing()
    {
        $unpaid   = FinancialRecord::with('patient')->where('status', 'unpaid')->latest()->paginate(10);
        $paid     = FinancialRecord::with('patient')->where('status', 'paid')->latest()->paginate(10);
        $claims   = FinancialRecord::with('patient')->whereNotNull('claim_number')->latest()->paginate(10);
        $patients = Patient::all();

        return view('patients.billing', compact('unpaid','paid','claims','patients'));
    }

    public function storeBilling(Request $request)
    {
        $validated = $request->validate([
            'patient_id'        => 'required|exists:patients,id',
            'invoice_number'    => 'required|string|max:50',
            'invoice_date'      => 'required|date',
            'amount'            => 'required|numeric|min:0',
            'status'            => 'required|in:unpaid,paid,pending,cancelled',
            'insurance_provider'=> 'nullable|string|max:255',
            'claim_number'      => 'nullable|string|max:100',
            'claim_status'      => 'nullable|in:submitted,approved,denied,pending',
            'payment_method'    => 'nullable|string|max:50',
            'payment_date'      => 'nullable|date',
            'notes'             => 'nullable|string',
        ]);

        FinancialRecord::create($validated);

        return redirect()->route('patients.billing')
                         ->with('success', 'Financial record saved successfully.');
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

    return view('patients.reports', compact('patientGrowth','ageDistribution','diseaseCategories'));
}
}