<?php

namespace App\DataAccessLayer\DbModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string _id
 * @property string name
 * @property string|null photoPath
 * @property string userId
 * @property string[] songsIds
 */
class Playlist extends BaseModel
{
    use HasFactory;

    public static function getModelName(): string
    {
        return 'playlist';
    }
}
