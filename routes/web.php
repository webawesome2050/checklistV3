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
Route::get('/generate-pdf/{entry_id}/{type?}', [CommonController::class, 'generatePDF'])->name('generate.pdf');
Route::get('/generate-pdf-atp/{entry_id}}', [CommonController::class, 'generatePDFATP'])->name('generate.atp');
Route::get('/generate-pdf-gmp/{entry_id}}', [CommonController::class, 'generatePDFGMP'])->name('generate.gmp');
Route::get('/generate-pdf-chemical/{entry_id}}', [CommonController::class, 'generatePDFChemical'])->name('generate.chemical');
Route::get('/generate-pdf-micro/{entry_id}}', [CommonController::class, 'generatePDFMicro'])->name('generate.micro');
Route::get('/generate-pdf-hat/{entry_id}}', [CommonController::class, 'generatePDFHAT'])->name('generate.hat');
Route::get('/generate-pdf-tvchiller/{entry_id}}', [CommonController::class, 'generatePDFTVC'])->name('generate.tvc');
Route::get('/generate-pdf-tvs/{entry_id}}', [CommonController::class, 'generatePDFTVS'])->name('generate.tvs');


// Route::get('/admin/check-lists/{id}/edit', [CheckListController::class, 'edit']);
