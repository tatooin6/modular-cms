<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Entities\Article;
use App\Services\MediaResolverService;
use App\Repositories\FileMediaRepository;
use Ramsey\Uuid\Uuid;

class ExportArticleCommand extends Command
{
    protected $signature = 'article:export 
                            {headline : Headline of the article} 
                            {content : Content of the article} 
                            {--images=* : UUIDs of image media} 
                            {--media=* : UUIDs of other media attachments}';

    protected $description = 'Export an article with resolved media as JSON';

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

        $resolved = $resolver->resolve($article);

        $output = $article->toArray($resolved);

        $this->line(json_encode($output, JSON_PRETTY_PRINT));
    }
}
