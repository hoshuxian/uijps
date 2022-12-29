<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PostController;


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
//Main
Route::get('/', function () {
    return view('mainpage');
});


//Sign up
Route::get('/signup', function () {
    return view('/Sign Up/signup');
});
Route::post('/studentsignup', 'App\Http\Controllers\UserController@studentsignup');
Route::post('/employersignup', 'App\Http\Controllers\UserController@employersignup');

//Login
Route::get('/login', function () {
    return view('/Login/login');
});
Route::get('/trylogin', 'App\Http\Controllers\UserController@login');

//Manage Admin
Route::get('/createstdprofile', function () {
    return view('/Admin/createstdprofile');
});
Route::post('/createstudent', 'App\Http\Controllers\UserController@createstudent');

Route::get('/createempprofile', function () {
    return view('/Admin/createempprofile');
});
Route::post('/createemployer', 'App\Http\Controllers\UserController@createemployer');

Route::get('/searchstdprofile', 'App\Http\Controllers\UserController@viewstudentlist');
Route::get('/searchstdprofile/search', 'App\Http\Controllers\UserController@studentlist');
Route::get('/searchstdprofile/{id}', 'App\Http\Controllers\UserController@deletestudentprofile');

Route::get('/searchempprofile', 'App\Http\Controllers\UserController@viewemployerlist');
Route::get('/searchempprofile/search', 'App\Http\Controllers\UserController@employerlist');
Route::get('/searchempprofile/{reg_no}', 'App\Http\Controllers\UserController@deleteemployerprofile');
Route::get('/displaystdprofile/{id}', 'App\Http\Controllers\UserController@displaystdprofile');

/*Route::get('/test', function () {
    return view('/Admin/test');
});*/

Route::get('/displayempprofile/{reg_no}', 'App\Http\Controllers\UserController@displayempprofile');
Route::get('/updateempprofile/{reg_no}', 'App\Http\Controllers\UserController@updateempprofile');
Route::post('/updateempprofile', 'App\Http\Controllers\UserController@update');
Route::get('/showstdprofile/{id}', 'App\Http\Controllers\UserController@showstdprofile');

//Manage Feedback
Route::get('/feedback', function () {
    return view('/Student/feedback');
});

Route::post('/submitfeedback', 'App\Http\Controllers\FeedbackController@submitfeedback');

Route::get('/empfeedback', function () {
    return view('/Employer/empfeedback');
});

Route::post('/submitempfeedback', 'App\Http\Controllers\FeedbackController@submitempfeedback');

//Manage search company Profile
Route::get('/searchcompany', 'App\Http\Controllers\UserController@viewcompanylist');
Route::get('/searchcompany/search', 'App\Http\Controllers\UserController@companylist');
Route::get('/displaycompanyprofile/{reg_no}', 'App\Http\Controllers\UserController@displaycompanyprofile');
Route::get('/logout', [UserController::class, 'destroy'])
                ->name('logout');

//Manage search student Profile
Route::get('/searchstudent', 'App\Http\Controllers\UserController@viewstdlist');
Route::get('/searchstudent/search', 'App\Http\Controllers\UserController@stdlist');
Route::get('/displaystudentprofile/{std_matric}', 'App\Http\Controllers\UserController@displaystudentprofile');

//Manage Feedback
Route::get('/searchfeedback', 'App\Http\Controllers\FeedbackController@viewfeedback');
Route::get('/searchfeedback/search', 'App\Http\Controllers\FeedbackController@feedbacklist');

//Manage Event
Route::get('/createevent', function () {
    return view('/Admin/createevent');
});
Route::post('/createevent', 'App\Http\Controllers\EventController@createevent');
Route::get('/searchevent', 'App\Http\Controllers\EventController@vieweventlist');
Route::get('/searchevent/search', 'App\Http\Controllers\EventController@eventlist');
Route::get('/displayevent/{event_name}', 'App\Http\Controllers\EventController@displayevent');

Route::get('/updateevent/{event_name}', 'App\Http\Controllers\EventController@updateevent');
Route::put('/updateevent/{event_name}', 'App\Http\Controllers\EventController@update2');

//Manage Internship Job Post
Route::get('/createjobpost', function () {
    return view('/Employer/createjobpost');
});
Route::post('/createjobpost', 'App\Http\Controllers\PostController@createjob');