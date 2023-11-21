<?php

use Imissher\FirstSprint\Services\Https\Route;

Route::get('/', 'welcome');

Route::fallback();
