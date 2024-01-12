<?php

namespace Uasoft\Badaso\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HandleFile
{
    public static function handle($dto)
    {
        $objects = [];
        foreach ($dto as $key => $value) {
            if (is_array($value)) {
                $objects[$key] = self::handle($value);
            } else {
                $objects[$key] = self::handleUrl($value);
                //if($key == "send") dump($key,$value);
            }
        }

        return $objects;
    }

    protected static function handleUrl($val)
    {
        if (stristr($val, 'http://') ?: stristr($val, 'https://')) {
            return $val;
        }

        if (preg_match('/^.*\.(jpg|jpeg|gif|svg|ico|tif|tiff|webp|heif|png|bmp)$/i', $val)) {
            // File Manager solusi file manager yang /storage/storage error link
            return str_replace("/storage/storage","/storage",Storage::url($val));//Storage::url($val);
        }

        if (Str::contains($val, config('lfm.folder_categories.file.folder_name').'/')) {
            return '111111'; str_replace(
                config('lfm.folder_categories.file.folder_name').'/',
                Storage::url('/').config('lfm.folder_categories.file.folder_name').'/',
                $val
            );
        }

        if (Str::contains($val, config('lfm.folder_categories.image.folder_name').'/')) {
            return str_replace(
                config('lfm.folder_categories.image.folder_name').'/',
                Storage::url('/').config('lfm.folder_categories.image.folder_name').'/',
                $val
            );
        }

        //dd('handleUrl',$val);

        return $val;
    }

    public static function normalize($val)
    {
        $objects = [];
        foreach ($val as $key => $value) {
            if (is_array($value)) {
                $objects[$key] = self::normalize($value);
            } else {
                $objects[$key] = self::removeBaseUrl($value);
            }
        }

        return $objects;
    }

    protected static function removeBaseUrl($val)
    {
        if (Str::contains($val, Storage::url('/'))) {
            return Str::remove(Storage::url('/'), $val);
        }

        return $val;
    }
}
