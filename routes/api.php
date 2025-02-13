<?php

use App\Http\Controllers\api\Client\SearchAction;
use App\Http\Controllers\api\ClientWithCarAndServiceAction\ListAction as ClientsListAction;
use App\Http\Controllers\api\ServiceAction\GetMaxLogNumberForServicesAction;
use Illuminate\Support\Facades\Route;

Route::get('/clients', ClientsListAction::class);
Route::get('/clients/{clientId}/cars/max-log-number', GetMaxLogNumberForServicesAction::class);
Route::post('/client-search', SearchAction::class);
