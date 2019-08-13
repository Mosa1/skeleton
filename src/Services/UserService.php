<?php

namespace BetterFly\Skeleton\Services;

use BetterFly\Skeleton\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserService extends BaseService{
    public function __construct(UserRepository $repository){
        parent::__construct($repository);
    }

    public function authorize($credentials){
        return Auth::attempt($credentials);
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register($user)
    {
        $user['password'] = bcrypt($user['password']);
        return $this->create($user);
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
}