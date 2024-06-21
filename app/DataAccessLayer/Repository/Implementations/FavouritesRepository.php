<?php

namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\DbModels\Artist;
use App\DataAccessLayer\DbModels\Genre;
use App\DataAccessLayer\DbModels\Song;
use App\DataAccessLayer\Repository\Interfaces\IFavouritesRepository;
use App\Exceptions\FavouritesExceptions\FavouriteAlbumsException;
use App\Exceptions\FavouritesExceptions\FavouriteArtistsException;
use App\Exceptions\FavouritesExceptions\FavouriteGenresException;
use App\Exceptions\FavouritesExceptions\FavouriteSongsException;
use Illuminate\Support\Facades\DB;

class FavouritesRepository implements IFavouritesRepository
{
    /**
     * Check is it in favourites
     */
    public function checkSongIsFavourite(int $userId, int $songId): bool
    {
        return (bool)DB::table('users_songs')
            ->where([
                'user_id' => $userId,
                'song_id' => $songId
            ])
            ->first();
    }

    public function checkAlbumIsFavourite(int $userId, int $albumId): bool
    {
        return (bool)DB::table('users_albums')
            ->where([
                'user_id' => $userId,
                'album_id' => $albumId
            ])
            ->first();
    }

    public function checkArtistIsFavourite(int $userId, int $artistId): bool
    {
        return (bool)DB::table('users_artists')
            ->where([
                'user_id' => $userId,
                'artist_id' => $artistId
            ])
            ->first();
    }

    /**
     * Add to favourites
     */

    public function addAlbumToFavourites(int $albumId, int $userId): void
    {
        $result = DB::table('users_albums')->insert([
            'user_id' => $userId,
            'album_id' => $albumId
        ]);

        if (!$result) {
            throw FavouriteAlbumsException::failedAddToFavourites($albumId);
        }
    }

    public function addSongToFavourites(int $songId, int $userId): void
    {
        $result = DB::table('users_songs')->insert([
            'user_id' => $userId,
            'song_id' => $songId
        ]);

        if (!$result) {
            throw FavouriteSongsException::failedAddToFavourites($songId);
        }
    }

    public function addArtistToFavourites(int $artistId, int $userId): void
    {
        $result = DB::table('users_artists')->insert([
            'user_id' => $userId,
            'artist_id' => $artistId
        ]);

        if (!$result) {
            throw FavouriteArtistsException::failedAddToFavourites($artistId);
        }
    }

    /**
     * Delete from favourites
     */

    public function deleteAlbumFromFavourites(int $albumId, int $userId): void
    {
        $result = DB::table('users_albums')->where([
            'user_id' => $userId,
            'album_id' => $albumId
        ])->delete();

        if (!$result) {
            throw FavouriteAlbumsException::failedDeleteFromFavourites($albumId);
        }
    }

    public function deleteSongFromFavourites(int $songId, int $userId): void
    {
        $result = DB::table('users_songs')->where([
            'user_id' => $userId,
            'song_id' => $songId
        ])->delete();

        if (!$result) {
            throw FavouriteSongsException::failedDeleteFromFavourites($songId);
        }
    }

    public function deleteArtistFromFavourites(int $artistId, int $userId): void
    {
        $result = DB::table('users_artists')->where([
            'user_id' => $userId,
            'artist_id' => $artistId
        ])->delete();

        if (!$result) {
            throw FavouriteArtistsException::failedDeleteFromFavourites($artistId);
        }
    }

    /**
     * Get favourites
     */

    public function getFavouriteAlbumsIds(int $userId): array
    {
        return DB::table('users_albums')
            ->where('user_id', $userId)
            ->pluck('album_id')
            ->toArray();
    }

    public function getFavouriteSongsIds(int $userId): array
    {
        return DB::table('users_songs')
            ->where('user_id', $userId)
            ->pluck('song_id')
            ->toArray();
    }

    public function getFavouriteArtistsIds(int $userId): array
    {
        return DB::table('users_artists')
            ->where('user_id', $userId)
            ->pluck('artist_id')->toArray();
    }

    /**
     * Increment likes
     */

    public function incrementAlbumLikes(int $albumId): void
    {
        $result = Album::query()->where('id', $albumId)->increment('likes');

        if (!$result) {
            throw FavouriteAlbumsException::failedIncrementLikes($albumId);
        }
    }

    public function incrementSongLikes(int $songId): void
    {
        $result = Song::query()->where('id', $songId)->increment('likes');

        if (!$result) {
            throw FavouriteSongsException::failedIncrementLikes($songId);
        }
    }

    public function incrementArtistLikes(int $artistId): void
    {
        $result = Artist::query()->where('id', $artistId)->increment('likes');

        if (!$result) {
            throw FavouriteArtistsException::failedIncrementLikes($artistId);
        }
    }

    /**
     * Decrement likes
     */

    public function decrementAlbumLikes(int $albumId): void
    {
        $result = Album::query()->where('id', $albumId)->decrement('likes');

        if (!$result) {
            throw FavouriteAlbumsException::failedDecrementLikes($albumId);
        }
    }

    public function decrementSongLikes(int $songId): void
    {
        $result = Song::query()->where('id', $songId)->decrement('likes');

        if (!$result) {
            throw FavouriteSongsException::failedDecrementLikes($songId);
        }
    }

    public function decrementArtistLikes(int $artistId): void
    {
        $result = Artist::query()->where('id', $artistId)->decrement('likes');

        if (!$result) {
            throw FavouriteArtistsException::failedDecrementLikes($artistId);
        }
    }
}
