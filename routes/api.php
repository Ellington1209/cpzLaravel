<?php

use App\Http\Controllers\Excel\LeitorExcelController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/send-messages', [WhatsAppController::class, 'sendMessagesToAll']);
//Route::post('/excel/import', [LeitorExcelController::class, 'store']);