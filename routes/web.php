<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/reset-password', function (Request $request) {
    $token = $request->query('token');

    return redirect("automarket://reset-password?token=$token");
});