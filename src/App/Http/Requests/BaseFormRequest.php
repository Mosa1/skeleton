<?php

namespace BetterFly\Skeleton\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


abstract class BaseFormRequest extends FormRequest
{
    /**
     * For more sanitizer rule check https://github.com/Waavi/Sanitizer
     */
    public function validateResolved()
    {
        {
            parent::validateResolved();
        }
    }

    /*protected function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
      if(!$this->ajax())
          parent::failedValidation($validator);

      throw new HttpResponseException(response()->json($validator->errors(), 422));
    }*/


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize();
}
