<?php

namespace App\Repository\Interfaces;

use App\Exceptions\FavouritesExceptions\FavouritesException;

interface IFavouritesRepository
{
    /**
     * Check is it in favourites
     */

    public function checkSongIsFavourite(int $userId, int $songId): bool;
    public function checkAlbumIsFavourite(int $userId, int $albumId): bool;
    public function checkArtistIsFavourite(int $userId, int $artistId): bool;
    public function checkGenreIsFavourite(int $userId, int $genreId): bool;

    /**
     * Get favourites
     */

    /**
     * @param int $userId
     * @return int[]
     */
    public function getFavouriteAlbumsIds(int $userId): array;

    /**
     * @param int $userId
     * @return int[]
     */
    public function getFavouriteSongsIds(int $userId): array;

    /**
     * @param int $userId
     * @return int[]
     */
    public function getFavouriteGenresIds(int $userId): array;

    /**
     * @param int $userId
     * @return int[]
     */
    public function getFavouriteArtistsIds(int $userId): array;

    /**
     * Add to favourites
     */

    /**
     * @param int $albumId
     * @param int $userId
     * @throws FavouritesException
     * @return void
     */
    public function addAlbumToFavourites(int $albumId, int $userId): void;

    /**
     * @param int $songId
     * @param int $userId
     * @throws FavouritesException
     * @return void
     */
    public function addSongToFavourites(int $songId, int $userId): void;

    /**
     * @param int $genreId
     * @param int $userId
     * @throws FavouritesException
     * @return void
     */
    public function addGenreToFavourites(int $genreId, int $userId): void;

    /**
     * @param int $artistId
     * @param int $userId
     * @throws FavouritesException
     * @return void
     */
    public function addArtistToFavourites(int $artistId, int $userId): void;

    /**
     * Delete from favourites
     */

    /**
     * @param int $albumId
     * @param int $userId
     * @throws FavouritesException
     * @return void
     */
    public function deleteAlbumFromFavourites(int $albumId, int $userId): void;

    /**
     * @param int $songId
     * @param int $userId
     * @throws FavouritesException
     * @return void
     */
    public function deleteSongFromFavourites(int $songId, int $userId): void;

    /**
     * @param int $genreId
     * @param int $userId
     * @throws FavouritesException
     * @return void
     */
    public function deleteGenreFromFavourites(int $genreId, int $userId): void;

    /**
     * @param int $artistId
     * @param int $userId
     * @throws FavouritesException
     * @return void
     */
    public function deleteArtistFromFavourites(int $artistId, int $userId): void;

    /**
     * Increment likes
     */

    /**
     * @param int $albumId
     * @throws FavouritesException
     * @return void
     */
    public function incrementAlbumLikes(int $albumId): void;

    /**
     * @param int $songId
     * @throws FavouritesException
     * @return void
     */
    public function incrementSongLikes(int $songId): void;

    /**
     * @param int $genreId
     * @throws FavouritesException
     * @return void
     */
    public function incrementGenreLikes(int $genreId): void;

    /**
     * @param int $artistId
     * @throws FavouritesException
     * @return void
     */
    public function incrementArtistLikes(int $artistId): void;

    /**
     * Decrement likes
     */

    /**
     * @param int $albumId
     * @throws FavouritesException
     * @return void
     */
    public function decrementAlbumLikes(int $albumId): void;

    /**
     * @param int $songId
     * @throws FavouritesException
     * @return void
     */
    public function decrementSongLikes(int $songId): void;

    /**
     * @param int $genreId
     * @throws FavouritesException
     * @return void
     */
    public function decrementGenreLikes(int $genreId): void;

    /**
     * @param int $artistId
     * @throws FavouritesException
     * @return void
     */
    public function decrementArtistLikes(int $artistId): void;
}
