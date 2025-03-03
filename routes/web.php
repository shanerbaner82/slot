<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(){
   return redirect()->route('slots', 'default');
});
Route::get('/slots/{slotMachine:name}', \App\Livewire\Machine::class)->name('slots');
