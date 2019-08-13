<?php echo '<?php' ?>

namespace {{$namespace}};

use BetterFly\Skeleton\App\Http\Requests\BaseFormRequest;

class {{ $moduleName }}Request extends BaseFormRequest
{
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

        ];
    }

    private function getRule()
    {
        $type = $this->getMethod();

        switch($type)
        {
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
            @foreach($ruleStorefields as $field)
                '{{$field['name']}}' => '{{$field['value']}}',
            @endforeach
            ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
            @foreach($ruleUpdatefields as $field)
                '{{$field['name']}}' => '{{$field['value']}}',
            @endforeach
            ];
                }
            default:break;
        }
    }
}

