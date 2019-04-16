<?php

namespace BetterFly\Skeleton\App\Http\Requests;

class UserRequest extends BaseFormRequest
{
    const REQUEST_LOGIN = "login";
    const REQUEST_REGISTER = "register";

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getRule();
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Email is required!',
            'name.required' => 'Name is required!',
            'password.required' => 'Password is required!',
            'c_password.required' => 'Password is required!',
            'email.email' => 'Email format is not correct',
            'credentials' => 'Credentials not found'
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'email' => 'trim|lowercase',
            'name' => 'trim|capitalize|escape'
        ];
    }

    private function getRequestType(){
        if($this->is(UserRequest::REQUEST_LOGIN))
            return UserRequest::REQUEST_LOGIN;

        if($this->is(UserRequest::REQUEST_REGISTER))
            return UserRequest::REQUEST_REGISTER;
    }

    private function getRule()
    {
        $type = $this->getRequestType();

        switch($type)
        {
            case UserRequest::REQUEST_LOGIN:
                return [
                    'email' => 'required|email',
                    'password' => 'required'
                ];
                break;
            case UserRequest::REQUEST_REGISTER:
                return [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users',
                    'password' => 'required',
                    'c_password' => 'required|same:password',
                ];
                break;
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'user.name.first' => 'required',
                        'user.name.last'  => 'required',
                        'user.email'      => 'required|email|unique:users,email',
                        'user.password'   => 'required|confirmed',
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'user.name.first' => 'required',
                        'user.name.last'  => 'required',
                        //'user.email'      => 'required|email|unique:users,email,'.$user->id,
                        'user.password'   => 'required|confirmed',
                    ];
                }
            default:break;
        }
    }
}
