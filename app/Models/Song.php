<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Song
 *
 * @property int $id
 * @property string $name
 * @property int $likes
 * @property string $photo_path
 * @property string $music_path
 * @property int $album_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Song newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Song newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Song query()
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereMusicPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song wherePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereUpdatedAt($value)
 * @property int $artist_id
 * @method static \Illuminate\Database\Eloquent\Builder|Song whereArtistId($value)
 * @mixin \Eloquent
 */
class Song extends Model
{
    use HasFactory;
}
