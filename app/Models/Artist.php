<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Artist
 *
 * @property int $id
 * @property string $name
 * @property string $photo_path
 * @property int $likes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|Artist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist wherePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Artist whereUserId($value)
 * @mixin \Eloquent
 */
class Artist extends Model
{
    use HasFactory;
}
