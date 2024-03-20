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
use App\Http\Controllers\UserController;
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

    Route::group(['prefix' => '{artistId}'], function() {
        Route::get('', [ArtistController::class, 'show']);
        Route::put('add-to-favourites', [FavouriteArtistsController::class, 'addToFavouriteArtists'])->middleware(CheckArtistIsFavourite::class);
        Route::put('delete-from-favourites', [FavouriteArtistsController::class, 'deleteFromFavouriteArtists']);

        Route::group(['middleware' => ArtistOwnership::class], function() {
            Route::post('update-artist', [ArtistController::class, 'update']);
            Route::delete('delete-artist', [ArtistController::class, 'delete']);
        })->withoutMiddleware([CheckArtistExists::class]);;
    });
});

Route::group(['prefix' => 'albums', 'middleware' => Authenticate::class], function () {
    Route::get('created-by-artist/{artistId}', [ArtistController::class, 'showAlbumsMadeByArtist']);
    Route::post('/create-album', [AlbumController::class, 'create'])->middleware(ForArtistPermitted::class);

    Route::group(['prefix' => '{albumId}', 'middleware' => [CheckAlbumStatus::class]], function () {
        Route::get('', [AlbumController::class, 'show']);

        Route::put('add-to-favourites', [FavouriteAlbumsController::class, 'addToFavouriteAlbums'])->middleware(CheckAlbumIsFavourite::class);
        Route::put('delete-from-favourites', [FavouriteAlbumsController::class, 'deleteFromFavouriteAlbums']);

        Route::group(['middleware' => [AlbumOwnership::class]], function() {
            Route::delete('delete-album', [AlbumController::class, 'delete']);
            Route::post('update-album', [AlbumController::class, 'update']);
        })->withoutMiddleware(CheckAlbumStatus::class);

        Route::group(['prefix' => 'songs'], function () {
            Route::post('create-song', [SongController::class, 'create'])->middleware([ArtistOwnership::class]);
            Route::get('album-songs', [AlbumController::class, 'showSongsInAlbum']);
            Route::group(['prefix' => '{songId}', 'middleware' => CheckSongExists::class], function () {
                Route::get('', [SongController::class, 'show'])->withoutMiddleware(CheckSongExists::class);
                Route::put('add-to-favourites', [FavouriteSongsController::class, 'addToFavouriteSongs'])->middleware(CheckSongIsFavourite::class);
                Route::put('delete-from-favourites', [FavouriteSongsController::class, 'deleteFromFavouriteSongs']);

                Route::group(['middleware' => PlaylistOwnership::class], function () {
                    Route::put('add-to-playlist/{playlistId}', [PlaylistController::class, 'addSongToPlaylist'])->middleware(CheckSongInPlaylist::class);
                    Route::put('delete-from-playlist/{playlistId}', [PlaylistController::class, 'deleteSongsFromPlaylist']);
                })->withoutMiddleware(CheckSongExists::class);

                Route::group(['middleware' => [AlbumOwnership::class]], function() {
                    Route::post('/update-song', [SongController::class, 'update']);
                    Route::delete('/delete-song', [SongController::class, 'delete']);
                })->withoutMiddleware(CheckAlbumStatus::class);;
            });
        });
    });
});

Route::group(['prefix' => 'genre', 'middleware' => Authenticate::class], function() {
    Route::get('/all', [GenreController::class, 'showAll']);

    Route::group(['prefix' => '{genreId}', 'middleware' => CheckGenreExists::class], function() {
        Route::get('', [GenreController::class, 'show']);
        Route::put('add-to-favourites', [FavouriteGenresController::class, 'addToFavouriteGenres'])->middleware(CheckGenreIsFavourite::class);
        Route::put('delete-from-favourites', [FavouriteGenresController::class, 'deleteFromFavouriteGenres']);
        Route::get('albums-by-genre', [GenreController::class, 'albumsWithGenre']);
    });
});

Route::group(['prefix' => 'user', 'middleware' => Authenticate::class], function () {

    Route::get('/favourite-albums', [FavouriteAlbumsController::class, 'showFavouriteAlbums']);
    Route::get('/favourite-songs', [FavouriteSongsController::class, 'showFavouriteSongs']);
    Route::get('/favourite-artists', [FavouriteArtistsController::class, 'showFavouriteArtists']);
    Route::get('/favourite-genres', [FavouriteGenresController::class, 'showFavouriteGenres']);
    Route::group(['prefix' => 'playlists'], function () {
        Route::get('user-playlists', [PlaylistController::class, 'showUserPlaylists']);
        Route::post('create-playlist', [PlaylistController::class, 'create']);
        Route::group(['prefix' => '{playlistId}', 'middleware' => PlaylistOwnership::class], function () {
            Route::get('', [PlaylistController::class, 'show']);
            Route::get('playlist-songs', [PlaylistController::class, 'showSongsInPlaylist']);
            Route::post('update-playlist', [PlaylistController::class, 'update']);
            Route::delete('delete-playlist', [PlaylistController::class, 'delete']);
        });
    });

});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware(Authenticate::class);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me'])->middleware(Authenticate::class);
});
