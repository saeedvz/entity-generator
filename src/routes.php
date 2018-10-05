<?php

Route::group(['middleware' => config('entity-generator.middleware')], function () {
    Route::get('/entity-generator', 'Cotlet\EntityGenerator\Controllers\EntityController@index')->name('entity-generator::index');
    Route::post('/entity-generator/create', 'Cotlet\EntityGenerator\Controllers\EntityController@create')->name('entity-generator::create');
});
