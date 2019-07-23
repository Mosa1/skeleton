<?php

namespace BetterFly\Skeleton\Commands;

use Illuminate\Support\Facades\File;

class MakeViewCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'betterfly:view {moduleName : Module classname} {--file=*} {--dirPathType=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Views For Admin';

    /**
     * Supported file's type.
     *
     */
    protected $supportedFilesTypes = ['index', 'create', 'edit'];

    /**
     *  Names.
     *
     */
    protected $routeTypes = ['create' => ['type' => 'store'], 'edit' => ['type' => 'update'], 'index' => ['type' => 'index']];

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
        $this->moduleName = trim($this->argument('moduleName'));
        $config = $this->getMergedConfig();

        $files = $this->checkFileTypes($this->option('file'), $config);
        $files = in_array('all', $files) ? $this->supportedFilesTypes : $files;

        $this->makeViews($config, $files);

    }

    public function getMergedConfig()
    {
        $defaultCfg = [
            'editModeOnly' => false,
            'excelExport' => false,
        ];

        $cfg = $this->getConfigFile($this->moduleName, true, true);

        $cfg = (object)array_merge($defaultCfg, (array)$cfg);

        return $cfg;
    }

    public function makeViews($config, $files)
    {
        $this->requestNameSpace = '\\App\\Modules\\' . $this->moduleName . '\\' . $this->moduleName . 'Request';
        $this->laravel->view->addNamespace('betterfly', substr(__DIR__, 0, -8) . 'views');
        $this->createProgressbar();

        foreach ($files as $file) {
            $pluginsBlade = false;
            $viewNameSpace = $this->getViewNameSpace($file, $config);
            if (!$viewNameSpace) continue;

            $routeType = $this->routeTypes[$file]['type'];
            $moduleRoute = $this->getRouteName();

            $baseData = ['cfg' => $config, 'requestNameSpace' => $this->requestNameSpace, 'moduleName' => $this->moduleName, 'moduleRoute' => $moduleRoute, 'routeType' => $routeType];

            $baseBlade = $this->laravel->view->make($viewNameSpace)->with($baseData)->render();

            if ($file != 'index') {
                $pluginsBlade = $this->collectPlugins($config->fields, $file);
            }

            $baseBlade = $this->replaceStrs($baseBlade, $pluginsBlade);

            $dirPath = base_path('resources/views/admin') . '/' . strtolower($this->moduleName);
            $this->createDirIfNeeded($dirPath);

            $filePath = $dirPath . '/' . $file . '.blade.php';
            if (file_exists($filePath)) {
                if (!$this->confirm("\n " . $filePath . " File Exists, Do you want to overwrite ?", false))
                    continue;
                else
                    File::delete($filePath);
            }

            $this->createFileWithData($filePath, $baseBlade);
        }

        $this->finishProgressBar();
    }

    public function replaceStrs($str, $pluginsBlade = false)
    {
        if ($pluginsBlade) $str = str_replace('{plugins}', $pluginsBlade, $str);
        $str = str_replace('print_start_allow_chars', '{!!', $str);
        $str = str_replace('print_end_allow_chars', '!!}', $str);
        $str = str_replace('print_start', '{{', $str);
        $str = str_replace('print_end', '}}', $str);
        $str = str_replace('at_symbol', '@', $str);

        return $str;
    }

    public function checkFileTypes($files, $cfg)
    {
        $files = $cfg->editModeOnly ? ['edit'] : (empty($files) ? ['all'] : $files);

        foreach ($files as $key => $fileType) {
            if ($fileType != 'all' && !in_array($fileType, $this->supportedFilesTypes)) unset($files[$key]);
        }

        return $files;
    }

    public function getViewNameSpace($file, $config)
    {
        if ($file == 'index') {
            if (!property_exists($config, 'indexPlugin') || !property_exists($config->indexPlugin[0], 'pluginName'))
                return false;
            return 'betterfly::plugins.' . $config->indexPlugin[0]->pluginName . '.tpl';
        }

        return 'betterfly::plugins.inner_base';

    }

    public function collectPlugins($fields, $mode)
    {
        $pluginsBlade = '';
        $pluginIncrement = 0;

        foreach ($fields as $fieldName => $properties) {
            if (!property_exists($properties, 'pluginName')) continue;

            if ($properties->pluginName == 'multiField') {
                require_once('../views/plugins/multiField/MultiField.php');
                $pluginTpl = New MultiField($properties);
                dd($pluginTpl);
            }

            $fieldData = ['properties' => $properties, 'fieldName' => $fieldName, 'mode' => $mode, 'plugin_id' => $properties->pluginName . '_' . $pluginIncrement];

            $pluginBlade = $this->laravel->view->make('betterfly::plugins.' . $properties->pluginName . '.tpl')->with($fieldData)->render();

            $pluginsBlade .= $pluginBlade;
            $pluginIncrement++;
        }

        return $pluginsBlade;
    }
}
