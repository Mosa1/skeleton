<?php echo '<?php' ?>

namespace {{$namespace}};


use BetterFly\Skeleton\Services\BaseService;

class {{ $moduleName }}Service extends BaseService{

  public function __construct({{ $moduleName }}Repository $repository){
    parent::__construct($repository);
  }
}