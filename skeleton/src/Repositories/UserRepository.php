<?php

namespace BetterFly\Skeleton\Repositories;


use BetterFly\Skeleton\Models\User;

class UserRepository extends BaseRepository {

    public function __construct(User $model){
        parent::__construct($model);
    }
}