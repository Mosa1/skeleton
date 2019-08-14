<?php echo '<?php' ?>

namespace {{$namespace}};

@if(!$config->translatableModel)
    @if($config->translatable)
    use Astrotomic\Translatable\Translatable;
    @else
        @if(property_exists($config,'slugSource'))
        use Cviebrock\EloquentSluggable\Sluggable;
        @endif
    @endif
    @if($config->sortable)
    use Kalnoy\Nestedset\NodeTrait;
    @endif
@else
    @if(property_exists($config,'slugSource'))
    use Cviebrock\EloquentSluggable\Sluggable;
    @endif
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
        @elseif($relation->relationType == 'belongsTo' || $relation->relationType == 'hasMany' || $relation->relationType == 'hasOne')
            return $this->{!! $relation->relationType !!}('{!! $relation->relativeModel !!}','{!! $relation->foreignKey !!}');
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

@if(property_exists($config,'slugSource'))

    @if($config->translatable)
        @if($config->translatableModel)
        use Sluggable;

        public function sluggable()
        {
            return [
                'slug' => [
                    'source' => '{{ $config->slugSource }}',
                    'onUpdate' => true
                ]
            ];
        }
        @endif
    @else
        use Sluggable;

        public function sluggable()
        {
            return [
                'slug' => [
                    'source' => '{{ $config->slugSource }}',
                    'onUpdate' => true
                ]
            ];
        }
    @endif
@endif
@if($config->translatableModel)
    public $timestamps = false;
    protected $fillable = [{!! $config->translatedAttributes !!}];

@endif

}