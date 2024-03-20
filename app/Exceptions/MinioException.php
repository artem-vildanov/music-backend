<?php

namespace App\Exceptions;

class MinioException extends \Exception
{
    public static function failedToUpdatePhoto(): MinioException
    {
        return new self('failed to update photo', 500);
    }

    public static function failedToSavePhoto(): MinioException
    {
        return new self('failed to upload photo', 500);
    }

    public static function failedToDeletePhoto(): MinioException
    {
        return new self('failed to delete photo', 500);
    }

    public static function failedToUpdateAudio(): MinioException
    {
        return new self('failed to update audio', 500);
    }

    public static function failedToSaveAudio(): MinioException
    {
        return new self('failed to upload audio', 500);
    }

    public static function failedToDeleteAudio(): MinioException
    {
        return new self('failed to delete audio', 500);
    }
}
