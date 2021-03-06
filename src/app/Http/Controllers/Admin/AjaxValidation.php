<?php

namespace BetterFly\Skeleton\App\Http\Controllers\Admin;

use BetterFly\Skeleton\App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxValidation extends Controller
{

    /**
     * Validate Data via ajax
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxValidate(Request $request)
    {
        $requestNameSpace = $request->input('request_name_space');
        $requestInstance = new $requestNameSpace();
        $requestInstance->setMethod($request->method());
        $validatedData = \Validator::make($request->all(),$requestInstance->rules());
        if($validatedData->fails()){
            return response(['success' => false,'message' => $validatedData->messages()]);
        }

        return response(['success' => true,'message' => 'succesfuly validate form']);
    }
}
