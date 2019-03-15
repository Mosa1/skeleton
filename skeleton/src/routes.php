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
    Route::post('role', 'BetterFly\Skeleton\App\Http\Controllers\API\UserRoleController@createRole');
    // Route to create a new permission
    Route::post('permission', 'BetterFly\Skeleton\App\Http\Controllers\API\UserRoleController@createPermission');
    // Route to assign role to user
    Route::post('assign-role', 'BetterFly\Skeleton\App\Http\Controllers\API\UserRoleController@assignRole');
    // Route to attache permission to a role
    Route::post('attach-permission', 'BetterFly\Skeleton\App\Http\Controllers\API\UserRoleController@attachPermission');
    Route::post('update-standard-roles', 'BetterFly\Skeleton\App\Http\Controllers\API\UserRoleController@updateStandardRoles');

});

Route::group(['middleware' => 'web'], function () {
    Route::get('admin', 'BetterFly\Skeleton\App\Http\Controllers\Admin\LoginController@index')->name('betterfly.admin');
    Route::post('login', 'BetterFly\Skeleton\App\Http\Controllers\API\UserController@login')->name('betterfly.login');


    Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {

        Route::get('/locale/{locale}', function ($locale) {
            \Session::put('locale', $locale);
            return redirect()->back();
        })->name('admin.setLocale');

        Route::resource('file', 'BetterFly\Skeleton\App\Http\Controllers\Admin\FileController', [
            'names' => [
                'index' => 'file.index',
                'store' => 'file.store',
                'destroy' => 'file.delete'
            ]
        ]);

        Route::post('excel-export','BetterFly\Skeleton\App\Http\Controllers\Controller@excelExport')->name('excel-export');

        Route::post('validate-form', 'BetterFly\Skeleton\App\Http\Controllers\Admin\AjaxValidation@ajaxValidate')->name('ajax-validation');

        Route::patch('set-visibility/{Model}/{id}', 'BetterFly\Skeleton\App\Http\Controllers\Controller@setStatus')->name('set-visibility');

        Route::get('logout', 'BetterFly\Skeleton\App\Http\Controllers\API\UserController@logout')->name('betterfly.logout');

        Route::get('dashboard', 'BetterFly\Skeleton\App\Http\Controllers\Admin\DashboardController@index')->name('dashboard');
    });

});


$dirPath = app_path('Modules');
if (File::isDirectory($dirPath)) {
    $files = File::allFiles($dirPath);
    foreach ($files as $file) {
        if (strpos($file->getFilename(), '.route.php') !== false) {
            require_once $file->getPathName();
        }
    }
}