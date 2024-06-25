<?php

namespace App\Providers;

use App\DataAccessLayer\Queries\FavouriteQuery;
use App\DataAccessLayer\Repository\Implementations\UserRepository;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\IPlaylistRepository;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\DataAccessLayer\Repository\Proxies\AlbumRepositoryProxy;
use App\DataAccessLayer\Repository\Proxies\ArtistRepositoryProxy;
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
        $this->app->bind(IPlaylistRepository::class, PlaylistRepositoryProxy::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
