<?php

use Illuminate\Support\Facades\Route;

Route::get('/slots/{slotMachine:name}', \App\Livewire\Machine::class)->name('slots');
