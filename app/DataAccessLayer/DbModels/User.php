<?php

namespace App\DataAccessLayer\DbModels;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string id
 * @property string name
 * @property string email
 * @property string password
 * @property string[] favouriteArtistsIds
 * @property string[] favouriteAlbumsIds
 * @property string[] favouriteSongsIds
 * @property string role
 */
class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        '_id',
        'id',
        'name',
        'email',
        'password',
        'favouriteArtistsIds',
        'favouriteAlbumsIds',
        'favouriteSongsIds',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
