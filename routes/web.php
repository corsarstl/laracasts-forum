<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::view('scan', 'scan');

Route::get('/home', 'HomeController@index');

Route::get('threads/search', 'SearchController@show');

Route::get('threads', 'ThreadsController@index')->name('threads');
Route::get('threads/create', 'ThreadsController@create');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show');
Route::patch('threads/{channel}/{thread}', 'ThreadsController@update')->name('threads.update');
Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::post('threads', 'ThreadsController@store')->middleware('must-be-confirmed');
Route::get('threads/{channel}', 'ThreadsController@index');

Route::post('locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::post('replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');

Route::get('threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::patch('replies/{reply}', 'RepliesController@update');
Route::delete('replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');

Route::post('replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy');

Route::get('profiles/{user}', 'ProfilesController@show')->name('profile');

Route::get('profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

Route::post('threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::get('register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');

Route::get('api/users', 'Api\UsersController@index');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');
