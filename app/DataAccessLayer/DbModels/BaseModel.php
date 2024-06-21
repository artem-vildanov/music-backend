<?php

namespace App\DataAccessLayer\DbModels;

use Exception;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    static public function getModelName(): string
    {
        throw new Exception('not implemented');
    }
}
