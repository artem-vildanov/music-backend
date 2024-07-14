<?php

namespace App\DataAccessLayer\DbModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

/**
* @property string id
* @property string filePath
* @property string fileStatus
*/
class File extends Model
{
    use HasFactory;

    protected $fillable = [
        '_id',
        'id',
        'filePath',
        'fileStatus'
    ];
}
