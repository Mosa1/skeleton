<?php echo '<?php' ?>

Route::resource('{{$route_name}}', 'App\Modules\{{$moduleName}}\{{$moduleName}}Controller', [
    'names' => [
        'index' => '{{$route_name}}.index',
        'store' => '{{$route_name}}.store',
        'update' => '{{$route_name}}.update',
        'destroy' => '{{$route_name}}.delete'
    ]
]);
