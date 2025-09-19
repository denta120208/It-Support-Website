<?php

use Illuminate\Support\Facades\Route;

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
Route::any('/', 'HomeController@index')->name('home');
Route::any('/home', 'HomeController@index')->name('home');
Route::post('/dashboardType', 'HomeController@dashboardType')->name('dashboardType');
Route::post('/dashboardPic', 'HomeController@dashboardPic')->name('dashboardPic');

Auth::routes();
Route::get('/SSO/{id}/{ix?}','SsoController@token');
Route::get('/logout','SsoController@logout');
Route::get('/logoutSupport', function(){
    Session::flush();
    return redirect('https://sso.metropolitanland.com/LogoutAllApps');
});

Route::get('/change_project/{id}','SupportController@change_project')->name('change_project');

// View Ticketing
Route::get('/viewListTicketing', 'Ticketing\ticketingController@index')->name('viewListTicketing');
Route::get('/viewInputTicketing', 'Ticketing\ticketingController@viewInputTicketing')->name('viewInputTicketing');
Route::post('/saveDataTicket', 'Ticketing\ticketingController@saveDataTicket')->name('saveDataTicket');
Route::post('/filterDataTicket', 'Ticketing\ticketingController@filterDataTicket')->name('filterDataTicket');
Route::get('/viewDataTicketing/{TRANS_TICKET_NOCHAR}', 'Ticketing\ticketingController@viewDataTicketing')->name('viewDataTicketing');
Route::get('/viewNotAssignTicket/{TRANS_TICKET_NOCHAR}', 'Ticketing\ticketingController@viewNotAssignTicket')->name('viewNotAssignTicket');
Route::post('/editDataTicketHistory', 'Ticketing\ticketingController@editDataTicketHistory')->name('editDataTicketHistory');
Route::post('/editDataTicketNotAssign', 'Ticketing\ticketingController@editDataTicketNotAssign')->name('editDataTicketNotAssign');
Route::get('/viewCloseTicketing/{TRANS_TICKET_NOCHAR}', 'Ticketing\ticketingController@viewCloseTicketing')->name('viewCloseTicketing');
Route::post('/respondCloseTicketing', 'Ticketing\ticketingController@respondCloseTicketing')->name('respondCloseTicketing');
Route::post('/reopenDataTicketing', 'Ticketing\ticketingController@reopenDataTicketing')->name('reopenDataTicketing'); 

// Select2
Route::get('/getCategory', 'Ticketing\ticketingController@getCategory')->name('getCategory');
Route::get('/getAplikasi/{ID_ROLE}', 'Ticketing\ticketingController@getAplikasi')->name('getAplikasi');

// Download File
Route::get('/downloadFile/{attachment_Name}', 'Ticketing\ticketingController@downloadFile')->name('downloadFile'); 

// View Report Summary
Route::get('/viewListReportSummary', 'ReportingTicket\reportingTicketController@viewListReportSummary')->name('viewListReportSummary');
Route::post('/filterViewListReportSummary', 'ReportingTicket\reportingTicketController@filterViewListReportSummary')->name('filterViewListReportSummary');
Route::get('/printReportSummary/{cutoff}/{pic}', 'ReportingTicket\reportingTicketController@printReportSummary')->name('printReportSummary');
Route::get('/excelReportSummary/{cutoff}/{pic}', 'ReportingTicket\reportingTicketController@excelReportSummary')->name('excelDataService');


// Notulen Print Preview
Route::get('/notulen/print/{id}', 'NotulenController@printPreview')->name('printNotulen');

// View Report Detail
Route::get('/viewListReportDetail', 'ReportingTicket\reportingTicketController@viewListReportDetail')->name('viewListReportDetail');
Route::post('/viewReportingDetail', 'ReportingTicket\reportingTicketController@viewReportingDetail')->name('viewReportingDetail');
Route::get('/printReportDetail/{id1}/{id2}/{id3}', 'ReportingTicket\reportingTicketController@printReportDetail')->name('printReportDetail');
Route::get('/excelReportDetail/{id1}/{id2}/{id3}', 'ReportingTicket\reportingTicketController@excelReportDetail')->name('excelReportDetail');



/////////////////////////////////////////////////////////////////MASTER DATA /////////////////////////////////////////////////////////////////////////
// UserIt
Route::get('/viewListUserIt', 'Ticketing\masterDataController@viewListUserIt')->name('viewListUserIt');
Route::get('/viewAddUserIt', 'Ticketing\masterDataController@viewAddUserIt')->name('viewAddUserIt');
Route::post('/saveAddUserIt', 'Ticketing\masterDataController@saveAddUserIt')->name('saveAddUserIt');
Route::get('/viewDataUserIt/{id}', 'Ticketing\masterDataController@viewDataUserIt')->name('viewDataUserIt');
Route::post('/saveEditUserIt', 'Ticketing\masterDataController@saveEditUserIt')->name('saveEditUserIt');
Route::get('/deleteDataUserIt/{id}', 'Ticketing\masterDataController@deleteDataUserIt')->name('deleteDataUserIt');
// Aplikasi
Route::get('/viewListAplikasi', 'Ticketing\masterDataController@viewListAplikasi')->name('viewListAplikasi');
Route::post('/saveAddAplikasi', 'Ticketing\masterDataController@saveAddAplikasi')->name('saveAddAplikasi');
Route::get('/viewDataAplikasi/{id}', 'Ticketing\masterDataController@viewDataAplikasi')->name('viewDataAplikasi');
Route::post('/saveEditAplikasi', 'Ticketing\masterDataController@saveEditAplikasi')->name('saveEditAplikasi');
Route::get('/deleteDataAplikasi/{id}', 'Ticketing\masterDataController@deleteDataAplikasi')->name('deleteDataAplikasi');
// EmailSupport
Route::get('/viewListEmailSupport', 'Ticketing\masterDataController@viewListEmailSupport')->name('viewListEmailSupport');
Route::get('/viewAddEmailSupport', 'Ticketing\masterDataController@viewAddEmailSupport')->name('viewAddEmailSupport');
Route::post('/saveAddEmailSupport', 'Ticketing\masterDataController@saveAddEmailSupport')->name('saveAddEmailSupport');
Route::get('/viewDataEmailSupport/{id}', 'Ticketing\masterDataController@viewEditEmailSupport')->name('viewEditEmailSupport');
Route::post('/saveEditEmailSupport', 'Ticketing\masterDataController@saveEditEmailSupport')->name('saveEditEmailSupport');
Route::get('/deleteDataEmailSupport/{id}', 'Ticketing\masterDataController@deleteDataEmailSupport')->name('deleteDataEmailSupport');
// RoleIt
Route::get('/viewlistRoleIt', 'Ticketing\masterDataController@viewlistRoleIt')->name('viewlistRoleIt');
Route::post('/saveAddRoleIt', 'Ticketing\masterDataController@saveAddRoleIt')->name('saveAddRoleIt');
Route::get('/viewDataRoleIt/{id}', 'Ticketing\masterDataController@viewDataRoleIt')->name('viewDataRoleIt');
Route::post('/saveEditRoleIt', 'Ticketing\masterDataController@saveEditRoleIt')->name('saveEditRoleIt');
Route::get('/deleteDataRoleIt/{id}', 'Ticketing\masterDataController@deleteDataRoleIt')->name('deleteDataRoleIt');
// Type Keluhan
Route::get('/viewlistTypeKeluhan', 'Ticketing\masterDataController@viewlistTypeKeluhan')->name('viewlistTypeKeluhan');
Route::post('/saveAddTypeKeluhan', 'Ticketing\masterDataController@saveAddTypeKeluhan')->name('saveAddTypeKeluhan');
Route::get('/viewDataTypeKeluhan/{id}', 'Ticketing\masterDataController@viewDataTypeKeluhan')->name('viewDataTypeKeluhan');
Route::post('/saveEditTypeKeluhan', 'Ticketing\masterDataController@saveEditTypeKeluhan')->name('saveEditTypeKeluhan');
Route::get('/deleteDataTypeKeluhan/{id}', 'Ticketing\masterDataController@deleteDataTypeKeluhan')->name('deleteDataTypeKeluhan');

// Notulen Rapat
Route::get('/notulen', 'NotulenController@viewInputNotulen')->name('viewInputNotulen');
Route::post('/notulen/save', 'NotulenController@saveNotulen')->name('saveNotulen');
Route::get('/notulen/list', 'NotulenController@listNotulen')->name('listNotulen');
Route::get('/notulen/{id}/edit', 'NotulenController@editNotulen')->name('editNotulen');
Route::post('/notulen/{id}/update', 'NotulenController@updateNotulen')->name('updateNotulen');
Route::get('/notulen/{id}', 'NotulenController@showNotulen')->name('showNotulen');
Route::post('/notulen/{id}/delete', 'NotulenController@deleteNotulen')->name('deleteNotulen');


//Route::get('/login', 'App\Http\Controllers\Auth\LoginController@login')->name('login');
// Route::post('/doLogin', 'App\Http\Controllers\Auth\LoginController@doLogin')->name('doLogin');
//cancelDataTicket
//Route::get('admin/home', 'App\Http\Controllers\HomeController@adminHome')->name('admin.home')->middleware('is_admin');
