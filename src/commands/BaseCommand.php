<?php

namespace BetterFly\Skeleton\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BaseCommand extends Command
{
    private $plugins = ['Repository', 'Service', 'Request', 'Transformer', 'Controller', 'Model'];
    private $supported_relations = ['belongsTo', 'belongsToMany','hasMany'];

    public $progressBar;

    public function __construct()
    {
        parent::__construct();
    }

    protected function getConfigFile($moduleName, $existCheck = false, $withRelation = true)
    {
        $dirPath = $this->getDirPath($moduleName, 'Config');
        $file = $this->getFileNamePath($dirPath, 'Config', $moduleName);

        if (File::exists($file)) {
            $json = File::get($file);
            $json = preg_replace('!/\*.*?\*/!s', '', $json);
            $json = json_decode($json);

            if (!$json) {
                exit($this->error('Invalid Config file! Please validate "' . $file . '" file '));
            } else if (!$json->fields) {
                exit($this->error('Missing fields in Config file'));
            }

            $json = $this->refactorJson($json, $withRelation);

            return $json;
        } else if ($existCheck) {
            exit($this->error('Config file doesn\'t exists! First you need to call "php artisan betterfly:create_module ' . $moduleName . '" '));
        }
    }

    protected function getModuleName($className)
    {
        $pieces = array_values(array_filter(preg_split('/(?=[A-Z])/', $className)));

        $lastPiece = end($pieces);
        if (in_array($lastPiece, $this->plugins)) {
            array_pop($pieces);
        }

        return implode("", $pieces);
    }

    protected $dirPath = -1;

    protected function getDirPathType()
    {
        $dirPathType = $this->option('dirPathType');

        return $dirPathType ? $dirPathType : 0;
    }

    protected function getDirPath($className, $fileType = 'Repository')
    {
        switch ($this->getDirPathType()) {
            case 2:
                $fileTypePlural = str_plural($fileType);
                $dirPath = '/app/' . $fileTypePlural;
                break;

            default:
                $moduleName = $this->getModuleName($className);
                $dirPath = '/app/Modules/' . $moduleName;
                break;
        }

        return $dirPath;
    }

    public function createDirIfNeeded($dirPath)
    {
        if (!File::isDirectory(app_path("Modules"))) {
            File::makeDirectory(app_path("Modules"));
        }

        if (File::isDirectory($dirPath)) return;

        File::makeDirectory($dirPath);
    }

    protected function getFileNamePath($dirPath, $fileType, $moduleName)
    {
        switch ($fileType) {
            case "Config":
                return base_path($dirPath) . '/' . strtolower($moduleName) . '.config.json';
                break;
            case "Route":
                return base_path($dirPath) . '/' . strtolower($moduleName) . '.route.php';
                break;
            case "Migration":
                return base_path("/database/migrations") . "/" . date('Y_m_d_His') . "_create_" . strtolower($moduleName) . "_table.php";
                break;
            case "Model":
                return base_path($dirPath) . '/' . ucfirst($moduleName) . '.php';
                break;

            default:
                return base_path($dirPath) . '/' . ucfirst($moduleName) . $fileType . '.php';
                break;
        }
    }

    protected function getTemplateFile($fileType)
    {
        switch ($fileType) {
            default:
                return strtolower($fileType);
                break;
        }
    }

    /*
     * var $fileType = 'Repository','Service', 'Request','Model','Migration'
     * */
    protected function createFile($moduleName, $dirPath, $fileType, $namespace, $templateParams = [])
    {
        $templateFile = $this->getTemplateFile($fileType);

        $this->createDirIfNeeded(base_path($dirPath));
        $this->laravel->view->addNamespace('betterfly', substr(__DIR__, 0, -8) . 'views');

        $filePath = $this->getFileNamePath($dirPath, $fileType, $moduleName);

        $moduleName = ucfirst($moduleName);
        $data = compact('namespace', 'moduleName');

        $data = array_merge($data, $templateParams);

        $output = $this->laravel->view->make('betterfly::generators.' . $templateFile)->with($data)->render();

        if ($this->createFileWithData($filePath, $output))
            return true;

        return false;
    }


    public function refactorJson($cfg, $withRelation)
    {
        $cfg->translatable = property_exists($cfg, 'translatable') ? $cfg->translatable : false;
        $cfg->translatableModel = false;
        $cfg->sortable = property_exists($cfg, 'indexPlugin') && $cfg->indexPlugin[0]->pluginName == 'sortableList';
        $cfg->translatedAttributes = '';
        $cfg->tableName = property_exists($cfg, 'tableName') ? $cfg->tableName : strtolower(trim($cfg->title));
        $cfg->parentModule = property_exists($cfg, 'parentModule') ? $cfg->parentModule : false;

        if (property_exists($cfg, 'relations') && $withRelation)
            $cfg->relations = $this->getRelationsByCFG($cfg);
        else
            $cfg->relations = [];

        foreach ($cfg->fillable as $key => $field) {
            if (!property_exists($cfg->fields, $field)) continue;

            if (property_exists($cfg->fields->{$field}, 'translatable') && $cfg->fields->{$field}->translatable) {
                $cfg->translatedAttributes .= '"' . $field . '",';
                unset($cfg->fillable[$key]);
            }
        }

        if (property_exists($cfg, 'setVisibility')) {
            if ($cfg->translatable)
                $cfg->translatedAttributes .= '"visibility",';
            else
                $cfg->fillable[] = "visibility";
        }

        if ($cfg->sortable) {
            $cfg->fillable[] = "id";
        }

        $cfg->fillable = '"' . implode('" ,"', $cfg->fillable) . '"';

        return $cfg;
    }

    public function getRelationsByCFG($cfg)
    {
        $relations = [];

        foreach ($cfg->relations as $cfgRelation) {
            if (!in_array($cfgRelation->relationType, $this->supported_relations)) {
                $this->comment("\n \n Sorry But We Aren't Support This Relation Type (" . $cfgRelation->relationType . "), Update Our Package Or Define This Relationship Manually \n");
                continue;
            }

            $relativeModelCFG = $this->getConfigFile($cfgRelation->relativeModel, true, false);

            $this->validateRelation($cfgRelation, $relativeModelCFG);

            $relation = $this->setRelationVariables($cfg, $cfgRelation, $relativeModelCFG);
            if(property_exists($relation,'pluginName'))
                $cfg->fields->{$relation->relationMethodName} = $relation;
            $relations[$relation->relationMethodName] = $relation;
        }

        return $relations;
    }

    public function validateRelation($properties, $relativeModelCFG)
    {
        if (!property_exists($properties, 'relativeModel'))
            exit($this->error("\n Can't create relation missing relativeModel in"));
        if (!property_exists($relativeModelCFG, 'incrementField'))
            exit($this->error("\n Can't create relation missing IncrementField in " . $properties->relativeModel . '.config.json'));
        if (!property_exists($properties, 'relationMethodName'))
            exit($this->error("\n Can't create relation missing relationMethodName in config.json"));
        if (!property_exists($properties, 'relationMethodName'))
            exit($this->error("\n Can't create relation missing relationType in config.json"));
    }

    public function setRelationVariables($cfg, $properties, $relativeModelCFG)
    {
        if (!property_exists($properties, 'tableName')) {
            $tables[] = $relativeModelCFG->tableName;
            $tables[] = $cfg->tableName;
            sort($tables);
            $properties->tableName = $tables[0] . '_to_' . $tables[1];
            $properties->migrationClassName = ucfirst($tables[0]) . 'To' . ucfirst($tables[1]);
        }
        if (!property_exists($properties, 'foreignKey'))
            $properties->foreignKey = str_singular($cfg->tableName) . '_' . $cfg->incrementField;

        if (!property_exists($properties, 'relatedPivotKey'))
            $properties->relatedPivotKey = str_singular($relativeModelCFG->tableName) . '_' . $relativeModelCFG->incrementField;

        $properties->relativeModelIncrementField = $relativeModelCFG->incrementField;
        $properties->currentModelIncrementField = $cfg->incrementField;
        $properties->currentModelTableName = $cfg->tableName;
        $properties->relativeModelShortName = $properties->relativeModel;
        $properties->relativeModel = "App\Modules\\" . $properties->relativeModel . '\\' . $properties->relativeModel;
        $properties->relativeModelTableName = $relativeModelCFG->tableName;
        $properties->relativeMigrationName = 'create_' . strtolower($properties->relativeModelTableName) . '_table.php';

        return $properties;
    }

    public function getMigrationFile($fileName)
    {
        $files = File::allFiles(database_path('migrations'));

        foreach ($files as $file) {
            if (strpos($file->getFilename(), $fileName) !== false)
                return $file;
        }

        return false;
    }

    public function checkRelationsDatabases($relations)
    {
        $progressBar = ["message" => "Checking Relation's Databases \n"];
        $this->createProgressbar($progressBar);

        foreach ($relations as $relation) {
            $relation->readyForMake = false;
            $relativeMigrationFile = $this->getMigrationFile($relation->relativeMigrationName);
            if ($relation->relationType == 'belongsTo') {
                if (!$relativeMigrationFile)
                    $this->comment("\n \n Can't Create Migration Because, Related Module Migration File Dosen't Exist. You Must Build Related Module First. Relation(" . $relation->relationMethodName . ") \n");
            } else if ($relation->relationType == 'belongsToMany') {
                if ($relativeMigrationFile)
                    $relation->readyForMake = true;
            }
        }

        $this->finishProgressBar();

        return $relations;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function createProgressbar($params = [])
    {
        $command = $this->hasArgument('command') ? trim($this->argument('command')) : 'Command';
        $argument = $this->hasArgument('moduleName') ? trim($this->argument('moduleName')) : '';
        $defaultParams = ['message' => "Running " . $command . " {" . $argument . "}", 'time' => 500];
        $params = array_merge($defaultParams, $params);
        $params['time'] = 1000 * $params['time'];

        $this->progressBar = $this->output->createProgressBar(2);
        $this->info("\n \n " . $params['message'] . " \n ");
        $this->progressBar->advance(1);
        usleep($params['time']);
    }

    public function createRelationDatabases($relations)
    {
        $moduleName = trim($this->argument('moduleName'));

        $progressBar = ["message" => "Creating Relationship Databases For {" . $moduleName . "}"];
        $this->createProgressbar($progressBar);

        foreach ($relations as $relation) {
            if (!$relation->readyForMake || $relation->relationType != 'belongsToMany')
                continue;

            $this->createRelationshipMigration($relation);
        }

        $this->finishProgressBar();
    }

    public function createRelationshipMigration($relation)
    {
        sleep(1);
        $existedFile = $this->getMigrationFile($relation->tableName);

        if ($existedFile) {
            File::delete($existedFile->getPathname());
        }

        $this->laravel->view->addNamespace('betterfly', substr(__DIR__, 0, -8) . 'views');

        $migrationFile = $this->getFileNamePath(false, 'Migration', $relation->tableName);
        $data['relation'] = $relation;
        $output = $this->laravel->view->make('betterfly::generators.relations')->with($data)->render();

        if (!file_exists($migrationFile) && $fs = fopen($migrationFile, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }
        return false;
    }

    public function finishProgressBar()
    {
        $this->progressBar->finish();
        $this->info("\n \n succesfuly completed \n");
    }

    public function validateDirPath($dirPath)
    {
        if (strpos($dirPath, "/app/") !== 0) {
            exit($this->error("\n Dir should be in app, please start DirPath from: /app/*"));
        }
    }

    public function getRouteName(){

        $moduleName = trim($this->argument('moduleName'));

        $params['route_name'] = '';
        $cfg = $this->getConfigFile($moduleName, true, true);
        if (property_exists($cfg, 'parentModule'))
            $params['route_name'] .= $cfg->parentModule;

        $params['route_name'] .= $params['route_name'] == '' ? strtolower(str_plural($moduleName)) : '.'.strtolower(str_plural($moduleName));

        return $params['route_name'];
    }

    public function createFileWithData($filePath, $data)
    {
        if (!file_exists($filePath) && $fs = fopen($filePath, 'x')) {
            fwrite($fs, $data);
            fclose($fs);
            return true;
        }
    }
}
