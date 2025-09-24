<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Entities\Article;
use App\Services\MediaResolverService;
use App\Repositories\FileMediaRepository;
use Ramsey\Uuid\Uuid;

class ExportArticleFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:export-file {file : Path to the JSON file with article definition}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export an article defined in a JSON file with resolved media as JSON';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return;
        }

        $data = json_decode(file_get_contents($filePath), true);

        if (!$data || !isset($data['headline']) || !isset($data['content'])) {
            $this->error("Invalid JSON structure.");
            return;
        }

        $repo = new FileMediaRepository();
        $resolver = new MediaResolverService($repo);

        $article = new Article(
            Uuid::uuid4()->toString(),
            $data['headline'],
            $data['content'],
            $data['images'] ?? [],
            $data['media'] ?? []
        );

        $resolved = $resolver->resolve($article);

        $output = $article->toArray($resolved);

        $this->line(json_encode($output, JSON_PRETTY_PRINT));
    }
}
