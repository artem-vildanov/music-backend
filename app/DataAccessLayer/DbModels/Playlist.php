<?php

namespace App\DataAccessLayer\DbModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

/**
 * @property string id
 * @property string name
 * @property string|null photoPath
 * @property string userId
 * @property string photoStatus
 * @property string[] songsIds
 */
class Playlist extends Model
{
    use HasFactory;

    protected $fillable = [
        '_id',
        'id',
        'name',
        'photoPath',
        'userId',
        'photoStatus',
        'songsIds'
    ];
}
