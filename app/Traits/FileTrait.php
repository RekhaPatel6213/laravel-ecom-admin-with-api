<?php

namespace App\Traits;

trait FileTrait
{
    private function storeFile($fileName)
    {
        $request = request();
        $filePath = null;
        if ($request->hasfile($fileName)) {
            $file = $request->file($fileName);
            $newFileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('public', $newFileName);
            $filePath = str_replace('public/', '', $filePath);
        } /*else if(!$request->hasfile($fileName)){
            $filePath =  $request->$fileName;
        }*/

        return $filePath;
    }

    private function getFileImage(?string $image = null, ?string $editImage = null)
    {
        if ($image !== null) {
            $image = explode('storage/', $image);

            return $image = $image[1];
        }

        return $editImage ?? null;
    }
}
