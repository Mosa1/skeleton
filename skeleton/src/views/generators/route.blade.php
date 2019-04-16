<?php echo '<?php' ?>

Route::resource('{{$modulePlural}}', 'App\Modules\{{$moduleName}}\{{$moduleName}}Controller', [
    'names' => [
        'index' => '{{$modulePlural}}.index',
        'store' => '{{$modulePlural}}.store',
        'update' => '{{$modulePlural}}.update',
        'destroy' => '{{$modulePlural}}.delete'
    ]
]);
