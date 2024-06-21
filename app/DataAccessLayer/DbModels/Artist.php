<?php

namespace App\DataAccessLayer\DbModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string _id
 * @property string name
 * @property string photoPath
 * @property int likes
 * @property string userId
 * @property string[] albumsIds
 */
class Artist extends BaseModel
{
    use HasFactory;

    public static function getModelName(): string
    {
        return 'artist';
    }
}
