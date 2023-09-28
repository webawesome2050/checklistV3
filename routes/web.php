<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/', function () {
//     if (!auth()->check()) {
//         return redirect('/admin/login');
//     }
//     return redirect('/admin'); 
// });



// Route::get('/PDF', [CommonController::class, 'PDFGenerate'])->name('view.PDF');
// Route::get('/generate-pdf/{entry_id}', [CommonController::class, 'generatePDF'])->name('view.PDF');
Route::get('/generate-pdf/{entry_id}', [CommonController::class, 'generatePDF'])->name('generate.pdf');


// Route::get('/admin/check-lists/{id}/edit', [CheckListController::class, 'edit']);
