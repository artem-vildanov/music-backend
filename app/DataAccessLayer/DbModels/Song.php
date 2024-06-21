<?php

namespace App\DataAccessLayer\DbModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string _id
 * @property string name
 * @property int likes
 * @property string photoPath
 * @property string musicPath
 * @property string albumId
 * @property string artistId
 */
class Song extends BaseModel
{
    use HasFactory;
}
