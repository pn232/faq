<?php
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
Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin', 'HomeController@admin_dashboard')->name('admin');
Route::get('/superadmin', 'HomeController@superadmin_dashboard')->name('superadmin');
Route::get('/admin/view_users', 'UserController@index')->name('users');
Route::get('/admin/view_admins', 'UserController@index_admin')->name('admins');
Route::get('/admin/view_faqs', 'FaqController@index')->name('faqs');
Route::post('/admin/update_profile', 'UserController@update');
Route::get('/admin/{user_id}/delete_user', 'UserController@delete');
Route::get('/user/{user_id}/profile', 'ProfileController@create')->name('profile.create');
Route::get('/user/{user_id}/profile/{profile_id}', 'ProfileController@show')->name('profile.show');
Route::get('/user/{user_id}/profile/{profile_id}/edit', 'ProfileController@edit')->name('profile.edit');
Route::post('/user/{user_id}/profile/', 'ProfileController@store')->name('profile.store');
Route::patch('/user/{user_id}/profile/{profile_id}', 'ProfileController@update')->name('profile.update');
Route::delete('/user/{user_id}/profile/{profile_id}', 'ProfileController@destroy')->name('profile.destroy');
Route::get('/questions/{question_id}/answers/create', 'AnswerController@create')->name('answers.create');
Route::get('/questions/{question_id}/answers/{answer_id}', 'AnswerController@show')->name('answers.show');
Route::get('/questions/{question_id}/answers', 'AnswerController@show_all_answer')->name('answers.show_all_answer');
Route::get('/questions/{question_id}/answers/{answer_id}/edit', 'AnswerController@edit')->name('answers.edit');
Route::post('/questions/{question_id}/answers/', 'AnswerController@store')->name('answers.store');
Route::patch('/questions/{question_id}/answer/{answer_id}', 'AnswerController@update')->name('answers.update');
Route::delete('/questions/{question_id}/answer/{answer_id}', 'AnswerController@destroy')->name('answers.destroy');
Route::get('/admin/{answer_id}/answer_delete','AnswerController@delete_answer');
Route::post('/admin/{question_id}/question_update', 'QuestionController@update_question');
Route::get('/admin/{question_id}/question_delete', 'QuestionController@delete_question');
Route::resources([
    'questions' => 'QuestionController',
]);
