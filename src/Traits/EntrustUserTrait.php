<?php
namespace BetterFly\Skeleton\Traits;

use Zizaco\Entrust\Traits\EntrustUserTrait as OriginalEntrustUserTrait;

trait EntrustUserTrait
{
    use OriginalEntrustUserTrait {
        can as entrustCan;
        hasRole as entrustHasRole;
    }

    public function hasRole($name, $requireAll = false){
        if ($this->is_super)
            return true;
        return $this->entrustHasRole($name, $requireAll);
    }

    public function can($permission, $requireAll = false)
    {
        if ($this->is_super)
            return true;
        return $this->entrustCan($permission, $requireAll);
    }
}