<?php

declare(strict_types=1);

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AlbumOwnership;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckAlbumIsFavourite;
use App\Http\Middleware\CheckAlbumStatus;
use App\Http\Middleware\CheckArtistExists;
use App\Http\Middleware\CheckArtistIsFavourite;
use App\Http\Middleware\CheckSongExists;
use App\Http\Middleware\CheckSongInAlbum;
use App\Http\Middleware\CheckSongIsFavourite;
use App\Http\Middleware\ForArtistPermitted;
use App\Http\Middleware\ForBaseUserPermitted;
use App\Http\Middleware\ArtistOwnership;
use App\Http\Middleware\PlaylistOwnership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'artists', 'middleware' => Authenticate::class], function () {
    Route::post('/create-artist', [ArtistController::class, 'create'])
        ->middleware(ForBaseUserPermitted::class);
    // TODO для тестирования фронта, убрать потом !!
    Route::get('all', [ArtistController::class, 'showAll']);
    Route::group(['prefix' => '{artistId}'], function() {
        Route::get('', [ArtistController::class, 'show']);
        Route::group(['middleware' => ArtistOwnership::class], function() {
            Route::post('update-artist-name', [ArtistController::class, 'updateName']);
            Route::post('update-artist-photo', [ArtistController::class, 'updatePhoto']);
            Route::delete('delete-artist', [ArtistController::class, 'delete']);
        })->withoutMiddleware([CheckArtistExists::class]);;
    });
});

Route::group(['prefix' => 'albums', 'middleware' => Authenticate::class], function () {
    Route::get('created-by-artist/{artistId}', [ArtistController::class, 'showAlbumsMadeByArtist']);
    Route::post('/create-album', [AlbumController::class, 'create'])
        ->middleware(ForArtistPermitted::class);

    Route::group(['prefix' => '{albumId}', 'middleware' => [CheckAlbumStatus::class]], function () {
        Route::get('', [AlbumController::class, 'show']);
        Route::group(['middleware' => [AlbumOwnership::class]], function() {
            Route::delete('delete-album', [AlbumController::class, 'delete']);
            Route::post('update-album-name', [AlbumController::class, 'updateName']);
            Route::post('update-album-genre', [AlbumController::class, 'updateGenre']);
            Route::post('update-album-publish-time', [AlbumController::class, 'updatePublishTime']);
            Route::post('update-album-photo', [AlbumController::class, 'updatePhoto']);
        })->withoutMiddleware(CheckAlbumStatus::class);

        Route::group(['prefix' => 'songs'], function () {
            Route::post('create-song', [SongController::class, 'create'])->middleware([AlbumOwnership::class]);
            Route::get('album-songs', [AlbumController::class, 'showSongsInAlbum']);
            Route::group(['prefix' => '{songId}', 'middleware' => CheckSongInAlbum::class], function () {
                Route::get('', [SongController::class, 'show'])->withoutMiddleware(CheckSongInAlbum::class);

                Route::group(['middleware' => [AlbumOwnership::class]], function() {
                    Route::post('/update-song-name', [SongController::class, 'updateName']);
                    Route::post('/update-song-audio', [SongController::class, 'updateAudio']);
                    Route::delete('/delete-song', [SongController::class, 'delete']);
                })->withoutMiddleware(CheckAlbumStatus::class);;
            });
        });
    });
});

Route::group(['prefix' => 'favourite', 'middleware' => Authenticate::class], function () {

    Route::group(['prefix' => 'albums'], function () {
        Route::get('', [UserController::class, 'showFavouriteAlbums']);
        Route::put('add-to-favourites/{albumId}', [UserController::class, 'addAlbumToFavourites'])
            ->middleware([CheckAlbumIsFavourite::class]);
        Route::put('delete-from-favourites/{albumId}', [UserController::class, 'removeAlbumFromFavourites']);
    });

    // TODO могу добавить трек из альбома, который еще не выложен в публику... исправить !
    Route::group(['prefix' => 'songs'], function () {
        Route::get('', [UserController::class, 'showFavouriteSongs']);
        Route::put('add-to-favourites/{songId}', [UserController::class, 'addSongToFavourites'])
            ->middleware([CheckSongIsFavourite::class]); // добавить проверку на публичность трека, запрос по albumId и проверить album status
        Route::put('delete-from-favourites/{songId}', [UserController::class, 'removeSongFromFavourites']);
    });

    Route::group(['prefix' => 'artists'], function () {
        Route::get('', [UserController::class, 'showFavouriteArtists']);
        Route::put('add-to-favourites/{artistId}', [UserController::class, 'addArtistToFavourites'])
            ->middleware([CheckArtistIsFavourite::class]);
        Route::put('delete-from-favourites/{artistId}', [UserController::class, 'removeArtistFromFavourites']);
    });
});

Route::group(['prefix' => 'playlists', 'middleware' => Authenticate::class], function () {
    Route::get('', [PlaylistController::class, 'showUserPlaylists']);
    Route::post('create-playlist', [PlaylistController::class, 'create']);
    Route::group(['prefix' => '{playlistId}', 'middleware' => PlaylistOwnership::class], function () {
        Route::get('', [PlaylistController::class, 'show']);
        Route::get('playlist-songs', [PlaylistController::class, 'showSongsInPlaylist']);
        Route::post('update-playlist-name', [PlaylistController::class, 'updateName']);
        Route::post('update-playlist-photo', [PlaylistController::class, 'updatePhoto']);
        Route::delete('delete-playlist', [PlaylistController::class, 'delete']);

        // TODO добавить проверку на публичность трека, запрос по albumId и проверить album status
        Route::put('add-song/{songId}', [PlaylistController::class, 'addSongToPlaylist'])
            ->middleware(CheckSongExists::class);
        Route::put('delete-song/{songId}', [PlaylistController::class, 'deleteSongsFromPlaylist']);
    });
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware(Authenticate::class);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [UserController::class, 'showUserInfo'])->middleware(Authenticate::class);
});
