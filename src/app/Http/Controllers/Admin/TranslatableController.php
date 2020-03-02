<?php

namespace BetterFly\Skeleton\App\Http\Controllers\Admin;

use BetterFly\Skeleton\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Facades\File;


class TranslatableController extends Controller
{
    protected $currnetLanguageFilePath;
    protected $defaultLanguage;
    protected $supportedLanguages;
    protected $arrayOfPatterns = ['/(?<=@lang\()(.*?)(?=\s*\))/','/(?<=$t\()(.*?)(?=\s*\))/'];


    public function __construct()
    {
        $this->supportedLanguages = config('translatable.locales');
        $this->currnetLanguageFilePath = $this->getLanguageFilePath();
        $this->reNewLanguageFiles();
    }


    public function store(Request $request)
    {
        $key = $request->input('key');
        $value = $request->input('value');
        $words = $this->getAlreadyTranslatedWords();
        $words[$key] = $value;
        if ($this->overrideTranslations($words, $this->getLanguageFilePath()))
            return response(['success' => true]);


        return response(['success' => false]);
    }

    public function index()
    {
        $words = $this->getAlreadyTranslatedWords();

        return view('betterfly::admin.common.texts')->with(["words" => $words]);
    }

    public function overrideTranslations($words, $path)
    {
        $words = json_encode($words);
        if (File::put($path, $words))
            return true;
        return false;
    }

    public function getTranslatableWords()
    {
        $words = [];
        $path = resource_path('views');
        $files = (New Finder())->files()->in($path)->contains('@lang(');

        foreach ($files as $file) {
            $fileContent = file_get_contents($file->getRealpath());
            foreach($this->arrayOfPatterns as $pattern){
                if (preg_match_all($pattern, $fileContent, $matches)) {
                    foreach ($matches[0] as $match) {
                        $match = str_replace("'", '', str_replace('"', '', $match));
                        $words[$match] = $match;
                    }
                }
            }
        }

        return array_unique($words);
    }

    public function getAlreadyTranslatedWords($lang = false)
    {
        $lang = $lang ?: App()->getLocale();
        $path = resource_path('lang/' . $lang . '.json');
        $data = json_decode(file_get_contents($path));

        return (array)$data;
    }


    public function getLanguageFilePath($lang = false)
    {
        $lang = $lang ?: App()->getLocale();
        return resource_path('lang/' . $lang . '.json');
    }

    public function reNewLanguageFiles()
    {

        $words = $this->getTranslatableWords();

        foreach ($this->supportedLanguages as $locale) {
            $alreadTranslated = $this->getAlreadyTranslatedWords($locale);
            $languageFilePath = $this->getLanguageFilePath($locale);
            $removedKeys = array_diff_key($alreadTranslated, $words);
            foreach ($words as $word) {
                if (!key_exists($word, $alreadTranslated))
                    $alreadTranslated[$word] = '';
            }

            foreach ($removedKeys as $key => $value) {
                unset($alreadTranslated[$key]);
            }

            try {
                $this->overrideTranslations($alreadTranslated, $languageFilePath);
            } catch (\Exception $e) {
                return response(['succes' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function autoTranslate()
    {
        $endPoint = "https://translate.yandex.net/api/v1.5/tr.json/translate";
        $words = $this->getAlreadyTranslatedWords();
        $client = new \GuzzleHttp\Client();
        $apiKey = config('skeleton.yandex_api_key');
        $filePath = $this->getLanguageFilePath();

        foreach ($words as $key => $word) {
        	if($word)
        		continue;
        	
            $response = $client->request('POST', $endPoint,
                [
                    'query' => [
                        "key" => $apiKey,
                        "text" => $key,
                        "lang" => App()->getLocale()
                    ],
                    "headers" => [
                        "Content-type" => "application/x-www-form-urlencoded"
                    ]

                ]);

            $response = json_decode($response->getBody());

            $words[$key] = $response->text[0];
        }

        if ($this->overrideTranslations($words, $filePath))
            return response(['success' => true]);
    }
}
