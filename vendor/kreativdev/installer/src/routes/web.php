<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => "Kreativdev\Installer\Controllers", "middleware" => "web"], function() {
    Route::get('add-installer', 'KdInstallerController@index')->name('kd-installer.index');
    Route::post('add-installer', 'KdInstallerController@addInstaller')->name('kd-installer.add');
});

?>