<?php echo '<?php' ?>

Route::group(['middleware' => 'web'], function(){
    Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
        Route::resource('{{$modulePlural}}', 'App\Modules\{{$moduleName}}\{{$moduleName}}Controller', [
            'names' => [
                'index' => '{{$modulePlural}}.index',
                'store' => '{{$modulePlural}}.store',
                'update' => '{{$modulePlural}}.update',
                'destroy' => '{{$modulePlural}}.delete'
            ]
        ]);
    });
});
