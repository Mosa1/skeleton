<?php

namespace BetterFly\Skeleton\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

abstract class BaseFormRequest extends FormRequest
{
    use SanitizesInput;
    /**
     * For more sanitizer rule check https://github.com/Waavi/Sanitizer
     */
    public function validateResolved()
    {
        {
            $this->sanitize();
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
