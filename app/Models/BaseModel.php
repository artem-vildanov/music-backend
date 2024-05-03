<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model 
{   
    static public function getModelName(): string
    {
        throw new Exception('not implemented');
    }
}