<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware('admin')->namespace('App\Http\Controllers\Panel')->group(function () {
    Route::get('/'                         , 'IndexController@index')->name('dashboard');
    Route::get('dashboard'                 , 'IndexController@index')->name('dashboard');
    Route::get('panel/getcities/{id}'     , 'IndexController@getcities')->name('getcities');
    Route::resource('panel/finance'      , 'FinancialController');
    Route::resource('panel/owner'        , 'OwnerController');
    Route::resource('panel/menupanel'    , 'MenupanelController');
    Route::resource('panel/submenupanel' , 'SubmenupanelController');
    Route::resource('panel/menusite'     , 'MenusiteController');
    Route::resource('panel/submenusite'  , 'SubmenusiteController');
    Route::resource('panel/typeuser'     , 'TypeuserController');
    Route::resource('panel/siteuser'     , 'SiteuserController');
    Route::resource('panel/paneluser'    , 'PaneluserController');
    Route::resource('panel/roleuser'     , 'RoleuserController');
    Route::resource('panel/leveluser'    , 'LeveluserController');
    Route::resource('panel/useraccess'   , 'UseraccessController');
    Route::resource('panel/filemanager'  , 'FilemanagerController');
    Route::resource('panel/project'      , 'ProjectController');
    Route::resource('panel/paidmanage'   , 'PaidController');
    Route::resource('panel/receivemanage', 'ReceiveController');
    Route::resource('panel/account'      , 'AccountController');
    Route::resource('panel/company'      , 'CompanyController');
    Route::resource('company'            , 'CompanyController');
    Route::resource('minute'             , 'MinuteController');
    Route::resource('panel/flow'         , 'FlowController');
    Route::resource('panel/report'       , 'ReportController');
    Route::get('correspondence/data'     , 'CorrespondenceController@data')->name('correspondence.data');
    Route::resource('correspondence'     , 'CorrespondenceController');
    Route::resource('panel/contract'     , 'ContractController');

    Route::get('profile'                   , 'ProfileController@index')->name('profile');
    Route::get('userdata'                  , 'ProfileController@userdata')->name('userdata');
    Route::get('companydata'               , 'ProfileController@companydata')->name('companydata');
    Route::post('sales'                    , 'ProfileController@sales')->name('sales');


    Route::get('panel/calendar'                 , 'CalendarController@index')->name('calendar.index');
    Route::get('panel/calendar/events'          , 'CalendarController@getEvents')->name('calendar.events');
    Route::post('panel/calendar/store'          , 'CalendarController@store')->name('calendar.store');
    Route::patch('panel/calendar/update/{id}'   , 'CalendarController@update')->name('calendar.update');
    Route::delete('panel/calendar/delete/{id}'  , 'CalendarController@destroy')->name('calendar.destroy');

    Route::get('panel/changepassword'      , 'ChangePasswordController@index')->name('password.change.form');
    Route::post('panel/changepassword'     , 'ChangePasswordController@change')->name('password.change.submit');

    Route::post('panel/filestatus'         , 'FilemanagerController@filestatus')->name('filestatus');
    Route::post('panel/store'              , 'FilemanagerController@store')     ->name('storemedia');
    Route::get('panel/selectfile'          , 'FilemanagerController@selectfile')->name('selectfile');
    Route::delete('panel/deletefile'       , 'FilemanagerController@deletefile')->name('deletefile');

    /*charts*/
    Route::get('panel/invest-month'          , 'ChartController@invest')->name('invest-month');

});

Route::get('/toggle-theme', function () {
    $theme = session('theme') === 'theme-default-dark' ? 'theme-default' : 'theme-default-dark';
    session(['theme' => $theme]);
    return back();
})->name('toggle-theme');

Auth::routes();

Route::post('panel/fullregister'        , [App\Http\Controllers\Auth\FullRegisterController::class, 'register'])->name('fullregister');
Route::patch('panel/fullregister/{id}'  , [App\Http\Controllers\Auth\FullRegisterController::class, 'update'])->name('fullregister.update');
Route::get('logout'                     , [App\Http\Controllers\Auth\FullRegisterController::class, 'logout'])->name('logout');
Route::post('logout'                    , [App\Http\Controllers\Auth\FullRegisterController::class, 'logout'])->name('logout');
Route::get('login/{provider}'           , [App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider'])   ->name('redirectToProvider');
Route::get('login/{provider}/callback'  , [App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback'])->name('handleProviderCallback');
Route::get('otplogin'                   , [App\Http\Controllers\Auth\LoginController::class, 'otplogin'])->name('otplogin');
Route::post('gettoken'                  , [App\Http\Controllers\Auth\LoginController::class, 'gettoken'])->name('gettoken');
Route::get('sendtoken'                  , [App\Http\Controllers\Auth\LoginController::class, 'sendtoken'])->name('sendtoken');
Route::post('checktoken'                , [App\Http\Controllers\Auth\LoginController::class, 'checktoken'])->name('checktoken');


Route::get('getdata', [App\Http\Controllers\Api\IndexController::class, 'getdata'])->name('getdata');
