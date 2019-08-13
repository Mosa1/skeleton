<?php echo '<?php' ?>

namespace {{$namespace}};

use BetterFly\Skeleton\Repositories\BaseRepository;

class {{ $moduleName }}Repository extends BaseRepository {
    public function __construct({{$moduleName}} $model){
        parent::__construct($model);
    }
}