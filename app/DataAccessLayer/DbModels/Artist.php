<?php

namespace App\DataAccessLayer\DbModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

/**
 * @property string id
 * @property string name
 * @property string photoPath
 * @property int likes
 * @property string userId
 */
class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        '_id',
        'id',
        'name',
        'photoPath',
        'likes',
        'userId'
    ];
}
