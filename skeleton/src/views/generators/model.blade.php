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

@if($config->translatable && !$config->translatableModel)
    use Translatable;

    @if($config->sortable)
        use NodeTrait;
    @endif

    @if(property_exists($config,'useLaravelTimestamps') && !$config->useLaravelTimestamps)
        public $timestamps = false;
    @endif

    protected $table = '{!! $config->tableName !!}';
    public $translationModel = '{!! $namespace.'\\'.$moduleName.'Translation' !!}';
    public $translatedAttributes = [{!! $config->translatedAttributes !!} ];
@endif

@if($config->translatableModel)
    public $timestamps = false;
    protected $fillable = [{!! $config->translatedAttributes !!}];
@elseif($config->translatable && !$config->translatableModel)
    protected $fillable = [{!! $config->modelFillable !!}];
@else
    @if(property_exists($config,'fillable'))
    protected $fillable = [{!! $config->fillable !!}];
    @endif
@endif

@if(!$config->translatableModel)
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
}