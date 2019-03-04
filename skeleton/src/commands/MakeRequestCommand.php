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
     * Supported Validation Types.
     *
     * @var array
     */
    protected $columnTypes = [
//        'boolean',
//        'date',
//        'dateTime',
        'integer',
        'text' => ['string'],
        'image' => ['string'],
        'file' => ['jpeg', 'png', 'bmp', 'gif', 'svg'],
        'email',
        'string' => ['max:255'],
//        'time',
//        'timestamp',
//        'timestamps',
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
        $this->createProgressbar();

        $dirPath = $this->getDirPath($moduleName, 'Request');
        $this->validateDirPath($dirPath);

        $params = $this->getFields($moduleName, []);
        $nameSpace = 'App\\Modules\\' . $moduleName;

        $this->createFile($moduleName, $dirPath, 'Request', $nameSpace, $params);
        $this->finishProgressBar();
    }

    private function getFields($moduleName, $params)
    {
        $config = $this->getConfigFile($moduleName, true, false);

        $ruleStorefields = [];


        $ruleUpdatefields = [];

        foreach ($config->fields as $fieldName => $field) {

            if (property_exists($field, 'primaryKey')) {
                continue;
            }

            $validations = $this->getValidations($field);
            if (!$validations) {
                continue;
            }

//            if (property_exists($field, 'maxCount') && $field->maxCount > 1) {
//                $fieldRules = $this->getValidations($field,true);
//
//                $ruleStorefields[] = [
//                    "name" => $fieldName . '.*',
//                    "value" => $fieldRules
//                ];
//
//                $ruleUpdatefields[] = [
//                    "name" => $fieldName . '.*',
//                    "value" => $fieldRules
//                ];
//
//            }

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

    public function getValidations($field)
    {
        $validations = [];
//        $fieldValidation = [];

        if (property_exists($field, 'required') && $field->required) {
            $validations[] = 'required';
        } else {
            $validations[] = 'nullable';
        }


        if (property_exists($field, 'unique') && $field->unique) {
            $validations[] = 'unique';
        }

//        if (($field->type != 'image' && $field->type != 'file') || $additionalValidation || (property_exists($field, 'maxCount') && $field->maxCount < 2)) {
            $fieldValidation = $this->getValidationRulesByField($field);
//        }

        $validations = array_merge($validations, $fieldValidation);

        return implode("|", $validations);
    }

    private function getValidationRulesByField($field)
    {
        $validation = null;
        if (in_array($field->type, $this->columnTypes)) {
            $validation = [$field->type];
        }

        if (key_exists($field->type, $this->columnTypes)) {
            $validation = $this->columnTypes[$field->type];
//            if ($field->type == 'image' || $field->type == 'file') {
//                $mimes = property_exists($field, 'mimeTypes') ? $field->mimeTypes : $validation;
//                $validation = ['mimes:' . implode(',', $mimes)];
//
//            }
        }

        if ($validation === null)
            $validation = ['max:255'];

        return $validation;
    }
}
