<?php

namespace App\DataAccessLayer\DbModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string _id
 * @property string name
 * @property string photoPath
 * @property int likes
 * @property bool isFavourite
 * @property string|null publishTime
 * @property string[] songsIds
 * @property string artistId
 * @property string genre
 */
class Album extends BaseModel
{
    use HasFactory;

    public static function getModelName(): string
    {
        return 'album';
    }
}
