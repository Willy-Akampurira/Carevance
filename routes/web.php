<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\DrugCategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ExpiryController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;

// Supplier module controllers
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierInvoiceController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile management
Route::middleware(['auth'])->group(function () {
    // Show profile form
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    // Update profile info (name/email)
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Update password
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    // Resend email verification link
    Route::post('/profile/verification', [ProfileController::class, 'sendVerification'])
        ->name('profile.verification.send');

    // Delete account
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Archived Patients routes
Route::get('/patients/archived', [PatientController::class, 'archived'])->middleware(['auth'])->name('patients.archived');
Route::post('/patients/{id}/restore', [PatientController::class, 'restore'])->middleware(['auth'])->name('patients.restore');
Route::delete('/patients/{id}/force-delete', [PatientController::class, 'forceDelete'])->middleware(['auth'])->name('patients.forceDelete');

// Patients sub‑modules
Route::get('/patients/register', [PatientController::class, 'create'])->middleware(['auth'])->name('patients.create');

// Appointments
Route::get('/patients/appointments', [PatientController::class, 'appointments'])->middleware(['auth'])->name('patients.appointments');
Route::post('/patients/appointments', [PatientController::class, 'storeAppointment'])->middleware(['auth'])->name('patients.appointments.store');

// Prescriptions
Route::get('/patients/prescriptions', [PatientController::class, 'prescriptions'])
    ->middleware(['auth'])
    ->name('patients.prescriptions');

Route::post('/patients/prescriptions', [PatientController::class, 'storePrescription'])
    ->middleware(['auth'])
    ->name('patients.prescriptions.store');

// Export a single prescription to PDF
Route::get('/prescriptions/{prescription}/export-pdf', [PatientController::class, 'exportPdf'])
    ->middleware(['auth'])
    ->name('prescriptions.export.pdf');

// Medical Records
Route::get('/patients/records', [PatientController::class, 'records'])->middleware(['auth'])->name('patients.records');
Route::post('/patients/records', [PatientController::class, 'storeRecord'])->middleware(['auth'])->name('patients.records.store');

// ------------------- Billing & Insurance -------------------

// Show billing tables
Route::get('/patients/billing', [PatientController::class, 'billing'])
    ->middleware(['auth'])
    ->name('patients.billing');

// Store new invoice
Route::post('/patients/billing', [PatientController::class, 'storeBilling'])
    ->middleware(['auth'])
    ->name('patients.billing.store');

// Print Invoice
Route::get('/patients/billing/print/{id}', [PatientController::class, 'printInvoice'])
    ->middleware(['auth'])
    ->name('billing.printInvoice');

// ------------------- Action Buttons -------------------

// Show payment form (GET) → lets user enter amount, method, date
Route::get('/patients/billing/{id}/pay', [PatientController::class, 'showPaymentForm'])
    ->middleware(['auth'])
    ->name('patients.billing.pay.form');

// Record a payment (POST) → processes the form submission
Route::post('/patients/billing/{id}/pay', [PatientController::class, 'addPayment'])
    ->middleware(['auth'])
    ->name('patients.billing.pay');

// Cancel an invoice
Route::post('/patients/billing/{id}/cancel', [PatientController::class, 'cancelInvoice'])
    ->middleware(['auth'])
    ->name('patients.billing.cancel');

// View payment details (from financial_records itself)
Route::get('/patients/billing/{id}/payments', [PatientController::class, 'viewPayments'])
    ->middleware(['auth'])
    ->name('patients.billing.payments');

// Show the claim update form (GET)
Route::get('/patients/billing/{id}/claim', [PatientController::class, 'showClaimForm'])
    ->middleware(['auth'])
    ->name('patients.billing.claim.form');

// Handle claim update (POST)
Route::post('/patients/billing/{id}/claim', [PatientController::class, 'updateClaim'])
    ->middleware(['auth'])
    ->name('patients.billing.claim.update');

// Reports & Analytics
Route::get('/patients/reports', [PatientController::class, 'reports'])->middleware(['auth'])->name('patients.reports');
Route::get('/patients/analytics', [PatientController::class, 'analytics'])->middleware(['auth'])->name('patients.analytics');

// Prescriptions renewal actions
Route::post('/prescriptions/{id}/renewals/approve', [PatientController::class, 'approveRenewal'])->middleware(['auth'])->name('prescriptions.renewals.approve');
Route::post('/prescriptions/{id}/renewals/decline', [PatientController::class, 'declineRenewal'])->middleware(['auth'])->name('prescriptions.renewals.decline');

// ------------------- Suppliers Module -------------------

// Archived Suppliers routes (Soft Deletes) — must come BEFORE resource route
Route::get('/suppliers/archived', [SupplierController::class, 'archived'])->middleware(['auth'])->name('suppliers.archived');
Route::post('/suppliers/{id}/restore', [SupplierController::class, 'restore'])->middleware(['auth'])->name('suppliers.restore');
Route::delete('/suppliers/{id}/force-delete', [SupplierController::class, 'forceDelete'])->middleware(['auth'])->name('suppliers.forceDelete');

// Deliveries nested under suppliers
Route::get('/suppliers/{supplier}/deliveries', [SupplierController::class, 'deliveriesIndex'])->middleware(['auth'])->name('suppliers.deliveries.index');
Route::get('/suppliers/{supplier}/deliveries/create', [SupplierController::class, 'deliveriesCreate'])->middleware(['auth'])->name('suppliers.deliveries.create');
Route::post('/suppliers/{supplier}/deliveries', [SupplierController::class, 'deliveriesStore'])->middleware(['auth'])->name('suppliers.deliveries.store');
Route::get('/suppliers/{supplier}/deliveries/{delivery}', [SupplierController::class, 'deliveriesShow'])->middleware(['auth'])->name('suppliers.deliveries.show');
Route::get('/suppliers/{supplier}/deliveries/{delivery}/edit', [SupplierController::class, 'deliveriesEdit'])->middleware(['auth'])->name('suppliers.deliveries.edit');
Route::put('/suppliers/{supplier}/deliveries/{delivery}', [SupplierController::class, 'deliveriesUpdate'])->middleware(['auth'])->name('suppliers.deliveries.update');
Route::delete('/suppliers/{supplier}/deliveries/{delivery}', [SupplierController::class, 'deliveriesDestroy'])->middleware(['auth'])->name('suppliers.deliveries.destroy');

// Supplier → Purchase Orders (scoped create/store)
Route::get('/suppliers/{supplier}/purchase-orders/create', [PurchaseOrderController::class, 'create'])->middleware(['auth'])->name('suppliers.po.create');
Route::post('/suppliers/{supplier}/purchase-orders', [PurchaseOrderController::class, 'store'])->middleware(['auth'])->name('suppliers.po.store');

// Purchase Orders (full resource)
Route::resource('purchase-orders', PurchaseOrderController::class)->middleware(['auth'])->names('purchaseOrders');

// Supplier Invoices nested under suppliers
Route::get('/suppliers/{supplier}/invoices', [SupplierController::class, 'invoicesIndex'])->middleware(['auth'])->name('suppliers.invoices.index');
Route::get('/suppliers/{supplier}/invoices/create', [SupplierController::class, 'invoicesCreate'])->middleware(['auth'])->name('suppliers.invoices.create');
Route::post('/suppliers/{supplier}/invoices', [SupplierController::class, 'invoicesStore'])->middleware(['auth'])->name('suppliers.invoices.store');
Route::get('/suppliers/{supplier}/invoices/{invoice}', [SupplierController::class, 'invoicesShow'])->middleware(['auth'])->name('suppliers.invoices.show');
Route::get('/suppliers/{supplier}/invoices/{invoice}/edit', [SupplierController::class, 'invoicesEdit'])->middleware(['auth'])->name('suppliers.invoices.edit');
Route::put('/suppliers/{supplier}/invoices/{invoice}', [SupplierController::class, 'invoicesUpdate'])->middleware(['auth'])->name('suppliers.invoices.update');
Route::delete('/suppliers/{supplier}/invoices/{invoice}', [SupplierController::class, 'invoicesDestroy'])->middleware(['auth'])->name('suppliers.invoices.destroy');

// Supplier Payments nested under suppliers & invoices
Route::get('/suppliers/{supplier}/invoices/{invoice}/payments', [SupplierController::class, 'paymentsIndex'])->middleware(['auth'])->name('suppliers.invoices.payments.index');
Route::get('/suppliers/{supplier}/invoices/{invoice}/payments/create', [SupplierController::class, 'paymentsCreate'])->middleware(['auth'])->name('suppliers.invoices.payments.create');
Route::post('/suppliers/{supplier}/invoices/{invoice}/payments', [SupplierController::class, 'paymentsStore'])->middleware(['auth'])->name('suppliers.invoices.payments.store');
Route::get('/suppliers/{supplier}/invoices/{invoice}/payments/{payment}', [SupplierController::class, 'paymentsShow'])->middleware(['auth'])->name('suppliers.invoices.payments.show');
Route::get('/suppliers/{supplier}/invoices/{invoice}/payments/{payment}/edit', [SupplierController::class, 'paymentsEdit'])->middleware(['auth'])->name('suppliers.invoices.payments.edit');
Route::put('/suppliers/{supplier}/invoices/{invoice}/payments/{payment}', [SupplierController::class, 'paymentsUpdate'])->middleware(['auth'])->name('suppliers.invoices.payments.update');
Route::delete('/suppliers/{supplier}/invoices/{invoice}/payments/{payment}', [SupplierController::class, 'paymentsDestroy'])->middleware(['auth'])->name('suppliers.invoices.payments.destroy');

// =========================
// Drugs Module Routes
// =========================

// List all drugs
Route::get('/drugs', [DrugController::class, 'index'])
    ->middleware(['auth'])
    ->name('drugs.index');

// Create new drug
Route::get('/drugs/create', [DrugController::class, 'create'])
    ->middleware(['auth'])
    ->name('drugs.create');
Route::post('/drugs', [DrugController::class, 'store'])
    ->middleware(['auth'])
    ->name('drugs.store');

// Restock routes
// Show the restock form for a specific drug
Route::get('/drugs/{drug}/restock', [DrugController::class, 'showRestockForm'])
    ->middleware(['auth'])
    ->name('drugs.restock.form');

// Handle the restock submission
Route::post('/drugs/{drug}/restock', [DrugController::class, 'restock'])
    ->middleware(['auth'])
    ->name('drugs.restock');


// =========================
// Categories Sub‑Module
// (placed BEFORE /drugs/{drug})
// =========================
Route::get('/drugs/categories', [DrugCategoryController::class, 'index'])
    ->middleware(['auth'])
    ->name('drugs.categories.index');

Route::get('/drugs/categories/create', [DrugCategoryController::class, 'create'])
    ->middleware(['auth'])
    ->name('drugs.categories.create');
Route::post('/drugs/categories', [DrugCategoryController::class, 'store'])
    ->middleware(['auth'])
    ->name('drugs.categories.store');

Route::get('/drugs/categories/{category}/edit', [DrugCategoryController::class, 'edit'])
    ->middleware(['auth'])
    ->name('drugs.categories.edit');
Route::put('/drugs/categories/{category}', [DrugCategoryController::class, 'update'])
    ->middleware(['auth'])
    ->name('drugs.categories.update');
Route::delete('/drugs/categories/{category}', [DrugCategoryController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('drugs.categories.destroy');

// =========================
// Trash (Soft Deletes)
// =========================
Route::get('/drugs/trashed', [DrugController::class, 'trashed'])
    ->middleware(['auth'])
    ->name('drugs.trashed');
Route::post('/drugs/{id}/restore', [DrugController::class, 'restore'])
    ->middleware(['auth'])
    ->name('drugs.restore');
Route::delete('/drugs/{id}/forceDelete', [DrugController::class, 'forceDelete'])
    ->middleware(['auth'])
    ->name('drugs.forceDelete');

// =========================
// Wildcard Drug Routes
// (AFTER categories & trash)
// =========================
Route::get('/drugs/{drug}', [DrugController::class, 'show'])
    ->middleware(['auth'])
    ->name('drugs.show');

Route::get('/drugs/{drug}/edit', [DrugController::class, 'edit'])
    ->middleware(['auth'])
    ->name('drugs.edit');
Route::put('/drugs/{drug}', [DrugController::class, 'update'])
    ->middleware(['auth'])
    ->name('drugs.update');
Route::delete('/drugs/{drug}', [DrugController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('drugs.destroy');

// Stock Management Routes
Route::get('/stock/new', [StockController::class, 'newStock'])
    ->middleware(['auth'])
    ->name('stock.new');

Route::get('/stock/old', [StockController::class, 'oldStock'])
    ->middleware(['auth'])
    ->name('stock.old');

// Low Stock Alerts
Route::get('/stock/low', [StockController::class, 'low'])
    ->middleware(['auth'])
    ->name('stock.low');

// Show adjustment form (sidebar entry)
Route::get('/stock/adjustment', [StockController::class, 'adjustment'])
    ->middleware(['auth'])
    ->name('stock.adjustment');

// Save adjustment (form submission)
Route::post('/stock/adjustment/store', [StockController::class, 'adjustmentStore'])
    ->middleware(['auth'])
    ->name('stock.adjustment.store');

// View single adjustment record (optional detail view)
Route::get('/stock/adjustment/{id}', [StockController::class, 'adjustmentShow'])
    ->middleware(['auth'])
    ->name('stock.adjustment.show');

// Show threshold form
Route::get('/expiry/threshold', [ExpiryController::class, 'threshold'])
    ->middleware(['auth'])
    ->name('expiry.threshold');

// Handle threshold update
Route::post('/expiry/threshold', [ExpiryController::class, 'updateThreshold'])
    ->middleware(['auth'])
    ->name('expiry.updateThreshold');

// Nearing expiry drugs
Route::get('/expiry/nearing', [ExpiryController::class, 'nearing'])
    ->middleware(['auth'])
    ->name('expiry.nearing');

// Expired drugs
Route::get('/expiry/expired', [ExpiryController::class, 'expired'])
    ->middleware(['auth'])
    ->name('expiry.expired');

// Notifications
Route::get('/expiry/notifications', [ExpiryController::class, 'notifications'])
    ->middleware(['auth'])
    ->name('expiry.notifications');

// ------------------- Staff Module -------------------

// Staff Management (list, create, store)
Route::get('/staff', [StaffController::class, 'index'])
    ->middleware('auth')
    ->name('staff.index');

Route::get('/staff/create', [StaffController::class, 'create'])
    ->middleware('auth')
    ->name('staff.create');

Route::post('/staff', [StaffController::class, 'store'])
    ->middleware('auth')
    ->name('staff.store');

// Trash & Restore (must come BEFORE /staff/{id})
Route::get('/staff/trashed', [StaffController::class, 'trashed'])
    ->middleware('auth')
    ->name('staff.trashed');

Route::post('/staff/{id}/restore', [StaffController::class, 'restore'])
    ->middleware('auth')
    ->name('staff.restore');

Route::delete('/staff/{id}/force-delete', [StaffController::class, 'forceDelete'])
    ->middleware('auth')
    ->name('staff.forceDelete');

// ------------------- Departments -------------------
Route::get('/staff/departments', [StaffController::class, 'departments'])
    ->middleware('auth')
    ->name('staff.departments.index');

Route::get('/staff/departments/create', [StaffController::class, 'createDepartment'])
    ->middleware('auth')
    ->name('staff.departments.create');

Route::post('/staff/departments', [StaffController::class, 'storeDepartment'])
    ->middleware('auth')
    ->name('staff.departments.store');

Route::get('/staff/departments/{department}/edit', [StaffController::class, 'editDepartment'])
    ->middleware('auth')
    ->name('staff.departments.edit');

Route::put('/staff/departments/{department}', [StaffController::class, 'updateDepartment'])
    ->middleware('auth')
    ->name('staff.departments.update');

Route::delete('/staff/departments/{department}', [StaffController::class, 'destroyDepartment'])
    ->middleware('auth')
    ->name('staff.departments.destroy');

// ------------------- Activity Logs -------------------
Route::get('/staff/logs', [StaffController::class, 'logs'])
    ->middleware('auth')
    ->name('staff.logs');

// ------------------- Shift & Attendance -------------------

// Attendance Dashboard
Route::get('/staff/attendance', [StaffController::class, 'attendanceIndex'])
    ->middleware('auth')
    ->name('staff.attendance.index');

// Create Attendance (manual entry)
Route::get('/staff/attendance/create', [StaffController::class, 'createAttendance'])
    ->middleware('auth')
    ->name('staff.attendance.create');

Route::post('/staff/attendance', [StaffController::class, 'storeAttendance'])
    ->middleware('auth')
    ->name('staff.attendance.store');

// Shifts Management
Route::get('/staff/attendance/shifts', [StaffController::class, 'shiftIndex'])
    ->middleware('auth')
    ->name('staff.attendance.shifts.index');

Route::get('/staff/attendance/shifts/create', [StaffController::class, 'createShift'])
    ->middleware('auth')
    ->name('staff.attendance.shifts.create');

Route::post('/staff/attendance/shifts', [StaffController::class, 'storeShift'])
    ->middleware('auth')
    ->name('staff.attendance.shifts.store');

Route::get('/staff/attendance/shifts/{shift}/edit', [StaffController::class, 'editShift'])
    ->middleware('auth')
    ->name('staff.attendance.shifts.edit');

Route::put('/staff/attendance/shifts/{shift}', [StaffController::class, 'updateShift'])
    ->middleware('auth')
    ->name('staff.attendance.shifts.update');

Route::delete('/staff/attendance/shifts/{shift}', [StaffController::class, 'destroyShift'])
    ->middleware('auth')
    ->name('staff.attendance.shifts.destroy');

// Reports
Route::get('/staff/attendance/reports', [StaffController::class, 'attendanceReports'])
    ->middleware('auth')
    ->name('staff.attendance.reports');

// Performance Reports Dashboard
Route::get('/staff/reports', [StaffController::class, 'performanceReportsIndex'])
    ->middleware('auth')
    ->name('staff.reports.index');

// Optional: Drill-down view for a single report
Route::get('/staff/reports/{report}', [StaffController::class, 'showPerformanceReport'])
    ->middleware('auth')
    ->name('staff.reports.show');

// ------------------- Dynamic Staff Routes -------------------
Route::get('/staff/{id}', [StaffController::class, 'show'])
    ->middleware('auth')
    ->name('staff.show');

Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])
    ->middleware('auth')
    ->name('staff.edit');

Route::put('/staff/{id}', [StaffController::class, 'update'])
    ->middleware('auth')
    ->name('staff.update');

Route::delete('/staff/{id}', [StaffController::class, 'destroy'])
    ->middleware('auth')
    ->name('staff.destroy');

// Users Management
Route::get('/users', [UserController::class, 'index'])
    ->middleware('auth')
    ->name('users.index');

Route::get('/users/create', [UserController::class, 'create'])
    ->middleware('auth')
    ->name('users.create');

Route::post('/users', [UserController::class, 'store'])
    ->middleware('auth')
    ->name('users.store');

Route::get('/users/{user}/edit', [UserController::class, 'edit'])
    ->middleware('auth')
    ->name('users.edit');

Route::put('/users/{user}', [UserController::class, 'update'])
    ->middleware('auth')
    ->name('users.update');

Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->middleware('auth')
    ->name('users.destroy');

Route::get('/users/trash', [UserController::class, 'trash'])
    ->middleware('auth')
    ->name('users.trash');

Route::patch('/users/{user}/restore', [UserController::class, 'restore'])
    ->middleware('auth')
    ->name('users.restore');

Route::delete('/users/{user}/force-delete', [UserController::class, 'forceDelete'])
    ->middleware('auth')
    ->name('users.forceDelete');

// Settings Module
Route::get('/settings/clinic', [SettingsController::class, 'clinic'])
    ->middleware('auth')
    ->name('settings.clinic');

Route::put('/settings/clinic', [SettingsController::class, 'updateClinic'])
    ->middleware('auth')
    ->name('settings.clinic.update');

Route::get('/settings/invoice', [SettingsController::class, 'invoice'])
    ->middleware('auth')
    ->name('settings.invoice');

Route::put('/settings/invoice', [SettingsController::class, 'updateInvoice'])
    ->middleware('auth')
    ->name('settings.invoice.update');

Route::get('/settings/theme', [SettingsController::class, 'theme'])
    ->middleware('auth')
    ->name('settings.theme');

Route::put('/settings/theme', [SettingsController::class, 'updateTheme'])
    ->middleware('auth')
    ->name('settings.theme.update');

Route::get('/settings/footer', [SettingsController::class, 'footer'])
    ->middleware('auth')
    ->name('settings.footer');

Route::put('/settings/footer', [SettingsController::class, 'updateFooter'])
    ->middleware('auth')
    ->name('settings.footer.update');

// Main resources
Route::resource('patients', PatientController::class)->middleware(['auth']);
Route::resource('suppliers', SupplierController::class)->middleware(['auth']);
Route::resource('drugs', DrugController::class)->middleware(['auth']);
Route::resource('staff', StaffController::class)->middleware(['auth']);

require __DIR__.'/auth.php';
