<?php

namespace BetterFly\Skeleton\Services;

class BaseService{

  protected $repository;

  public function __construct($repository){
    $this->repository = $repository;
  }

  public function getList($query = null) {
    $result = $this->repository->getList($query);

    return $result;
  }

  public function create($data) {
    return $this->repository->create($data);
  }

  public function getById($itemId){
    return $this->repository->getById($itemId);
  }

  public function update($data) {
    return $this->repository->update($data);
  }

  public function delete($itemId) {
    return $this->repository->delete($itemId);
  }
}