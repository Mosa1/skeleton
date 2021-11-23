<?php

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['auth:api', 'auth'], 'prefix' => 'api'], function () {

    Route::post('details', 'BetterFly\Skeleton\App\Http\Controllers\API\UserController@details');

    // Route to create a new role
//    Route::post('role', 'BetterFly\Skeleton\App\Http\Controllers\API\UserRoleController@createRole');
//    // Route to create a new permission
//    Route::post('permission', 'BetterFly\Skeleton\App\Http\Controllers\API\UserRoleController@createPermission');
//    // Route to assign role to user
//    Route::post('assign-role', 'BetterFly\Skeleton\App\Http\Controllers\API\UserRoleController@assignRole');
//    // Route to attache permission to a role
//    Route::post('attach-permission', 'BetterFly\Skeleton\App\Http\Controllers\API\UserRoleController@attachPermission');
//    Route::post('update-standard-roles', 'BetterFly\Skeleton\App\Http\Controllers\API\UserRoleController@updateStandardRoles');

});

Route::group(['middleware' => 'web'], function () {

    //Admin Path Variable
    $admin_path = config('skeleton.admin_path');

    //Login Blade Route
    Route::get($admin_path, 'BetterFly\Skeleton\App\Http\Controllers\Admin\LoginController@index')->name('login');

    //Login Request Route
    Route::post('login', 'BetterFly\Skeleton\App\Http\Controllers\API\UserController@login')->name('betterfly.login');

    Route::group(['middleware' => 'auth', 'prefix' => $admin_path], function () {

        //Excel export route
        Route::post('excel-export', 'BetterFly\Skeleton\App\Http\Controllers\Controller@excelExport')->name('excel-export');

        Route::patch('set-visibility/{Model}/{id}', 'BetterFly\Skeleton\App\Http\Controllers\Controller@setStatus')->name('set-visibility');
        Route::post('update-order/{Model}', 'BetterFly\Skeleton\App\Http\Controllers\Controller@updateOrder')->name('update-order');
        Route::get('logout', 'BetterFly\Skeleton\App\Http\Controllers\API\UserController@logout')->name('betterfly.logout');


        //Set Application control panel language
        Route::get('/locale/{locale}', function ($locale) {
            \Session::put('locale', $locale);
            return redirect()->back();
        })->name('admin.setLocale');


        //Translatable Texts route
        Route::resource('texts', 'BetterFly\Skeleton\App\Http\Controllers\Admin\TranslatableController', [
            'names' => [
                'index' => 'translatable.index',
                'store' => 'translatable.store',
                'destroy' => 'translatable.delete'
            ]
        ]);
        Route::get('texts-auto-translate', 'BetterFly\Skeleton\App\Http\Controllers\Admin\TranslatableController@autoTranslate')->name('excel-export');

        //User's route
        Route::resource('users', 'BetterFly\Skeleton\App\Http\Controllers\API\UserController', [
            'names' => [
                'index' => 'users.index',
                'store' => 'users.store',
                'destroy' => 'users.delete'
            ]
        ]);

        //Files and images route
        Route::resource('file', 'BetterFly\Skeleton\App\Http\Controllers\Admin\FileController', [
            'names' => [
                'index' => 'file.index',
                'store' => 'file.store',
                'destroy' => 'file.delete'
            ]
        ]);

        //Form Validation Route For Files and images
        Route::post('validate-form', 'BetterFly\Skeleton\App\Http\Controllers\Admin\AjaxValidation@ajaxValidate')->name('ajax-validation');

        Route::any('/ckfinder/browser', 'CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
            ->name('ckfinder_browser');

        Route::any('/ckfinder/connector', 'CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
            ->name('ckfinder_connector');

        //Aplication control panel Module routes
        $dirPath = app_path('Modules');
        if (File::isDirectory($dirPath)) {
            $files = File::allFiles($dirPath);
            foreach ($files as $file) {
                if (strpos($file->getFilename(), '.route.php') !== false) {
                    require_once $file->getPathName();
                }
            }
        }


    });

});

