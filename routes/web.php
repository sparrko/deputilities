<?php

Route::get('/', 'App\Http\Controllers\StartController@main')->name('main');
Route::get('/login', 'App\Http\Controllers\StartController@login')->name('login');
Route::post('/login', 'App\Http\Controllers\StartController@loginPost');
Route::get('/logout', 'App\Http\Controllers\StartController@logout')->name('logout');
Route::get('/profile', 'App\Http\Controllers\StartController@profile')->name('profile');
Route::post('/profile', 'App\Http\Controllers\StartController@profilePost')->name('profile_post');
Route::get('/aboutCompany', 'App\Http\Controllers\CompanyInfoController@aboutCompany')->name('aboutCompany');

Route::prefix('news')->group(function () {
    Route::get('/view/{id}', 'App\Http\Controllers\StartController@viewNews')->name('news_view_tenant');
    Route::get('/', 'App\Http\Controllers\NewsController@listForAll')->name('news_list_for_all');
});

Route::middleware('auth')->namespace('\App\Http\Controllers')->group(function() {
    Route::prefix('admin')->group(function () {

        Route::get('/', function () {
            return redirect(route('main'));
        });

        Route::prefix('news')->group(function () {
            Route::get('/', 'NewsController@list')->name('news');
            Route::get('/view/{id}', 'NewsController@view')->name('news_view');
            Route::post('/archive', 'NewsController@archive')->name('news_archive');
            Route::get('/create', 'NewsController@create')->name('news_create');
            Route::post('/create', 'NewsController@editPost')->name('news_create');
            Route::get('/edit/{id}', 'NewsController@edit')->name('news_edit');
        });

        Route::get('/company', 'CompanyInfoController@companyInfo')->name('company_info');
        Route::post('/company', 'CompanyInfoController@saveCompanyInfo');
    });

    Route::prefix('staffOfficers')->group(function () {
        Route::get('/', 'StaffController@list')->name('staff');
        Route::get('/create', 'StaffController@create')->name('staff_create');
        Route::post('/create', 'StaffController@editPost')->name('staff_create');
        Route::get('/edit/{id}', 'StaffController@edit')->name('staff_edit');
        Route::post('/archive', 'StaffController@archive')->name('staff_archive');
    });

    Route::prefix('reference')->group(function () {
        Route::prefix('streets')->group(function () {
            Route::get('/', 'ReferenceController@streets')->name('streets');
            Route::get('/create', 'ReferenceController@createStreet')->name('streets_create');
            Route::post('/create', 'ReferenceController@editPostStreet')->name('streets_create');
            Route::get('/edit/{id}', 'ReferenceController@editStreet')->name('streets_edit');
            Route::post('/archive', 'ReferenceController@archiveStreet')->name('streets_archive');
        });
        Route::prefix('houses')->group(function () {
            Route::get('/', 'ReferenceController@houses')->name('houses');
            Route::get('/create', 'ReferenceController@createHouse')->name('houses_create');
            Route::post('/create', 'ReferenceController@editPostHouse')->name('houses_create');
            Route::get('/edit/{id}', 'ReferenceController@editHouse')->name('houses_edit');
            Route::post('/archive', 'ReferenceController@archiveHouse')->name('houses_archive');
        });
        Route::prefix('tenants')->group(function () {
            Route::get('/', 'ReferenceController@tenants')->name('tenants');
            Route::get('/create', 'ReferenceController@createTenant')->name('tenants_create');
            Route::post('/create', 'ReferenceController@editPostTenant')->name('tenants_create');
            Route::get('/edit/{id}', 'ReferenceController@editTenant')->name('tenants_edit');
            Route::post('/archive', 'ReferenceController@archiveTenant')->name('tenants_archive');
        });
    });

    Route::prefix('tickets')->group(function () {
        Route::get('/', 'TicketController@tickets')->name('tickets');
        Route::get('/create', 'TicketController@createTicket')->name('ticket_create');
        Route::post('/create', 'TicketController@createPostTicket')->name('ticket_create');
        Route::get('/edit/{id}', 'TicketController@editTicket')->name('ticket_edit');
        Route::post('/edit/{id}', 'TicketController@editPostTicket')->name('ticket_edit');
        Route::get('/archive/{id}', 'TicketController@archiveTicket')->name('ticket_archive');
        Route::post('/archivePost/{id}', 'TicketController@archivePostTicket')->name('ticket_archive_post');
        Route::post('/complete/{id}', 'TicketController@completePostTicket')->name('ticket_complete');
    });

    Route::prefix('ticketTypes')->group(function () {
        Route::get('/', 'TicketTypeController@list')->name('ticket_types');
        Route::get('/create', 'TicketTypeController@create')->name('ticket_type_create');
        Route::post('/create', 'TicketTypeController@editPost')->name('ticket_type_create');
        Route::get('/edit/{id}', 'TicketTypeController@edit')->name('ticket_type_edit');
        Route::post('/archive', 'TicketTypeController@archive')->name('ticket_type_archive');
    });

    Route::prefix('dyn')->group(function () {
        Route::get('/getAddressByTenant', 'DynController@getAddressByTenant')->name('get_address_by_tenant');
    });

    Route::prefix('printable')->group(function () {
        Route::get('/ticket/{id}', 'PrintController@ticket')->name('print_ticket');
        Route::get('/outfitOrder/{id}', 'PrintController@outfitOrder')->name('print_outfit_order');
    });

});
