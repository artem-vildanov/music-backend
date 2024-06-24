<?php

namespace App\DataAccessLayer\DbModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

/**
 * @property string _id
 * @property string name
 * @property string|null photoPath
 * @property string userId
 * @property string photoStatus
 * @property string[] songsIds
 */
class Playlist extends Model
{
    use HasFactory;
}
