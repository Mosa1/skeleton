<?php

namespace BetterFly\Skeleton\Commands;

class MakeRequestCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:request {moduleName : Module classname} {--dirPathType=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making Betterfly Request';

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
        $this->createProgressbar();

        $dirPath = $this->getDirPath($moduleName, 'Request');
        $this->validateDirPath($dirPath);

        $params = $this->getFields($moduleName, []);
        $nameSpace = 'App\\Modules\\'.$moduleName;

        $this->createFile($moduleName, $dirPath, 'Request', $nameSpace, $params);
        $this->finishProgressBar();
    }

    private function getFields($moduleName, $params){
        $config = $this->getConfigFile($moduleName, true,false);

        $ruleStorefields = [];


        $ruleUpdatefields = [];

        foreach ($config->fields as $fieldName => $field){

            if(property_exists($field, 'primaryKey')){
                continue;
            }

            $validations = $this->getValidations($field);
            if(!$validations){
                continue;
            }


            $ruleStorefields[] = [
                "name" => $fieldName,
                "value" => $validations
            ];

            $ruleUpdatefields[] = [
                "name" => $fieldName,
                "value" => $validations
            ];
        }

        $params['ruleStorefields'] = $ruleStorefields;
        $params['ruleUpdatefields'] = $ruleUpdatefields;

        return $params;
    }

    public function getValidations($field){
        $validations = [];

        if(!property_exists($field, 'required') || !$field->required){
            return null;
        }

        $validations[] = 'required';

        if(property_exists($field, 'unique') && $field->unique){
            $validations[] = 'unique';
        }

        $validations[] = $field->type;

        return implode("|", $validations);
    }
}
