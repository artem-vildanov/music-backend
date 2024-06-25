<?php

namespace App\DataAccessLayer\DbModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

/**
 * @property string id
 * @property string name
 * @property string photoPath
 * @property int likes
 * @property bool isFavourite
 * @property string|null publishTime
 * @property string artistId
 * @property string genre
 * @property string cdnFolderId
 */
class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        '_id',
        'id',
        'name',
        'photoPath',
        'likes',
        'isFavourite',
        'publishTime',
        'artistId',
        'genre',
        'cdnFolderId'
    ];
}
