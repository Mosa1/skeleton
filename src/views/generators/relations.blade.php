<?php echo '<?php' ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create{{$relation->migrationClassName}}Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    @if($relation->relationType == 'belongsToMany')
    public function up()
    {
        Schema::create('{{ $relation->tableName }}', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('{{ $relation->foreignKey  }}')->unsigned()->nullable();
            $table->foreign('{{ $relation->foreignKey  }}')->references('{{ $relation->currentModelTableName }}')->on('{{ $relation->currentModelTableName }}')->onDelete('cascade');

            $table->integer('{{ $relation->relatedPivotKey  }}')->unsigned()->nullable();
            $table->foreign('{{ $relation->relatedPivotKey  }}')->references('{{ $relation->relativeModelIncrementField }}')->on('{{ $relation->relativeModelIncrementField }}')->onDelete('cascade');
        });
    }
    @endif

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{{ $relation->tableName }}');
    }
}
