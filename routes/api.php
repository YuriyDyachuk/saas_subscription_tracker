<?php

use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\SubscriptionOverviewController;
use Illuminate\Support\Facades\Route;

Route::resource('subscriptions', SubscriptionController::class);
Route::get('/overview', SubscriptionOverviewController::class);
