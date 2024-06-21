<?php

namespace App\Providers;

use App\DataAccessLayer\Repository\Implementations\FavouritesRepository;
use App\DataAccessLayer\Repository\Implementations\PlaylistSongsRepository;
use App\DataAccessLayer\Repository\Implementations\UserRepository;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\IFavouritesRepository;
use App\DataAccessLayer\Repository\Interfaces\IGenreRepository;
use App\DataAccessLayer\Repository\Interfaces\IPlaylistRepository;
use App\DataAccessLayer\Repository\Interfaces\IPlaylistSongsRepository;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\DataAccessLayer\Repository\Proxies\AlbumRepositoryProxy;
use App\DataAccessLayer\Repository\Proxies\ArtistRepositoryProxy;
use App\DataAccessLayer\Repository\Proxies\GenreRepositoryProxy;
use App\DataAccessLayer\Repository\Proxies\PlaylistRepositoryProxy;
use App\DataAccessLayer\Repository\Proxies\SongRepositoryProxy;
use Illuminate\Support\ServiceProvider;

// use App\Repository\Implementations\AlbumRepository;
// use App\Repository\Implementations\ArtistRepository;
// use App\Repository\Implementations\SongRepository;
// use App\Repository\Implementations\GenreRepository;
// use App\Repository\Implementations\PlaylistRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);

        $this->app->bind(IAlbumRepository::class, AlbumRepositoryProxy::class);
        $this->app->bind(IArtistRepository::class, ArtistRepositoryProxy::class);
        $this->app->bind(ISongRepository::class, SongRepositoryProxy::class);
        $this->app->bind(IGenreRepository::class, GenreRepositoryProxy::class);
        $this->app->bind(IPlaylistRepository::class, PlaylistRepositoryProxy::class);

        // $this->app->bind(IAlbumRepository::class, AlbumRepository::class);
        // $this->app->bind(IArtistRepository::class, ArtistRepository::class);
        // $this->app->bind(ISongRepository::class, SongRepository::class);
        // $this->app->bind(IGenreRepository::class, GenreRepository::class);
        // $this->app->bind(IPlaylistRepository::class, PlaylistRepository::class);

        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IPlaylistSongsRepository::class, PlaylistSongsRepository::class);
        $this->app->bind(IFavouritesRepository::class, FavouritesRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
