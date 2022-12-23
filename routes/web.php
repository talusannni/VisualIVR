<?php

use App\Models\Sheet;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SheetController;
use App\Http\Controllers\ProjectController;


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

//page route files
Route::get('/sheet/create/{id}', function () {
    return view('playground');
});

Route::get('/sheet/list/{project_id}', function ($project_id) {
    $data = Sheet::where(['project_id' => $project_id ])->get();
    return response()->json($data);
})->name('Sheet.list');

Route::get('/sheet/json/{sheet}', [SheetController::class, 'show'])->name('Sheet.json');

Route::post('/sheet/store', [SheetController::class, 'store'])->name('Sheet.store');
Route::post('/sheet/create', [SheetController::class, 'create'])->name('Sheet.create');
Route::post('/sheet/update', [SheetController::class, 'update'])->name('Sheet.update');
Route::post('/sheet/root', [SheetController::class, 'setRoot'])->name('Sheet.setRoot');
Route::post('/sheet/delete', [SheetController::class, 'destroy'])->name('Sheet.delete');

//project route files
Route::get('/project/list', function () {
    $data = Project::all ();
    return view ( 'projects.list' )->withData ( $data );
})->name('Project.list');
Route::post('/project/create', [ProjectController::class, 'create'])->name('Project.create');
Route::post('/project/update', [ProjectController::class, 'update'])->name('Project.update');
Route::post ( '/project/delete', function (Request $request) {
    Project::find ( $request->get('id') )->delete ();
    return response ()->json ([
        'message'=>'Project Deleted Successfully!!'
    ]);
} )->name('Project.delete');
Route::get('/project/design/{project_id}', function($project_id){
    return view ( 'projects.publish' )->with ( 'project_id', $project_id );
})->name('Project.design');
Route::get('/project/templates/{project}', [ProjectController::class, 'templates'])->name('Project.templates');
Route::post('/project/template/{project}', [ProjectController::class, 'template'])->name('Project.template');
Route::get('/vivr/{project}', [ProjectController::class, 'show'])->name('Project.show');
//Route::post('/project/delete', [ProjectController::class, 'destroy'])->name('Project.delete');
