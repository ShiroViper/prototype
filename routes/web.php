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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::middleware(['guest'])->group(function () {
    Route::view('/', 'welcome', ['active' => 'welcome']);
    Route::view('/about', 'about', ['active' => 'about']);
    // Route::view('/terms', 'terms', ['active' => 'terms']);
    Route::post('/request', 'MemberRequestController@memberRequest');
});

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::prefix('admin')->middleware('admin-routes')->group(function () {
        Route::get('/dashboard', 'TransactionController@index')->name('admin-dashboard');
        Route::get('{id}/accept', 'MemberRequestController@accept');
        Route::get('{id}/decline', 'MemberRequestController@decline');
        Route::get('/adminTrans', 'TransactionController@adminTransaction')->name('admin-trans');
        Route::resource('/users', 'UsersController', [
            'names' => [
                'index' => 'users-index'
            ]
        ]);
        Route::get('/users/{id}/inactive', 'UsersController@inactive');
        Route::get('/users/{id}/active', 'UsersController@active');
        Route::get('/requests/{id}/accept', 'LoanRequestsController@accept');
        Route::get('/requests/{id}/reject', 'LoanRequestsController@reject');
        Route::resource('/requests', 'LoanRequestsController', [
            'names' => [
                'index' => 'admin-requests'
            ]
        ])->except([
            'edit', 'show', 'update'
        ]);
        Route::resource('/profile', 'ProfilesController',[
            'names' => [
                'index' => 'profile-index',
                'show' => 'profile-show'
            ]
        ]);

        Route::post('/calendar/available', 'DaysOffController@available');

        Route::get('/failed', 'TransactionController@failed')->name('admin-failed');
        Route::get('/deliquent', 'TransactionController@deliquent')->name('admin-deliquent');
        Route::get('/transaction/{id}/generate', 'TransactionController@generatepdf')->name('generate-pdf');
        Route::resource('/transaction','TransactionController',[
            'names'=>[
                'index'=>'admin-dashboard',
                'create'=>'transaction-collect'
                
            ]
        ]);
        Route::resource('/process', 'ProcessController', [
            'names' => [
                'index' => 'admin-process',
                'create' => 'admin-create',
                'store' => 'admin-process-store'
            ]
        ]);
        Route::get('/calendar', 'SchedulesController@index')->name('admin-calendar');
        
        Route::get('/cancel/{id}/accept', 'MemberController@accept')->name('admin-cancel-accept');
        Route::get('/cancel/{id}/reject', 'MemberController@reject')->name('admin-cancel-reject');
        Route::get('/change_pass', 'MemberController@changePassword')->name('change-password');
        Route::post('/change_pass/change', 'MemberController@change');

        Route::get('/status', 'StatusController@index')->name('admin-status');
        Route::get('/receive/{id}/accept', 'StatusController@accept')->name('admin-accept');

        Route::get('/distribute', 'AdminController@distribute')->name('admin-distribute');
    });

    Route::prefix('member')->group(function () {
        Route::get('/dashboard', 'SchedulesController@index')->name('member-dashboard');
        // When the user has not yet setup his account [The calendar wont show]
        Route::post('/dashboard/setup', 'UsersController@setup')->name('member-setup');
        Route::post('/partial/loan', 'LoanRequestsController@partial_loan')->name('member-partial-loan');
        Route::resource('/requests', 'LoanRequestsController', [
            'names' => [
                'index' => 'member-requests',
                'create' => 'member-create-request',
                'show' => 'member-loan'
            ]
        ]);
        Route::resource('/profile', 'ProfilesController',[
            'names' => [
                'index' => 'profile-index',
                'show' => 'profile-show'
            ]
        ]);
        // TWO REDUNDUNDANT REDIRECTS??? : okay na
        Route::get('/transactions', 'TransactionController@index')->name('member-transactions');
        Route::get('/sent/{id}/{token}/accept', 'TransactionController@accept')->name('loan-payment-accept');
        Route::get('/sent/{id}/d_accept', 'TransactionController@deposit_accept')->name('deposit-accept');
        Route::get('/transaction/{id}/generate', 'TransactionController@generatepdf')->name('generate-pdf');
        
        Route::get('/receive/{id}/{token}/accept', 'ProcessController@accept')->name('member-accept');
        // Route::get('/process/{id}/edit', 'ProcessController@col_edit')->name('member-process');
        Route::resource('/process', 'ProcessController', [
            'names' => [
                'index' => 'member-process',
                'create' => 'member-create',
                'store' => 'member-process-store'
            ]
        ]);
        
        Route::get('/cancel', 'MemberController@cancel')->name('member-cancel');
        Route::post('/cancel/archive/', 'MemberController@update')->name('member-cancel-archive');
        Route::get('/cancel/destroy', 'MemberController@destroy')->name('member-cancel-destroy');
        Route::get('/change_pass', 'MemberController@changePassword')->name('change-password');

        Route::get('/distribution/{id}/accept', 'DistributionController@accept')->name('member-distribution');
        
    });

    Route::prefix('collector')->group(function () {
        Route::get('/failed', 'TransactionController@failed')->name('collector-failed');
        Route::get('/deliquent', 'TransactionController@deliquent')->name('collector-deliquent');        
        Route::get('/member_searched', ['as'=>'search', 'uses'=>'TransactionController@partial_store']);
        Route::get('/transaction', 'TransactionController@index')->name('collector-dashboard');
        Route::get('/transaction/{id}/generate', 'TransactionController@generatepdf')->name('generate-pdf');
        Route::resource('/transaction','TransactionController',[
            'names'=>[
                'index'=>'collector-dashboard',
                'create'=>'transaction-collect' 
            ]
        ]);
        Route::resource('/profile', 'ProfilesController',[
            'names' => [
                'index' => 'profile-index',
                'show' => 'profile-show'
            ]
        ]);
        Route::get('/receive/{id}/{token}/accept', 'ProcessController@accept')->name('collector-accept');
        Route::get('/process/{id}/edit', 'ProcessController@col_edit')->name('collector-process');
        Route::resource('/process', 'ProcessController', [
            'names' => [
                'index' => 'collector-requests',
                'create' => 'collector-create',
                'store' => 'collector-process-store'
            ]
        ]);

        Route::get('/cancel', 'MemberController@cancel')->name('collector-cancel');
        Route::post('/cancel/archive/', 'MemberController@update')->name('collector-cancel-archive');
        Route::get('/cancel/destroy', 'MemberController@destroy')->name('collector-cancel-destroy');
        Route::get('/change_pass', 'MemberController@changePassword')->name('change-password');
        Route::post('/change', 'MemberController@change')->name('change');

        Route::get('/status', 'StatusController@index')->name('collector-status');
        Route::get('{token}/transfer/money', 'StatusController@transfer')->name('collector-transfer');
    });


    Route::view('/terms', 'terms', ['active' => 'terms']);
});

// Route::get('/home', 'HomeController@index')->name('home');
