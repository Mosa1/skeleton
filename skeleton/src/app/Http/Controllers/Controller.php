<?php

namespace BetterFly\Skeleton\App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use BetterFly\Skeleton\App\Http\Responses\APIResponseTrait;
use Maatwebsite\Excel\Facades\Excel;
use BetterFly\Skeleton\App\Exports\BladeExport;

class Controller extends BaseController
{
    use APIResponseTrait, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function setStatus($model, $id, Request $request)
    {
        $model = 'App\\Modules\\' . $model . '\\' . $model;
        $response = $model::find($id)->update(['visibility' => $request->input('visibility')]);
        return response(['success' => $response]);
    }

    public function excelEXport(Request $request)
    {
        $data = json_decode($request->input('data'));

        return Excel::download(new BladeExport($data), 'export.xlsx');
    }
}
