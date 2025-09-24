<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MediaService;
use App\Services\MediaMetadataService;
use App\Repositories\FileMediaRepository;


class EnrichMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:enrich 
                            {uuid : UUID of the media} 
                            {metadata* : Key=Value pairs of metadata (e.g. width=1920 height=1080)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enrich metadata of an existing media item';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $repo = new FileMediaRepository();
        $metadataService = new MediaMetadataService($repo);

        $uuid = $this->argument('uuid');
        $metadataArgs = $this->argument('metadata');

        // Parse key=value arguments into an array
        $newMetadata = [];
        foreach ($metadataArgs as $pair) {
            [$key, $value] = explode('=', $pair, 2);
            $newMetadata[$key] = $value;
        }

        $media = $metadataService->enrich($uuid, $newMetadata);

        if (!$media) {
            $this->error("Media not found with UUID: $uuid");
            return;
        }

        $this->info("Metadata enriched successfully:");
        foreach ($media->metadata as $key => $value) {
            $this->line("$key: $value");
        }
    }
}
