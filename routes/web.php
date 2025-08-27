<?php

use Illuminate\Support\Facades\Route;

Route::get('/scoreboard/{match}', function ($matchId) {
    return view('scoreboard', ['matchId' => $matchId]);
});

Route::get('/admin/match/{match}', function ($matchId) {
    return view('admin', ['matchId' => $matchId]);
});

Route::get('/login-page', function () {
    return view('login');
});