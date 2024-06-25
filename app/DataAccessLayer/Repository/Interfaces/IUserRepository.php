<?php

namespace App\DataAccessLayer\Repository\Interfaces;

use App\DataAccessLayer\DbModels\User;
use App\DomainLayer\Enums\UserRoles;
use App\Exceptions\DataAccessExceptions\DataAccessException;

interface IUserRepository
{
    /**
     * @param string $userId
     * @return User
     * @throws DataAccessException
     */
    public function getById(string $userId): User;

    /**
     * @param string $email
     * @return User
     * @throws DataAccessException
     */
    public function getByEmail(string $email): User;


    /**
     * @param string $name
     * @param string $password
     * @param string $email
     * @param string $role
     * @return User
     * @throws DataAccessException
     */
    public function create(
        string $name,
        string $password,
        string $email,
        UserRoles $role
    ): User;

    /**
     * @param string $userId
     * @return void
     * @throws DataAccessException
     */
    public function delete(string $userId): void;

    /**
     * @param string $userId
     * @param string $name
     * @param string $email
     * @param UserRoles $role
     * @return void
     * @throws DataAccessException
     */
    public function update(string $userId, string $name, string $email, UserRoles $role): void;

    /**
     * check favourite
     */

    public function checkSongFavourite(string $userId, string $songId): bool;
    public function checkAlbumFavourite(string $userId, string $albumId): bool;
    public function checkArtistFavourite(string $userId, string $artistId): bool;

    /**
     * ADD TO FAVOURITES
     */

    public function addArtistToFavourites(string $userId, string $artistId): void;
    public function addAlbumToFavourites(string $userId, string $albumId): void;
    public function addSongToFavourites(string $userId, string $songId): void;

    /**
     * DELETE FROM FAVOURITES
     */

    public function removeArtistFromFavourites(string $userId, string $artistId): void;
    public function removeAlbumFromFavourites(string $userId, string $albumId): void;
    public function removeSongFromFavourites(string $userId, string $songId): void;

    /**
     * DELETE FROM ALL FAVOURITES
     */

    public function removeSongFromAllUsers(string $id): void;
    public function removeAlbumFromAllUsers(string $id): void;
    public function removeArtistFromAllUsers(string $id): void;

    /**
     * GET FAVOURITES
     */

    /**
    public function getFavouriteArtists(string $userId): array;
    public function getFavouriteSongs(string $userId): array;
    public function getFavouriteAlbums(string $userId): array;
    */
}
