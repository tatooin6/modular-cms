<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Entities\Article;
use App\Services\MediaResolverService;
use App\Repositories\FileMediaRepository;
use Ramsey\Uuid\Uuid;

class ShowArticleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:show 
                            {headline : Headline of the article} 
                            {content : Content of the article} 
                            {--images=* : UUIDs of image media} 
                            {--media=* : UUIDs of other media attachments}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show an article with its resolved media attachments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $repo = new FileMediaRepository();
        $resolver = new MediaResolverService($repo);

        $article = new Article(
            Uuid::uuid4()->toString(),
            $this->argument('headline'),
            $this->argument('content'),
            $this->option('images'),
            $this->option('media')
        );

        $this->info("Article:");
        $this->line("UUID: {$article->articleUuid}");
        $this->line("Headline: {$article->headline}");
        $this->line("Content: {$article->content}");

        $this->info("\nResolved Media Attachments:");
        $resolved = $resolver->resolve($article);

        if (empty($resolved)) {
            $this->warn("No media found for this article.");
            return;
        }

        foreach ($resolved as $media) {
            $this->line("-------------------------");
            $this->line("UUID: {$media->uuid}");
            $this->line("Type: {$media->type}");
            $this->line("Title: {$media->title}");
            $this->line("URL: {$media->sourceUrl}");
        }
    }
}
