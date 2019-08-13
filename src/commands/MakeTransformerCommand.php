<?php

namespace BetterFly\Skeleton\Commands;

class MakeTransformerCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:transformer {moduleName : Module classname} {--dirPathType=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Making Betterfly Transformer';

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

        $dirPath = $this->getDirPath($moduleName, 'Transformer');
        $this->validateDirPath($dirPath);

        $params = $this->getFields($moduleName, []);
        $nameSpace = 'App\\Modules\\'.$moduleName;
        $this->createFile($moduleName, $dirPath, 'Transformer', $nameSpace, $params);

        $this->finishProgressBar();
    }

    private function getFields($moduleName, $params){
        $config = $this->getConfigFile($moduleName, true,false);

        $transformerFields = [];

        /*
         $data = [
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => $user->is_super,
            'token' => $user->createToken('MyApp')->accessToken
        ];
         * */

        foreach ($config->fields as $fieldName => $field){
            $transformerFields[$fieldName] = $fieldName;
        }

        $params['transformerFields'] = $transformerFields;

        return $params;
    }
}
