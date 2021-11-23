<?php
namespace BetterFly\Skeleton\App\Http\Transformers;

use Illuminate\Support\Facades\Auth;

class UserTransformer extends BaseTransformerAbstract
{
    public function transform($user)
    {
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => $user->is_super,
            'token' => $user->createToken('MyApp')->accessToken
        ];

//         if(Auth::user() && Auth::user()->hasRole('admin')){
//             $data['password'] = $user['password'];
//         }

        return $data;
    }
}
