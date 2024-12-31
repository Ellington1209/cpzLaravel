<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Excel\LeitorExcelController;
use App\Http\Controllers\Membros\MembrosController;
use App\Http\Controllers\Whatsapp\WhatsAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rota pública para login
Route::post('/login', [LoginController::class, 'login']);

// Rotas protegidas por autenticação JWT
Route::middleware(['auth:api'])->group(function () {
    // Rota protegida para obter informações do usuário autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/excel', [LeitorExcelController::class, 'store']);

   

    Route::prefix('whatsapp')->group(function () {
        Route::post('/send-text', [WhatsAppController::class, 'sendMessagesToAll']);
        Route::post('/send-media', [WhatsAppController::class, 'sendMedia']);
    });
    
    Route::prefix('membros')->group(function () {
        Route::get('/', [MembrosController::class,'index']);
        Route::post('/create', [MembrosController::class,'store']);
        Route::put('/update/{id}', [MembrosController::class,'update']);
        Route::delete('/destroy/{id}', [MembrosController::class,'destroy']);
    });
});