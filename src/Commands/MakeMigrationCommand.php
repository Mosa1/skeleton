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
     * Supported Column Types.
     *
     * @var array
     */
    protected $columnTypes = [
        'bigIncrements',
        'bigInteger',
        'binary',
        'boolean',
        'char',
        'date',
        'datetime',
        'decimal',
        'double',
        'enum',
        'float',
        'increments',
        'integer',
        'longText',
        'mediumInteger',
        'mediumText',
        'morphs',
        'nullableTimestamps',
        'smallInteger',
        'tinyInteger',
        'softDeletes',
        'string',
        'text',
        'time',
        'timestamp',
        'timestamps',
        'rememberToken',
        'unsigned',
    ];

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

        if ($this->removeOldMigrationFile($moduleName)) {
            $this->createProgressbar();
            $createdFile = $this->createFile($moduleName, $dirPath, 'Migration', '', $params);

            if (!$createdFile)
                return $this->comment("\n \n Something Went Wrong \n \n");

            if ($relations)
                $this->createRelationDatabases($relations);

            $this->finishProgressBar();
        }

    }

    public function removeOldMigrationFile($moduleName)
    {
        $possibleFileName = 'create_' . strtolower($moduleName) . '_table.php';

        $migrationFile = $this->getMigrationFile($possibleFileName);

        if ($migrationFile) {
            $progessParams = ['message' => 'Removing migration file'];
            $this->createProgressbar($progessParams);

            if (!$this->confirm("\n I'm going to delete last migration file , Do you wish to continue ?", true)) {
                $this->finishProgressBar();
                return false;
            }

            File::delete($migrationFile->getPathname());
            $this->finishProgressBar();
            return true;
        } else {
            return true;
        }
    }

    private function getParams($moduleName)
    {
        $config = $this->getConfigFile($moduleName, true);

        $params['tableName'] = $config->tableName;
        $dbFields = [];
        $translatableDbFields = [];
        foreach ($config->fields as $fieldName => $field) {
            if (property_exists($field, 'primaryKey') && $field->primaryKey || property_exists($field,'relationType')) {
                continue;
            }

            $field->type = $this->checkAndGetColumnType($field->type);

            $fieldConfig = '$table->' . $field->type . '(\'' . $fieldName . '\')';
            if (property_exists($field, 'unique') && $field->unique) {
                $fieldConfig .= '->unique()';
            }
            if (!property_exists($field, 'required') || !$field->required) {
                $fieldConfig .= '->nullable()';
            }

            if (property_exists($field, 'translatable') && $field->translatable && $config->translatable) {
                $translatableDbFields[] = $fieldConfig . ';';
            } else {
                $dbFields[] = $fieldConfig . ';';
            }
        }

        if (property_exists($config, 'setVisibility') && $config->setVisibility) {
            if ($config->translatable)
                $translatableDbFields[] = '$table->tinyInteger("visibility")->default(1);';
            else
                $dbFields[] = '$table->tinyInteger("visibility")->default(1);';
        }

        if ($config->sortable) {
            $dbFields[] = '$table->nestedSet();';
        }


        $params['dbFields'] = $dbFields;
        $params['translatableDbFields'] = $translatableDbFields;
        $params['config'] = $config;

        return $params;
    }

    private function checkAndGetColumnType($type)
    {
        if (!in_array($type, $this->columnTypes))
            return 'string';
        return $type;
    }
}
