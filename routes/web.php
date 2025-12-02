<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\RoleController;

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
Route::get('/profile', [ProfileController::class, 'edit'])->middleware(['auth'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->middleware(['auth'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware(['auth'])->name('profile.destroy');

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
Route::get('/patients/prescriptions', [PatientController::class, 'prescriptions'])->middleware(['auth'])->name('patients.prescriptions');
Route::post('/patients/prescriptions', [PatientController::class, 'storePrescription'])->middleware(['auth'])->name('patients.prescriptions.store');

// Medical Records
Route::get('/patients/records', [PatientController::class, 'records'])->middleware(['auth'])->name('patients.records');
Route::post('/patients/records', [PatientController::class, 'storeRecord'])->middleware(['auth'])->name('patients.records.store');

// Billing & Insurance
Route::get('/patients/billing', [PatientController::class, 'billing'])->middleware(['auth'])->name('patients.billing');
Route::post('/patients/billing', [PatientController::class, 'storeBilling'])->middleware(['auth'])->name('patients.billing.store');

// Reports & Analytics
Route::get('/patients/reports', [PatientController::class, 'reports'])->middleware(['auth'])->name('patients.reports');
Route::get('/patients/analytics', [PatientController::class, 'analytics'])->middleware(['auth'])->name('patients.analytics');

// Prescriptions renewal actions
Route::post('/prescriptions/{id}/renewals/approve', [PrescriptionController::class, 'approveRenewal'])->middleware(['auth'])->name('prescriptions.renewals.approve');
Route::post('/prescriptions/{id}/renewals/decline', [PrescriptionController::class, 'declineRenewal'])->middleware(['auth'])->name('prescriptions.renewals.decline');

// Archived Suppliers routes (Soft Deletes) — must come BEFORE resource route
Route::get('/suppliers/archived', [SupplierController::class, 'archived'])->middleware(['auth'])->name('suppliers.archived');
Route::post('/suppliers/{id}/restore', [SupplierController::class, 'restore'])->middleware(['auth'])->name('suppliers.restore');
Route::delete('/suppliers/{id}/force-delete', [SupplierController::class, 'forceDelete'])->middleware(['auth'])->name('suppliers.forceDelete');

// Supplier module (CRUD)
Route::resource('suppliers', SupplierController::class)->middleware(['auth']);

// Supplier → Purchase Orders (scoped create/store)
Route::get('/suppliers/{supplier}/purchase-orders/create', [PurchaseOrderController::class, 'create'])->middleware(['auth'])->name('suppliers.po.create');
Route::post('/suppliers/{supplier}/purchase-orders', [PurchaseOrderController::class, 'store'])->middleware(['auth'])->name('suppliers.po.store');

// Purchase Orders (full resource)
Route::resource('purchase-orders', PurchaseOrderController::class)->middleware(['auth'])->names('purchaseOrders');

// Receive delivery (GRN) for a Purchase Order
Route::post('/purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->middleware(['auth'])->name('purchaseOrders.receive');

// Supplier Invoices & Payments (optional if implemented)
Route::post('/suppliers/{supplier}/invoices', [SupplierInvoiceController::class, 'store'])->middleware(['auth'])->name('suppliers.invoices.store');
Route::post('/supplier-invoices/{invoice}/payments', [SupplierInvoiceController::class, 'pay'])->middleware(['auth'])->name('supplierInvoices.pay');

// Main resources
Route::resource('patients', PatientController::class)->middleware(['auth']);
Route::resource('drugs', DrugController::class)->middleware(['auth']);
Route::resource('prescriptions', PrescriptionController::class)->middleware(['auth']);
Route::resource('staff', StaffController::class)->middleware(['auth']);
Route::resource('roles', RoleController::class)->middleware(['auth']);

require __DIR__.'/auth.php';
