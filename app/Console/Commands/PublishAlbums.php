<?php

namespace App\Console\Commands;

use App\Services\DomainServices\AlbumService;
use Illuminate\Console\Command;

class PublishAlbums extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-albums';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(
        private readonly AlbumService $albumService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->albumService->publishAllReadyAlbums();
    }
}
