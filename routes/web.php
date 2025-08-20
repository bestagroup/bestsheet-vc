<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware('admin')->namespace('App\Http\Controllers\Panel')->group(function () {
    Route::get('/'                         , 'IndexController@index')->name('dashboard');
    Route::get('dashboard'                 , 'IndexController@index')->name('dashboard');
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
    Route::resource('panel/calendar'     , 'CalendarController');
    Route::resource('panel/company'      , 'CompanyController');
    Route::resource('company'            , 'CompanyController');

    Route::get('profile'                   , 'ProfileController@index')->name('profile');
    Route::get('panel/changepassword'      , 'ChangePasswordController@index')->name('password.change.form');
    Route::post('panel/changepassword'     , 'ChangePasswordController@change')->name('password.change.submit');

    Route::post('panel/store'              , 'FilemanagerController@store')     ->name('storemedia');
    Route::get('panel/selectfile'          , 'FilemanagerController@selectfile')->name('selectfile');
    Route::delete('panel/deletefile'       , 'FilemanagerController@deletefile')->name('deletefile');

    /*charts*/
    Route::get('panel/invest-month'          , 'ChartController@invest')->name('invest-month');

});


//Route::middleware(['panel.access:panel'])->prefix('panel')->group(function () {
//    Route::get('profile'            , 'ProfileController@profile')              ->name('profile');
//});
//
//Route::middleware(['submenu.permission:can_insert,users'])->namespace('App\Http\Controllers\Panel')->prefix('panel')->group(function () {
//    Route::post('edit-user-profile' , 'ProfileController@edituserprofile')      ->name('edit-user-profile');
//});
//
//Route::middleware(['submenu.permission:can_update,users'])->namespace('App\Http\Controllers\Panel')->prefix('panel')->group(function () {
//    Route::PATCH('profile/update'           , 'ProfileController@update')       ->name('edituser');
//});
//
//Route::middleware(['submenu.permission:can_delete,users'])->namespace('App\Http\Controllers\Panel')->prefix('panel')->group(function () {
//    Route::delete('deleteuser'              , 'UserController@deleteuser')                          ->name('deleteuser');
//});





Route::get('/toggle-theme', function () {
    $theme = session('theme') === 'theme-default-dark' ? 'theme-default' : 'theme-default-dark';
    session(['theme' => $theme]);
    return back();
})->name('toggle-theme');

Auth::routes();

//Route::view('/panel/brand-management'           , 'panel.brand_management')         ->name('panel.brand_management');
//Route::view('/panel/profile-view'               , 'panel.profile_view')             ->name('panel.profile_view');
//Route::view('/panel/site-users'                 , 'panel.site_users')               ->name('panel.site_users');
//Route::view('/panel/notifications-management'   , 'panel.notifications_management') ->name('panel.notifications_management');
//Route::view('/panel/payments-management'        , 'panel.payments_management')      ->name('panel.payments_management');
//Route::view('/panel/site-menu-management'       , 'panel.site_menu_management')     ->name('panel.site_menu_management');
//Route::view('/panel/submenu-management'         , 'panel.submenu_management')       ->name('panel.submenu_management');
//Route::view('/panel/slides-management'          , 'panel.slides_management')        ->name('panel.slides_management');
//Route::view('/panel/customers-management'       , 'panel.customers_management')     ->name('panel.customers_management');
//Route::view('/panel/news-events-management'     , 'panel.news_events_management')   ->name('panel.news_events_management');
//Route::view('/panel/faq-management'             , 'panel.faq_management')           ->name('panel.faq_management');
//Route::view('/panel/employees-management'       , 'panel.employees_management')     ->name('panel.employees_management');
//Route::view('/panel/consulting-requests'        , 'panel.consulting_requests')      ->name('panel.consulting_requests');
//Route::view('/panel/inquiries-management'       , 'panel.inquiries_management')     ->name('panel.inquiries_management');
//Route::view('/panel/inquiry-fields'             , 'panel.inquiry_fields')           ->name('panel.inquiry_fields');
//Route::view('/panel/posts-management'           , 'panel.posts_management')         ->name('panel.posts_management');
//Route::view('/panel/educational-files'          , 'panel.educational_files')        ->name('panel.educational_files');
//Route::view('/panel/courses-management'         , 'panel.courses_management')       ->name('panel.courses_management');
//Route::view('/panel/media-management'           , 'panel.media_management')         ->name('panel.media_management');
//Route::view('/panel/discounts-management'       , 'panel.discounts_management')     ->name('panel.discounts_management');
//Route::view('/panel/file-management'            , 'panel.file_management')          ->name('panel.file_management');
//Route::view('/panel/file-management'            , 'panel.file_management')          ->name('panel.file_management');

Route::post('panel/fullregister', [App\Http\Controllers\Auth\FullRegisterController::class, 'register'])->name('fullregister');
Route::get('logout'             , [App\Http\Controllers\Auth\FullRegisterController::class, 'logout'])->name('logout');
Route::post('logout'            , [App\Http\Controllers\Auth\FullRegisterController::class, 'logout'])->name('logout');
