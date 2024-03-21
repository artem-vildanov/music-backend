<?php

namespace App\Providers;

use App\Repository\Proxies\AlbumRepositoryProxy;
use App\Repository\Proxies\ArtistRepositoryProxy;
use App\Repository\Proxies\SongRepositoryProxy;
use App\Repository\Proxies\GenreRepositoryProxy;
use App\Repository\Proxies\PlaylistRepositoryProxy;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\IArtistRepository;
use App\Repository\Interfaces\IFavouritesRepository;
use App\Repository\Interfaces\IGenreRepository;
use App\Repository\Interfaces\IPlaylistRepository;
use App\Repository\Interfaces\IPlaylistSongsRepository;
use App\Repository\Interfaces\ISongRepository;
use App\Repository\Interfaces\IUserRepository;
use App\Repository\Implementations\PlaylistSongsRepository;
use App\Repository\Implementations\FavouritesRepository;
use App\Repository\Implementations\UserRepository;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(ISongRepository::class, SongRepositoryProxy::class);
        $this->app->bind(IGenreRepository::class, GenreRepositoryProxy::class);
        $this->app->bind(IPlaylistRepository::class, PlaylistRepositoryProxy::class);
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
