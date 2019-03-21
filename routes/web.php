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
    Route::view('/terms', 'terms', ['active' => 'terms']);
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', 'TransactionController@index')->name('admin-dashboard');
        Route::get('/adminTrans', 'TransactionController@adminTransaction')->name('admin-trans');
        Route::resource('/users', 'UsersController', [
            'names' => [
                'index' => 'users-index'
            ]
        ]);
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
        Route::get('/failed', 'TransactionController@failed')->name('collector-failed');
        Route::get('/deliquent', 'TransactionController@deliquent')->name('collector-deliquent');
        Route::resource('/transaction','TransactionController',[
            'names'=>[
                'index'=>'collector-dashboard',
                'create'=>'transaction-collect'
                
            ]
        ]);
        Route::resource('/process', 'LoanProcessController', [
            'names' => [
                'index' => 'admin-process',
                'create' => 'admin-create',
                'store' => 'admin-process-store'
            ]
        ]);
        Route::get('/calendar', 'SchedulesController@index')->name('admin-calendar');
    });

    Route::prefix('member')->group(function () {
        Route::get('/dashboard', 'SchedulesController@index')->name('member-dashboard');
        // When the user has not yet setup his account [The calendar wont show]
        Route::post('/dashboard/setup', 'DepositController@create')->name('member-setup');
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
        // TWO REDUNDUNDANT REDIRECTS???
        Route::get('/transaction', 'TransactionController@memberTransaction')->name('member-transaction');
        Route::get('/transactions', 'TransactionController@index')->name('member-transactions');

        Route::get('/receive/{id}/accept', 'LoanProcessController@accept')->name('member-accept');
        // Route::get('/process/{id}/edit', 'LoanProcessController@col_edit')->name('member-process');
        Route::resource('/process', 'LoanProcessController', [
            'names' => [
                'index' => 'member-process',
                'create' => 'member-create',
                'store' => 'member-process-store'
            ]
        ]);
        Route::get('/patronage', 'StatusController@index_patronage')->name('member-patronage');
        Route::get('/loan', 'StatusController@index_loan')->name('member-loan');
        Route::get('/saving', 'StatusController@index_saving')->name('member-saving');
        // Route::view('/transactions','users.admin.dashboard',['active'=>'transactions'])->name('member-transactions');
    });

    Route::prefix('collector')->group(function () {
        Route::get('/failed', 'TransactionController@failed')->name('collector-failed');
        Route::get('/deliquent', 'TransactionController@deliquent')->name('collector-deliquent');
        
        Route::resource('/transaction','TransactionController',[
            'names'=>[
                // 'index'=>'collector-dashboard',
                'create'=>'transaction-collect' 
            ]
        ]);
        Route::get('/transaction', 'TransactionController@index')->name('collector-dashboard');
        Route::resource('/profile', 'ProfilesController',[
            'names' => [
                'index' => 'profile-index',
                'show' => 'profile-show'
            ]
        ]);
        Route::get('/receive/{id}/accept', 'LoanProcessController@accept')->name('collector-accept');
        Route::get('/process/{id}/edit', 'LoanProcessController@col_edit')->name('collector-process');
        Route::resource('/process', 'LoanProcessController', [
            'names' => [
                'index' => 'collector-requests',
                'create' => 'collector-create',
                'store' => 'collector-process-store'
            ]
        ]);
    });


    Route::view('/terms', 'terms', ['active' => 'terms']);
});

// Route::get('/home', 'HomeController@index')->name('home');
