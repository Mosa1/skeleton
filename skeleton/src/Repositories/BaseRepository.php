<?php

namespace BetterFly\Skeleton\Repositories;

use BetterFly\Skeleton\App\Http\Transformers\BaseTransformerAbstract as Transformer;
use Illuminate\Support\Facades\File;

class BaseRepository
{
    protected $model;
    protected $translatable;
    protected $relations;
    protected $translatableFields;

    public function __construct($model)
    {
        $this->model = new $model;
        $modelInfo = $this->determineTranslatable();
        $this->translatable = $modelInfo['translatable'];
        if ($this->translatable)
            $this->translatableFields = $modelInfo['fields'];
        $this->relations = $this->getModelRelations();
        $this->moduleCfg = $this->getModuleCfg();
    }

    public function getList($query = null)
    {
        $with = [];
        $query = $query ?? ['paginate' => 5];
        $data = $this->model;
        $paginate = isset($query['paginate']) ? $query['paginate'] : null;

        if (isset($query['with'])) {
            foreach ($query['with'] as $key => $value) {
                $with[] = $value;
            }
        }

        if ($with) $data = $data->with($with);
        if ($paginate) return $data->paginate($paginate);

        return $data->get();
    }

    public function create($data)
    {
        $data = Transformer::transfromFilledData($data, $this->translatable,$this->moduleCfg, $this->translatableFields);

        if ($this->translatable) $item = $this->model->create($data);
        else $item = new $this->model($data);

        $item->save();
        if ($this->relations) $this->syncRelations($data, $item);

        return $item;
    }

    //get item by id
    public function getById($itemId)
    {
        $item = $this->model;

        if ($this->relations) $item = $item->with($this->relations);

        return $item->find($itemId) ?? response('Item Not Found');
    }

    //requested item update
    public function update($data)
    {
        $item = $this->model::findOrFail($data['id']);
        if (!$item) response('Item Not found');
        $data = Transformer::transfromFilledData($data, $this->translatable, $this->translatableFields);

        $item->update($data);

        if ($this->relations) $this->syncRelations($data, $item);

        return $item;
    }

    //requested item delete
    public function delete($itemId)
    {
        $item = $this->model::find($itemId);

        return $item ? $this->model::find($itemId)->delete() : false;
    }

    //check if model is translatable
    public function determineTranslatable()
    {
        $modelTranslatable = $this->model->translationModel;
        if ($modelTranslatable)
            return ['translatable' => true, 'fields' => $this->model->translatedAttributes];
        else
            return ['translatable' => false];
    }

    //getting model relation method names
    public function getModelRelations($needle = null)
    {
        $relations = [];

        if ($needle !== null) {
            $relations[] = $this->getModelMethod($needle);
        } else {
            $allMethods = get_class_methods($this->model);
            foreach ($allMethods as $method) {
                if ($method == '__construct' || strpos($method, 'scope') === 0 || strpos($method, 'get') === 0 || strpos($method, 'set') === 0) break;
                $relations[] = $method;
            }
        }

        return $needle ? $relations[0] : $relations;
    }

    //syncs model and model's relations if isset in request
    public function syncRelations($data, $item)
    {
        foreach ($data as $field => $value) {
            $relationMethodName = $this->getModelRelations($field);
            if (!$relationMethodName) continue;
            $relationshipType = $this->learnMethodType($this->model, $relationMethodName);

            if ($relationshipType == 'BelongsToMany') {
                $item->{$relationMethodName}()->sync($value);
            } else {
                $item->{$relationMethodName}()->associate($value);
            }
        }

        return $item->save();
    }

    public function learnMethodType($model, $method)
    {
        $model = new $model;
        $relationClass = get_class($model->{$method}());

        return $this->getRelationShortNameByClass($relationClass);
    }

    public function getRelationShortNameByClass($relationClass)
    {
        return (new \ReflectionClass($relationClass))->getShortName();
    }


    //getting model's relations method names by needle( requested input[name] )
    public function getModelMethod($needle)
    {
        foreach ($this->relations as $relaion) {
            similar_text($needle, $relaion, $percentage);
            if ($percentage > 70) {
                return $relaion;
            }
        }
        return false;
    }

    public function getModuleCfg(){
        $moduleName = (new \ReflectionClass($this->model))->getShortName();

        $cfgPath = base_path('/app/Modules/'.$moduleName) . '/' . strtolower($moduleName) . '.config.json';

        if (File::exists($cfgPath)) {
            $json = File::get($cfgPath);
            $json = preg_replace('!/\*.*?\*/!s', '', $json);
            $json = json_decode($json);

            if (!$json) {
                exit($this->error('Invalid Config file! Please validate "' . $moduleName . '" file '));
            } else if (!$json->fields) {
                exit($this->error('Missing fields in Config file'));
            }

            return $json;
        }else{
            return [];
        }
    }
}