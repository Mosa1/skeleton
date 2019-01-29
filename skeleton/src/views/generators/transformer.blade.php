<?php echo '<?php' ?>

namespace {{$namespace}};

use BetterFly\Skeleton\App\Http\Transformers\BaseTransformerAbstract;
use Illuminate\Support\Facades\Auth;

class {{ $moduleName }}Transformer extends BaseTransformerAbstract
{
    public function transform(${{ strtolower($moduleName) }})
    {
        $data = [
    @foreach($transformerFields as $fieldname=>$field)
        '{{$fieldname}}' => ${{ strtolower($moduleName) }}->{{$field}},
    @endforeach
    ];

        return $data;
    }
}