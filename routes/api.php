<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavouriteAlbumsController;
use App\Http\Controllers\FavouriteArtistsController;
use App\Http\Controllers\FavouriteGenresController;
use App\Http\Controllers\FavouriteSongsController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\AlbumOwnership;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckAlbumExists;
use App\Http\Middleware\CheckAlbumIsFavourite;
use App\Http\Middleware\CheckAlbumStatus;
use App\Http\Middleware\CheckArtistExists;
use App\Http\Middleware\CheckArtistIsFavourite;
use App\Http\Middleware\CheckEmailExists;
use App\Http\Middleware\CheckGenreExists;
use App\Http\Middleware\CheckGenreIsFavourite;
use App\Http\Middleware\CheckSongExists;
use App\Http\Middleware\CheckSongInPlaylist;
use App\Http\Middleware\CheckSongIsFavourite;
use App\Http\Middleware\ForArtistPermitted;
use App\Http\Middleware\ForBaseUserPermitted;
use App\Http\Middleware\ArtistOwnership;
use App\Http\Middleware\ExceptionHandler;
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
    Route::post('/create-artist', [ArtistController::class, 'create'])->middleware(ForBaseUserPermitted::class);
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
    Route::post('/create-album', [AlbumController::class, 'create'])->middleware(ForArtistPermitted::class);

    Route::group(['prefix' => '{albumId}', 'middleware' => [CheckAlbumStatus::class]], function () {
        Route::get('', [AlbumController::class, 'show']);
        Route::group(['middleware' => [AlbumOwnership::class]], function() {
            Route::delete('delete-album', [AlbumController::class, 'delete']);
            Route::post('update-album-name-genre', [AlbumController::class, 'updateNameAndGenre']);
            Route::post('update-album-publish-time', [AlbumController::class, 'updatePublishTime']);
            Route::post('update-album-photo', [AlbumController::class, 'updatePhoto']);
        })->withoutMiddleware(CheckAlbumStatus::class);

        Route::group(['prefix' => 'songs'], function () {
            Route::post('create-song', [SongController::class, 'create'])->middleware([AlbumOwnership::class]);
            Route::get('album-songs', [AlbumController::class, 'showSongsInAlbum']);
            Route::group(['prefix' => '{songId}', 'middleware' => CheckSongExists::class], function () {
                Route::get('', [SongController::class, 'show'])->withoutMiddleware(CheckSongExists::class);

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
        Route::get('', [FavouriteAlbumsController::class, 'showFavouriteAlbums']);
        Route::put('add-to-favourites/{albumId}', [FavouriteAlbumsController::class, 'addToFavouriteAlbums'])
            ->middleware([CheckAlbumIsFavourite::class]);
        Route::put('delete-from-favourites/{albumId}', [FavouriteAlbumsController::class, 'deleteFromFavouriteAlbums']);
    });

    // TODO могу добавить трек из альбома, который еще не выложен в публику... исправить !
    Route::group(['prefix' => 'songs'], function () {
        Route::get('', [FavouriteSongsController::class, 'showFavouriteSongs']);
        Route::put('add-to-favourites/{songId}', [FavouriteSongsController::class, 'addToFavouriteSongs'])
            ->middleware([CheckSongIsFavourite::class]); // добавить проверку на публичность трека, запрос по albumId и проверить album status
        Route::put('delete-from-favourites/{songId}', [FavouriteSongsController::class, 'deleteFromFavouriteSongs']);
    });

    Route::group(['prefix' => 'artists'], function () {
        Route::get('', [FavouriteArtistsController::class, 'showFavouriteArtists']);
        Route::put('add-to-favourites/{artistId}', [FavouriteArtistsController::class, 'addToFavouriteArtists'])
            ->middleware([CheckArtistIsFavourite::class]);
        Route::put('delete-from-favourites/{artistId}', [FavouriteArtistsController::class, 'deleteFromFavouriteArtists']);
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
            ->middleware(CheckSongInPlaylist::class);// этот мидлвейр и так проверяет существование трека, нет смысла в CheckSongExists
        Route::put('delete-song/{songId}', [PlaylistController::class, 'deleteSongsFromPlaylist']);
    });
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware(Authenticate::class);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me'])->middleware(Authenticate::class);
});
