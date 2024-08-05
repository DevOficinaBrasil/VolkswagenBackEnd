<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AutoRepairController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ConcessionaireAreaController;
use App\Http\Controllers\ConcessionaireControler;
use App\Http\Controllers\ConcessionaireResourceController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SheetsController;
use App\Http\Controllers\UserLegacyController;
use App\Http\Controllers\VacanciesController;
use App\Http\Middleware\ConvertBooleans;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\SanitizeInputs;
use App\Services\UserService;
use Illuminate\Support\Facades\Route;

Route::post('/signup', 
    [AccessController::class, 'signup']
)->middleware([ConvertBooleans::class, SanitizeInputs::class]);

Route::post('/getByCpf', [UserLegacyController::class, 'search']);
Route::post('/getByCNPJ', [AutoRepairController::class, 'getByCNPJ'])->middleware(SanitizeInputs::class);

Route::post('/sendMail', [UserService::class, 'teste']);

Route::get('/getAllUserInfo/{id}', [UserController::class, 'getAllUserInfo']);

Route::post('/updateUser', [UserController::class, 'update']);

Route::post('/updateAddress', [UserController::class, 'updateUserAddress']);
Route::post('/getConcessionaireOnlyByAddress', [ConcessionaireControler::class, 'getConcessionaireOnlyByAddress']);
Route::get('/getConcessionaireByAddress', [ConcessionaireControler::class, 'getByAddress']);
Route::post('/createBannerData', [BannerController::class, 'createBannerData']);

/**
 * 
 * Training
 * 
 */
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/trainings', [TrainingController::class, 'index']);
});
Route::get('/training/active', [TrainingController::class, 'active']);

/**
 * 
 * Conecta
 * 
 */
Route::post('/createUserConecta', [UserController::class, 'createUserConecta']);

Route::middleware(JwtMiddleware::class)->group(function(){
    Route::apiResource('users', UserController::class);
    Route::apiResource('managers', ManagerController::class);
    
    Route::get('/trainings/{id}', [TrainingController::class, 'exib']);

    Route::post('/registerSheet', [SheetsController::class, 'store']);
    Route::get('/verify/sheet', [SheetsController::class, 'verify']);

    Route::prefix('admin')->group(function () {
        Route::apiResource('/trainings', AdminController::class)->middleware(SanitizeInputs::class);
        Route::get('/trainings/users/{id}', [AdminController::class, 'showWithUsers']);
        Route::apiResource('/concessionaire', ConcessionaireResourceController::class);
        Route::apiResource('/vacancies', VacanciesController::class);
    });

    Route::prefix('manager')->group(function () {
        Route::get('/trainings/{concessionaireId}', [ConcessionaireAreaController::class, 'getTrainings']);
        Route::get('/users', [ConcessionaireAreaController::class, 'getUserOnTraining']);
        Route::patch('/updatePresence', [ConcessionaireAreaController::class, 'updatePresence']);
    });

    /**
     * 
     * Presence
     * 
     */
    Route::post('/putTrainingPresence', [TrainingController::class, 'putTrainingPresence']);

    /**
     * 
     * Training 
     * 
     */
    Route::put('/updateConcessionaireTraining', [TrainingController::class, 'updateConcessionaire']);

    Route::apiResource('training', TrainingController::class);
});