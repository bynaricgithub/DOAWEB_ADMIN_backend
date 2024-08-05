<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return File::get(public_path() . '/index.html');
});
