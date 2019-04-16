<?php echo '<?php' ?>

namespace {{$namespace}};

@if($config->translatable && !$config->translatableModel)
use Dimsav\Translatable\Translatable;
@endif

@if($config->sortable && !$config->translatableModel)
use Kalnoy\Nestedset\NodeTrait;
@endif

use Illuminate\Database\Eloquent\Model;

class {{ $moduleName }} extends Model
{
@if(!$config->translatableModel)
    protected $table = '{!! $config->tableName !!}';
    @if($config->sortable)
    use NodeTrait;
    @endif
    @if(property_exists($config,'useLaravelTimestamps') && !$config->useLaravelTimestamps)
    public $timestamps = false;
    @endif
    protected $fillable = [{!! $config->fillable !!}];

    @foreach($config->relations as $relation)
        public function {!! $relation->relationMethodName !!}()
        {
        @if($relation->relationType == 'belongsToMany')
            return $this->{!! $relation->relationType !!}('{!! $relation->relativeModel !!}','{!! $relation->tableName !!}','{!! $relation->foreignKey !!}','{!! $relation->relatedPivotKey !!}');
        @else
            return $this->{!! $relation->relationType !!}('{!! $relation->relativeModel !!}','{!! $relation->foreignKey !!}');
        @endif
        }
    @endforeach
@endif
@if($config->translatable && !$config->translatableModel)
    use Translatable;

    public $translationModel = '{!! $namespace.'\\'.$moduleName.'Translation' !!}';
    public $translatedAttributes = [{!! $config->translatedAttributes !!} ];
@endif

@if($config->translatableModel)
    public $timestamps = false;
    protected $fillable = [{!! $config->translatedAttributes !!}];
@endif

}