<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', [
// 	"as" => "home",
// 	"uses" => "PageController@showHome"
// ]);

Route::auth();

Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

Route::get('setting', 'HomeController@setting');

Route::get('katmakalah/{id}', 'HomeController@katmakalah');

Route::get('profile/{tab?}', 'HomeController@profile');

Route::get('profileuser/{id}', 'HomeController@profileuser');

Route::get('plagiat', 'HomeController@plagiat');

Route::get('lolos', 'HomeController@lolos');

Route::get('diterima', 'HomeController@diterima');

Route::get('diterima/excel', 'HomeController@diterimaexcel');

Route::get('revisi', 'HomeController@revisi');

Route::get('ditolak', 'HomeController@ditolak');

Route::get('makalahuser', 'HomeController@makalahuser');

Route::post('simpanPengaturan', 'HomeController@simpanPengaturan');

Route::get('plotdosen/{id}','MakalahController@plotdosen');

Route::post('savedosen/{id}','MakalahController@savedosen');

Route::post('makalah/{id}/komentar','MakalahController@komentar');

Route::get('makalah/{id}/pdf','MakalahController@toPdf');

Route::post('profileupdate','HomeController@profileupdate');

Route::post('changepwd','HomeController@changepwd');

Route::get('compare/{id}','MakalahController@compare');

Route::get('materi', 'MateriController@index');

Route::get('template/books', [
'as'=>'admin.template.books',
'uses'=>'BooksController@generateExcelTemplate']);
Route::post('import/books', [
'as'=>'admin.import.books',
'uses'=>'BooksController@importExcel']);

Route::get('tugas', 'HomeController@index');

Route::get('404',function(){
	return view('errors/404');
});

Route::get('503',function(){
	return view('errors/503');
});

Route::resource('makalah', 'MakalahController');

Route::resource('dosen','DosenController');

Route::resource('mahasiswa','MahasiswaController');

Route::resource('stopword','StopwordController');

Route::resource('kategori','KategoriController');

Route::resource('materi','MateriController');

Route::resource('tugas','TugasController');

Route::resource('modul','ModulController');

Route::resource('makul','MakulController');
