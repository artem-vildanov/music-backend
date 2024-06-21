<?php

namespace App\DataAccessLayer\DbModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string _id
 * @property string name
 * @property int likes
 */
class Genre extends BaseModel
{
    use HasFactory;
}
