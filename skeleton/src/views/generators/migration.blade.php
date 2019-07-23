<?php echo '<?php' ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create{{ucfirst($tableName)}}Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing {{$tableName}}
        Schema::create('{{ $tableName }}', function (Blueprint $table) {
            @if(property_exists($config, 'incrementField') && $config->incrementField)
                $table->increments('{{$config->incrementField}}');
            @endif
            @foreach($dbFields as $field)
                {!! $field !!}
            @endforeach
            @if($config->useLaravelTimestamps)
                $table->timestamps();
            @endif

            @foreach($config->relations as $field => $relation)
                @if($relation->relationType == 'belongsTo')
                    $table->unsignedInteger('{!! $relation->relatedPivotKey !!}')->nullable();
                    $table->foreign('{!! $relation->relatedPivotKey !!}')->references('{!! $relation->relativeModelIncrementField !!}')->on('{!! $relation->relativeModelTableName !!}')->onDelete('cascade')->onUpdate('cascade');
                @endif
            @endforeach
        });
        @if($config->translatable)
            Schema::create('{{ $tableName }}_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('{{ $tableName.'_'.$config->incrementField  }}')->unsigned();
            @foreach($translatableDbFields as $field)
                {!! $field !!}
            @endforeach
            $table->string('locale')->index();


            $table->unique(['{{ $tableName.'_'.$config->incrementField  }}','locale']);
            $table->foreign('{{ $tableName.'_'.$config->incrementField  }}')->references('{{ $config->incrementField }}')->on('{{ $tableName }}')->onDelete('cascade');

            });
        @endif
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        @if($config->translatable)
        Schema::dropIfExists('{{ $tableName }}_translations');
        @endif
        Schema::dropIfExists('{{ $tableName }}');
    }
}
