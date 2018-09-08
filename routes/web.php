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

Route::group(['prefix'=>'admin','namespace'=>'Admin'], function(){
    Route::get('login','LoginController@showLoginForm');
    Route::post('login','LoginController@login');
    Route::get('logout','LoginController@logout');
    Route::group(['middleware' => 'auth'], function () {
        Route::group(['middleware'=>'admin'], function(){
            Route::get('/students/search','StudentController@search');
            Route::get('/tutors/search','TutorController@search');
            Route::get('/subjects/search','SubjectController@search');
            Route::get('/tutors/sessions/{id}','TutorController@sessions');
            Route::get('/students/sessions/{id}','StudentController@sessions');
            Route::resource('tutors', 'TutorController',['except'=>['destroy']]);
            Route::resource('students', 'StudentController',['except'=>['destroy']]);
            Route::resource('subjects', 'SubjectController',['except'=>['destroy']]);
            Route::get('/students/payments/{id}','StudentController@payments');
            Route::get('/tutors/payments/{id}','TutorController@payments');


        });
        Route::group(['middlware'=>'superadmin'], function(){
            Route::resource('admins', 'AdminController');
            Route::get('tutors/delete','TutorController@destroy')->name('tutors.destroy');
            Route::delete('students/{id}','StudentController@destroy')->name('students.destroy');
            Route::delete('tutors/{id}','TutorController@destroy')->name('tutors.destroy');
            Route::delete('subjects/{id}','SubjectController@destroy')->name('subjects.destroy');
        });




        Route::get('mapping-screen','MappingScreenController@index');
        Route::post('/dashboard/get-calender','DashboardController@getCalender');
        Route::post('/dashboard/add-new-schedule','DashboardController@addNewSchedule');
        Route::post('/dashboard/edit-schedule','DashboardController@editSchedule');
        Route::post('/dashboard/get-tutors','DashboardController@getTutors');
        Route::post('/dashboard/get-schedule','DashboardController@getSchedule');
        Route::get('/dashboard','DashboardController@index');
        Route::get('/','DashboardController@index');
    });
});

/*
 * TUTOR ROUTES
 */

Route::group(['prefix'=>'tutor','namespace'=>'Tutor'], function() {
    Route::get('login','LoginController@showLoginForm');
    Route::post('login','LoginController@login');
    Route::get('logout','LoginController@logout');

    Route::group(['middleware'=>'tutor'], function(){
        Route::get('whiteboard','WhiteBoardController@index');
        Route::post('/dashboard/get-calender','DashboardController@getCalender');
        Route::post('/dashboard/get-schedule','DashboardController@getSchedule');
        Route::get('/dashboard','DashboardController@index');
        Route::get('/','DashboardController@index');
    });
});


/*
 * STUDENT ROUTES
 */

Route::group(['prefix'=>'student','namespace'=>'Student'], function() {
    Route::get('login','LoginController@showLoginForm');
    Route::post('login','LoginController@login');
    Route::get('logout','LoginController@logout');

    Route::group(['middleware'=>'student'], function() {
        Route::get('whiteboard', 'WhiteBoardController@index');
        Route::post('/dashboard/get-calender', 'DashboardController@getCalender');
        Route::post('/dashboard/get-schedule', 'DashboardController@getSchedule');
        Route::get('/dashboard', 'DashboardController@index');
        Route::get('process-whiteboard-request', 'DashboardController@processDashboard');

        Route::get('/', 'DashboardController@index');
    });
});

Route::group(['prefix'=>'utility','middleware'=>'studenttutor'],function(){
    Route::post('save-canvas-image/{type}','UtilityController@saveCanvasImage');
    Route::post('read-sav-file/{type}','UtilityController@readSavFile');
    Route::post('save-canvas-to-sav/{type}','UtilityController@saveCanvasToSav');
    Route::post('send-report/{type}','UtilityController@sendReportToTechTeam');
    Route::get('download-file','UtilityController@downloadFile');
    Route::post('search-logs/{type}','UtilityController@searchSessionLogs');
});

//GET FILES FROM STORAGE
Route::get('storage/{filename}', function ($filename)
{
    $filename = preg_replace('/\-/','/',$filename);
    $path = storage_path("uploads/{$filename}");
    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});

Route::get('/home','HomeController@index');
Route::get('/',function (){
    return redirect('login');
});

Route::get('payment/payment', ['as' => 'payment', 'uses' => 'PaymentController@payment']);

# Status Route
Route::get('payment/status', ['as' => 'payment.status', 'uses' => 'PaymentController@status']);
Route::get('payment','PaymentController@index');