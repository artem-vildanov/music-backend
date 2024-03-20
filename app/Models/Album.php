<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Album
 *
 * @property int $id
 * @property string $name
 * @property string $photo_path
 * @property int $likes
 * @property int $artist_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status
 * @property string $cdn_folder_id
 * @method static \Illuminate\Database\Eloquent\Builder|Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Album query()
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereCdnFolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album wherePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereUpdatedAt($value)
 * @property int $genre_id
 * @method static \Illuminate\Database\Eloquent\Builder|Album whereGenreId($value)
 * @mixin \Eloquent
 */
class Album extends Model
{
    use HasFactory;
}
