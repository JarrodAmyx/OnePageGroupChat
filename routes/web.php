<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Chat;

Route::get( '/', [ Chat::class, 'index' ] );
Route::post( '/create', function()
{
	return view('create')->render();
});

Route::post( '/home', [ Chat::class, 'home' ] );
Route::post( '/viewChat', [ Chat::class, 'viewChat' ] );
Route::post( '/create-chat', [ Chat::class, 'createChat' ] );
Route::post( '/addMessage', [ Chat::class, 'addMessage' ] );
Route::post( '/', [ Chat::class, 'loginPost' ] );
Route::post( '/check-updates', [ Chat::class, 'checkUpdates' ] );
Route::get( '/logout', [ Chat::class, 'logout' ] );