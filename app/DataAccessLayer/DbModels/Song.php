<?php

namespace App\DataAccessLayer\DbModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

/**
 * @property string id
 * @property string name
 * @property int likes
 * @property string photoPath
 * @property string audioId
 * @property string albumId
 * @property string artistId
 */
class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        '_id',
        'id',
        'name',
        'likes',
        'photoPath',
        'audioId',
        'albumId',
        'artistId'
    ];
}
