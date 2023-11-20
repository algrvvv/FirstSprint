<?php

use App\Services\Https\Route;

Route::get('/', 'welcome');

Route::fallback();
