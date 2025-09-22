<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MediaService;
use App\Repositories\FileMediaRepository;

class SearchMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:search 
                            {--type= : Search media by type (image, video, audio, graph, file)} 
                            {--title= : Search media by title (partial match)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search for media items by type or title';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $repo = new FileMediaRepository();
        $service = new MediaService($repo);

        $type = $this->option('type');
        $title = $this->option('title');

        if (!$type && !$title) {
            $this->error("Please provide either --type or --title option.");
            return;
        }

        $results = [];

        if ($type) {
            $results = $service->searchByType($type);
        } elseif ($title) {
            $results = $service->searchByTitle($title);
        }

        if (empty($results)) {
            $this->warn("No media found for given criteria.");
            return;
        }

        foreach ($results as $media) {
            $this->line("-------------------------");
            $this->line("UUID: {$media->uuid}");
            $this->line("Type: {$media->type}");
            $this->line("Title: {$media->title}");
            $this->line("Description: {$media->description}");
            $this->line("URL: {$media->sourceUrl}");
            $this->line("Date: {$media->uploadedAt->format('Y-m-d H:i:s')}");
            if (!empty($media->metadata)) {
                $this->line("Metadata: " . json_encode($media->metadata));
            }
        }
    }
}
