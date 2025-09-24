<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MediaService;
use App\Repositories\FileMediaRepository;

class UploadMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:upload 
                            {type : Media type (image, video, audio, graph, file)} 
                            {title : Media title} 
                            {description : Media description} 
                            {sourceUrl : Source URL}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a media in the in-memory repository';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $repo = new FileMediaRepository();
        $strategy = new \App\Strategies\DefaultValidationStrategy();
        $service = new MediaService($repo, $strategy);

        $media = $service->createMedia(
            $this->argument('type'),
            $this->argument('title'),
            $this->argument('description'),
            $this->argument('sourceUrl')
        );

        $this->info("Media successfully created:");
        $this->line("UUID: {$media->uuid}");
        $this->line("Type: {$media->type}");
        $this->line("Title: {$media->title}");
        $this->line("Description: {$media->description}");
        $this->line("URL: {$media->sourceUrl}");
        $this->line("Date: {$media->uploadedAt->format('Y-m-d H:i:s')}");
    }
}
