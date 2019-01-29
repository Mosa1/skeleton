<?php

namespace BetterFly\Skeleton\Commands;

use Illuminate\Support\Facades\File;

class MakeMigrationCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:migration {moduleName : Module classname} {--dirPathType=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making Betterfly Migration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $moduleName = trim($this->argument('moduleName'));
        $relations = [];

        $dirPath = $this->getDirPath($moduleName, 'Migration');
        $this->validateDirPath($dirPath);

        $params = $this->getParams($moduleName);

        if ($params['config']->relations) {
            $relations = $this->checkRelationsDatabases($params['config']->relations);
        }

        $this->removeOldMigrationFile($moduleName);

        $this->createProgressbar();
        $createdFile = $this->createFile($moduleName, $dirPath, 'Migration', '', $params);

        if(!$createdFile)
            return $this->comment("\n \n Something Went Wrong \n \n");

        $this->finishProgressBar();

        if($relations)
            $this->createRelationDatabases($relations);
    }

    public function removeOldMigrationFile($moduleName)
    {
        $possibleFileName = 'create_' . strtolower(str_plural($moduleName)) . '_table.php';

        $migrationFile = $this->getMigrationFile($possibleFileName);

        if ($migrationFile) {
            $progessParams = ['message' => 'Removing migration file'];
            $this->createProgressbar($progessParams);

            if (!$this->confirm("\n I'm going to delete last migration file , Do you wish to continue ?",true))
                return exit($this->finishProgressBar());

            File::delete($migrationFile->getPathname());
            $this->finishProgressBar();
        }
    }

    private function getParams($moduleName)
    {
        $config = $this->getConfigFile($moduleName, true);

        $tableName = property_exists($config, 'tableName') ? $config->tableName : strtolower(str_plural($moduleName));
        $params['tableName'] = $tableName;
        $dbFields = [];
        $translatableDbFields = [];

        $config->translatable = property_exists($config, 'translatable') ? $config->translatable : false;
        foreach ($config->fields as $fieldName => $field) {
            if (property_exists($field, 'primaryKey') && $field->primaryKey) {
                continue;
            }

            $fieldConfig = '$table->' . $field->type . '(\'' . $fieldName . '\')';
            if (property_exists($field, 'unique') && $field->unique) {
                $fieldConfig .= '->unique()';
            }
            if (!property_exists($field, 'required') || !$field->required) {
                $fieldConfig .= '->nullable()';
            }

            if (property_exists($field, 'translatable') && $field->translatable) {
                $translatableDbFields[] = $fieldConfig . ';';
            } else {
                $dbFields[] = $fieldConfig . ';';
            }
        }

        $params['dbFields'] = $dbFields;
        $params['translatableDbFields'] = $translatableDbFields;
        $params['config'] = $config;

        return $params;
    }
}
