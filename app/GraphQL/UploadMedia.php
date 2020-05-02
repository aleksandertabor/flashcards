<?php

namespace App\GraphQL;

use Exception;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\UnreachableUrl;

class UploadMedia
{
    public static function uploadImageFromFile($image, $validationField, $model, $collection)
    {
        try {
            $model->addMedia($image)->toMediaCollection($collection);
        } catch (Exception $e) {
            $error = ValidationException::withMessages([
                $validationField => ['Try add an other image.'],
             ]);
            throw $error;
        }
    }

    public static function uploadImageFromUrl($image, $validationField, $model, $collection)
    {
        try {
            $url = '';
            $media = $model->getFirstMedia($collection);
            if ($media) {
                $url = $media->getFullUrl();
            }
            if ($image !== $url) {
                $model->addMediaFromUrl($image)->toMediaCollection($collection);
            }
        } catch (Exception $e) {
            if ($e instanceof FileIsTooBig || $e instanceof UnreachableUrl) {
                $error = ValidationException::withMessages([
                    $validationField => [preg_replace("/\`[^)]+\`/", '', $e->getMessage())],
             ]);
                throw $error;
            }
            $error = ValidationException::withMessages([
                $validationField => ['Try add an other image. Supported image formats: jpeg, webp, png.'],
             ]);
            throw $error;
        }
    }

    public static function uploadImageFromBase64($image, $validationField, $model, $collection)
    {
        try {
            $model->addMediaFromBase64($image)->toMediaCollection($collection);
        } catch (Exception $e) {
            $error = ValidationException::withMessages([
                $validationField => ['Try add an other image.'],
                 ]);
            throw $error;
        }
    }
}
