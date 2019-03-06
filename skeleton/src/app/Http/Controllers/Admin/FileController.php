<?php

namespace BetterFly\Skeleton\App\Http\Controllers\Admin;

use BetterFly\Skeleton\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Image;

class FileController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uploadedFiles = ['paths' => []];

        foreach ($request->file() as $inputName => $files) {
            $filesArray = !is_array($files) ? [$files] : $files;
            $imageCfg = $request->input($inputName . '_cfg');

            if (!$imageCfg)
                return response(['success' => false, 'message' => 'Image Config Not Found']);

            $imageCfg = json_decode($imageCfg);

            foreach ($filesArray as $file) {
                $response = $this->uploadFile($file, $imageCfg);
                if ($response['success']) {
                    $uploadedFiles['paths'][] = $response['filePath'];
                } else {
                    if (!empty($uploadedFiles['paths']))
                        File::delete($uploadedFiles['paths']);
                    return response(['success' => false, 'message' => $response['message']]);
                }
            }
        }

        return response(['files' => $uploadedFiles['paths'], 'success' => true]);

    }

    public function uploadFile($file, $cfg = false)
    {
        if ($cfg && property_exists($cfg, 'mimeTypes')) {
            $mimesValidation = $this->validateMimes($file, $cfg->mimeTypes);
            if (!$mimesValidation['success'])
                return ['success' => false, 'message' => $mimesValidation['message']];
        }

        $response = $this->createDirIfNeeded($cfg->folder);
        if (!$response['success'])
            return ['success' => false, 'message' => $response['message']];
        $fileDir = $response['dir'];
        $fileName = $this->getFilePath($file, $fileDir);

        try {
            $file->move($fileDir, $fileName);
            $filePath = $fileDir . '/' . $fileName;
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }


        if ($cfg && $cfg->thumbs) {
            if ($this->isImage($file))
                $this->createThumbs($filePath, $fileDir, $fileName, $cfg->thumbs);
        }

        return ['success' => true, 'filePath' => $filePath];
    }

    public function validateMimes($file, $mimes)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $mimes))
            return ['success' => false, 'message' => 'This file type is not allowed'];

        return ['success' => true];
    }

    public function isImage($file)
    {
        if (substr($file->getClientMimeType(), 0, 5) == 'image')
            return true;
        return false;
    }

    public function createThumbs($filePath, $fileDir, $fileName, $thumbs)
    {
        foreach ($thumbs as $thumb) {
            $img = Image::make($filePath)->resize($thumb->width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $path = !$thumb->prefix ? $fileDir . '/' . $fileName : $fileDir . '/' . $thumb->prefix . '_' . $fileName;
            $img->save($path);
        }
    }

    public function createDirIfNeeded($dirName)
    {
        $dir = base_path('storage/app/public/uploads/' . $dirName);

        if (!File::isDirectory(base_path("storage/app/public/uploads"))) {
            File::makeDirectory(base_path("storage/app/public/uploads"));
        }

        if (File::isDirectory($dir)) return ['success' => true, 'dir' => 'storage/uploads/' . $dirName, 'message' => 'existed'];

        try {
            File::makeDirectory($dir);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        return ['success' => true, 'dir' => 'storage/uploads/' . $dirName, 'message' => 'created'];
    }

    public function getFilePath($file, $dir)
    {
        $extension = strtolower($file->getClientOriginalExtension());

        $fileName = time() . '.' . $extension;

        if (file_exists($dir . '/' . $fileName))
            $fileName = time() + rand(0, 500) . '.' . $extension;

        return $fileName;
    }
}
