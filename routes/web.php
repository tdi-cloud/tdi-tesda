<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\BatchesController;
use App\Http\Controllers\CoverPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeclarationController;
use App\Http\Controllers\EnrolledProgramsController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\ForeignController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\ProgramSupportingDocumentController;
use App\Http\Controllers\Regionalreportcontroller;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RequirementsController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SubmissionsController;
use App\Http\Controllers\TESDAOrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResourceSpeakerController;
use Illuminate\Support\Facades\Route;
use App\Models\Program;

Route::middleware('auth')->group(function () {
//LOGOUT
Route::post('/logout', Logout::class);
    
Route::get('/gmanning', [EmployeesController::class, 'index'])->name('register');
Route::get('/', function () { return view('enrolled.programs');});
Route::get('/profile', [UserController::class,'index'])->name('profile');

// MONITORING
Route::get('/dashboard', fn () => view('monitoring.dashboard'));
Route::get('/programs', fn () => view('monitoring.programs'));
Route::get('/calendar', fn () => view('monitoring.calendar'));
Route::get('/employees', fn () => view('monitoring.employees'));

// Enrolled Programs
Route::get('/enrolled', fn () => view('enrolled.programs'));

// PROGRAMS 
Route::post('/create-program', [ProgramsController::class,'store'])->name('create.program');
Route::get('/programs/{id}', [ProgramsController::class,'showDetails'])->name('view.programs');
Route::get('/programs/{id}/participants', [ProgramsController::class,'show'])->name('view.programs');
Route::get('/get-programs', [ProgramsController::class, 'getAll']);
Route::get('/programs-count', [ProgramsController::class, 'getProgramsCount']);
Route::get('/programs/{id}/tesda-order', [ProgramsController::class, 'getTesdaOrders']);
Route::get('/my-programs', [ProgramsController::class, 'myPrograms']);
Route::get('/editor', function () { return view('editor');});
Route::get('/programs/{id}/certificate', [ProgramsController::class,'showCertficates'])->name('view.certificates');
Route::get('/programs/{id}/edit', [ProgramsController::class, 'edit']);
Route::put('/programs/{id}', [ProgramsController::class, 'update']);
Route::delete('/programs/{id}/delete', [ProgramsController::class, 'destroy'])->name('programs.destroy');



//BATCHES
Route::post('/create-batches', [BatchesController::class,'store'])->name('create.batch');
Route::get('/batches', [BatchesController::class, 'index'])->name('batches.index');
Route::get('/batches/{code}/participants', [BatchesController::class,'getBatches']);
Route::get('/batch/{id}/edit', [BatchesController::class,'edit']);
Route::post('/batch/{id}', [BatchesController::class,'update']);
Route::get('/batch/{id}/delete', [BatchesController::class,'destroy']);
Route::get('/batches/events', [BatchesController::class, 'events']);

// PARTICIPANTS
Route::get('/participants/bulk-add/{batch}', [ParticipantsController::class, 'showBulkAdd'])->name('participants.bulk-add');
Route::post('/participants/bulk-add', [ParticipantsController::class, 'bulkAdd'])->name('api.participants.bulk-add');
Route::get('/participants/{id}/delete', [ParticipantsController::class,'destroy']);
Route::get('/participants/{id}/clear', [ParticipantsController::class,'clearByBatch']);
Route::post('/participant/save-attendance', [ParticipantsController::class, 'saveAttendance']);
Route::post('/participant/set-all-hours', [ParticipantsController::class, 'setAllHours']);
Route::post('/participants/{id}/move-order', [ParticipantsController::class, 'moveOrder']);
Route::post('/participants/store', [ParticipantsController::class, 'store']);
Route::get('/employees/{empcode}/profile', [ParticipantsController::class, 'show'])->name('employee.profile');
 

// REQUIREMENTS 
Route::get('/programs/{id}/requirements', [ProgramsController::class,'showRequirement'])->name('view.requriement');
Route::post('/create-requirement', [RequirementsController::class,'create']);
Route::get('/get-requirements/{program}', [RequirementsController::class,'getRequirements']);
Route::delete('/requirements/{id}/delete', [RequirementsController::class, 'destroy']);
Route::get('/requirements/{program_code}/{participant_id}', [RequirementsController::class, 'getRequirementsView']);

// COVERPAGE 
Route::post('/upload-cover', [CoverPageController::class, 'upload']);
Route::delete('/cover/{id}', [CoverPageController::class, 'destroy']);


//EMPLOYEES
Route::get('/employees-data', [EmployeesController::class, 'employees']);
Route::get('/employees', [EmployeesController::class, 'employeesList']);
Route::get('/employee-trainings', [EmployeesController::class, 'getEmployeeTrainings']);
Route::get('/employees-progress', fn () => view('monitoring.employees'));
Route::get('/employees/search', [EmployeesController::class, 'searchSelect']);
Route::get('/employee/{empcode}/view', [EmployeesController::class, 'view']);

// TESDA ORDER
Route::post('/TESDAOrder/store', [TESDAOrderController::class, 'store']);
Route::get('/tesda-order/{id}', [TESDAOrderController::class, 'TESDAOrder']);
Route::get('/tesda-orders/{program_code}', [TESDAOrderController::class, 'show']);
Route::get('/tesda-orders/delete/{id}', [TESDAOrderController::class, 'destroy']);



// DASHBOARD 
Route::get('/batches/trend/data', [BatchesController::class, 'trendData']);
Route::post('/report/tpmr-pdf', [DashboardController::class, 'generateTPMRPdf']);
Route::get('/user/program-count', [DashboardController::class, 'getUserProgramCount']);
Route::get('/training-stats/8hrs', [DashboardController::class, 'getTrainingStats8hrs']);
Route::get('/training-stats/40hrs', [DashboardController::class, 'getTrainingStats40hrs']);
Route::get('/training-stats/8hrs/bars', [DashboardController::class, 'getTrainingStats8hrsBars']);
Route::get('/training-stats/40hrs/bars', [DashboardController::class, 'getTrainingStats40hrsBars']);

// SUBMISSIONS 
Route::post('/submissions/store', [SubmissionsController::class, 'store']);
Route::post('/submissions/admin/store', [SubmissionsController::class, 'adminStore']);
Route::delete('/submissions/delete/{submission}', [SubmissionsController::class, 'destroy'])->name('submissions.destroy');
Route::delete('/submissions/admin/delete/{id}', [SubmissionsController::class, 'adminDestroy'])->name('submissions.admindestroy');
Route::get('/programs/{id}/submissions', [ProgramsController::class,'showSubmissions'])->name('view.submission');
Route::get('/get-submissions', [SubmissionsController::class, 'index']);
Route::get('/get-submission/{id}', [SubmissionsController::class, 'show']);
Route::post('/update-submission/{id}', [SubmissionsController::class, 'update']);
Route::get('/participants/{id}/available-requirements', [SubmissionsController::class, 'availableRequirements']);


// CERTIFICATE 


// TPMR
Route::get('/tpmr', fn () => view('monitoring.tpmr.tpmr'));

Route::get('/training-submissions', [RegionalReportController::class, 'index']);
Route::post('/training-submissions', [RegionalReportController::class, 'store']);
Route::delete('/training-submissions/{id}', [RegionalReportController::class, 'destroy']);

// DECLARATION OF COMPLETERS
// Check if batch has completers (AJAX)
Route::get('/batches/{batch}/check-completers', [DeclarationController::class, 'checkCompleters'])->name('batches.check-completers');

// Generate PDF
Route::get('/batches/{batch}/declaration-pdf', [DeclarationController::class, 'generatePdf'])->name('batches.declaration-pdf');

// Search employees for signatory (AJAX)
Route::get('/employees/declaration/search', [DeclarationController::class, 'searchEmployee'])->name('employees.search');

// FOREIGN SCHOLARSHIP PROGRAMS 
Route::get('/foreign-programs/create', [ForeignController::class, 'create']);
Route::post('/foreign-programs/store', [ForeignController::class, 'store'])
    ->name('foreign-programs.store');


// SUPPORTING DOCS 
// routes/web.php

Route::prefix('programs/{program}/supporting-documents')
    ->name('supporting-documents.')
    ->group(function () {

        // Full page view
        Route::get('/', [ProgramSupportingDocumentController::class, 'show'])
            ->name('show');

        // JSON — fetchDocs() AJAX call
        Route::get('/data', [ProgramSupportingDocumentController::class, 'index'])
            ->name('index');
    });

// AJAX POST store
Route::post('supporting-documents', [ProgramSupportingDocumentController::class, 'store'])
    ->name('supporting-documents.store');

// AJAX DELETE
Route::delete('supporting-documents/{supportingDocument}', [ProgramSupportingDocumentController::class, 'destroy'])
    ->name('supporting-documents.destroy');

    Route::get('/program-supporting-documents', [ProgramSupportingDocumentController::class, 'main_index'])
    ->name('program-supporting-documents.mainindex');

// RESOURCE SPEAKER
// routes/web.php
Route::get('/programs/{program}/resource-speakers', [ResourceSpeakerController::class, 'page'])->name('resource-speakers.page');

// AJAX routes
Route::prefix('programs/{program}/resource-speakers')
    ->name('resource-speakers.')
    ->group(function () {
        Route::get('/list',                [ResourceSpeakerController::class, 'index'])  ->name('index');
        Route::post('/',                   [ResourceSpeakerController::class, 'store'])  ->name('store');
        Route::get('/{resourceSpeaker}',   [ResourceSpeakerController::class, 'show'])   ->name('show');
        Route::put('/{resourceSpeaker}',   [ResourceSpeakerController::class, 'update']) ->name('update');
        Route::delete('/{resourceSpeaker}',[ResourceSpeakerController::class, 'destroy'])->name('destroy');
    });

// ENROLLED PROGRAM 
// Show single program enrollment detail
    Route::get('enrolled/{participant}', [EnrolledProgramsController::class, 'show'])
        ->name('enrollment.show');
 
    // Submit a requirement
    Route::post('enrolled/submissions', [EnrolledProgramsController::class, 'store'])
        ->name('enrolled.submissions.store');
 
    // Download certificate
    Route::get('enrolled/{participant}/certificate', [EnrolledProgramsController::class, 'downloadCertificate'])
        ->name('certificate.download');

        Route::delete('enrolled/submission/{id}', [EnrolledProgramsController::class, 'destroy'])
    ->name('enrolled.submission.destroy');

// MISSING SUBMISSIONS 

Route::get(
    '/programs/{program_code}/missing-submissions',
    [SubmissionsController::class, 'missingSubmissions']
);

});





// REGISTRATION SECTION 
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::post('/register/send-otp', [RegisterController::class, 'sendOtp'])->name('register.sendOtp');
Route::get('/register/verify-otp', [RegisterController::class, 'showOtpForm'])->name('otp.verify.form');
Route::post('/register/verify-otp', [RegisterController::class, 'verifyOtp'])->name('otp.verify');

//USER CHECK EMPCODE
Route::post('/register/check-empcode', [RegisterController::class,'checkEmpcode'])->name('register.checkEmpcode');


//LOGIN SECTION
Route::view('/login','auth.login')->middleware('guest')->name('login');
Route::post('/login', Login::class)->middleware('guest');


// FORGOT PASSWORD 
Route::get('forgot-password', [ForgotPasswordController::class,'index'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class,'sendLink'])->name('password.email');
Route::get('/reset-password/{token}',[ResetPasswordController::class,'index'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class,'reset'])->name('password.update');