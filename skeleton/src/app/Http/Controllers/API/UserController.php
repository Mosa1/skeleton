<?php

namespace BetterFly\Skeleton\App\Http\Controllers\API;

use BetterFly\Skeleton\App\Http\Controllers\Controller;
use BetterFly\Skeleton\App\Http\Requests\UserRequest;
use BetterFly\Skeleton\App\Http\Transformers\UserTransformer;
use BetterFly\Skeleton\Services\UserService;
use Illuminate\Support\Facades\Session;
use League\Fractal\Manager;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /* @var UserService */
    private $userService;

    /* @var Manager */
    private $fractal;

    /* @var UserTransformer */
    private $userTransformer;

    function __construct(Manager $fractal, UserTransformer $userTransformer, UserService $userService)
    {
        $this->fractal = $fractal;
        $this->userTransformer = $userTransformer;
        $this->userService = $userService;
    }


    public function login(UserRequest $request){
        $authorized = $this->userService->authorize($request->validated());

        if($authorized){
            $user = Auth::user(['name', 'email']);

            if($request->ajax()){
                $data = $this->userTransformer->transform($user);
                return $this->responseWithDataAndMessage($data, $this->SUCCESS_STATUS, 'Success');
            }else{
                return redirect()->intended(route('dashboard'));
            }
        }
        if($request->ajax()){
            return $this->responseWithError('Unauthorised', $this->NOT_AUTHORIZED);
        }else{
            return redirect()->back()->withErrors('Credentials not found','default');
        }

    }

    public function register(UserRequest $request)
    {
        // Will return only validated data
        $validated = $request->validated();

        // Registering
        $user = $this->userService->register($validated);

        // Transforming for API JSON
        $json = $this->userTransformer->transform($user);

        return response()->json(['success' => $json], $this->SUCCESS_STATUS);
    }
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->SUCCESS_STATUS);
    }

    /**
     * User Logout
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        if(Auth::user()) Auth::logout();

        return redirect(route('betterfly.admin'));
    }
}