<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Signup auth api routes
Route::post('/auth/admin/signup', 'AuthController@adminSignup');
Route::post('/auth/customer/signup', 'AuthController@customerSignup');

// Signin auth api Routes
Route::post('/auth/admin/signin', 'AuthController@adminSignin');
Route::post('/auth/customer/signin', 'AuthController@customerSignin');

/*
Customer API routes. Need customer authentication via middleware CustomerAuth
Need ID and ACCESSTOKEN in http header
 */
Route::group(['middleware' => 'customerAuth', 'prefix' => 'c'], function() {
	
	// View profile
	Route::get('/profile', 'CustomerController@profile');

	// Create contract
	Route::post('/contract', 'CustomerController@createContract');

	// View all contracts
	Route::get('/contracts', 'CustomerController@showContracts');

	// View particular contracts
	Route::get('/contract/{id}', 'CustomerController@viewContract');	

	// Add an email query
	Route::post('/contract/email/query/{id}', 'CustomerController@emailQuery');

});

/*
Admin API routes. Need customer authentication via middleware AdminAuth
Need ID and ACCESSTOKEN in http header
 */
Route::group(['middleware' => 'adminAuth', 'prefix' => 'a'], function() {

	// View profile
	Route::get('/profile', 'AdminController@profile');

	// Get list of all customers
	Route::get('/customers', 'AdminController@showCustomers');

	// View particular customer
	Route::get('/customer/{id}', 'AdminController@viewCustomer');

	// View all contracts
	Route::get('/contracts', 'AdminController@showContracts');

	// View all particular contract
	Route::get('/contract/{id}', 'AdminController@viewContract');

	// Get the contract assigned
	Route::post('/contract/{id}/assign', 'AdminController@assign');

	// delete contract
	Route::post('/contract/{id}/delete', 'AdminController@deleteContract');

	// Update contract
	Route::post('/contract/{id}/update', 'AdminController@updateContract');

	// View email queries
	Route::get('/emailqueries', 'AdminController@emailQueries');

	// Send mail
	Route::post('/sendmail', 'AdminController@sendMail');

	// Create Mail List
	Route::post('/maillist/create', 'AdminController@createMailList');

	// get mail lists
	Route::get('/maillists', 'AdminController@getMailLists');

	// view mail list
	Route::get('/maillist/{id}', 'AdminController@viewMailList');

	// send mail to mail list
	Route::post('/maillist/send/{id}', 'AdminController@sendMailToMaillist');

	// delete mail list
	Route::get('/maillist/delete/{id}', 'AdminController@deleteMaillist');
});

