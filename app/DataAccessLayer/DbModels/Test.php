<?php

namespace App\DataAccessLayer\DbModels;

use MongoDB\Laravel\Eloquent\Model;

class Test extends Model
{
    protected $collection = 'tests';
}
