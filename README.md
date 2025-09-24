# Modular CMS

This project is a modular PHP backend component built with **Laravel 10**.  
It simulates a **Media Management Module** for a future CMS, with support for media storage, metadata enrichment, search capabilities and formatted article export.  

---

## Requirements

- PHP 8.2+  
- Composer  
- SQLite or JSON file storage (we are using JSON persistence for this challenge)  

Recommended extras:
- [Intelephense VSCode Extension](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client) for autocompletion.
- [PHPStan](https://phpstan.org/) for static analysis.

---

## Installation

Clone the repository:

```bash
git clone git@github.com:tatooin6/modular-cms.git
cd modular-cms
````

Install dependencies:

```bash
composer install
```

Generate Laravel application key:

```bash
php artisan key:generate
```

---

## Running the Application

This project is not using HTTP routes or controllers.
Instead, all interaction is done via **Artisan commands** in the CLI.

Laravel’s built-in commands work as usual, e.g.:

```bash
php artisan about
php artisan list
```

---

## Media Module Commands

### 1. Upload a new media

```bash
php artisan media:upload image "Logo" "Company logo" "http://example.com/logo.png"
```

Output:

```
Media successfully created:
UUID: <uuid>
Type: image
Title: Logo
Description: Company logo
URL: http://example.com/logo.png
Date: 2025-09-21 23:15:42
```

The media entry is stored in `storage/media.json`.
Sample media can be found in `storage/media.json.example`.

---

### 2. Enrich media metadata

```bash
php artisan media:enrich <uuid> width=1920 height=1080 format=png
```

Output:

```
Metadata enriched successfully:
width: 1920
height: 1080
format: png
```

---

### 3. Search media

#### By type:

```bash
php artisan media:search --type=image
```

#### By title (partial match):

```bash
php artisan media:search --title=Logo
```

Output:

```
-------------------------
UUID: <uuid>
Type: image
Title: Logo
Description: Company logo
URL: http://example.com/logo.png
Date: 2025-09-21 23:15:42
Metadata: {"width":"1920","height":"1080","format":"png"}
```

---

## Article Module Commands

### 1. Export article with flags (simple use cases)

```bash
php artisan article:export "My First Article" "This is the content" --images=<uuid1> --media=<uuid2>
```

Output (grouped by type):

```json
{
  "articleUuid": "abcd-1234",
  "headline": "My First Article",
  "content": "This is the content",
  "media": {
    "image": [
      { "uuid": "uuid1", "type": "image", "title": "Logo", ... }
    ],
    "video": [
      { "uuid": "uuid2", "type": "video", "title": "Trailer", ... }
    ]
  }
}
```

---

### 2. Export article from JSON file (scalable use cases)

Prepare a file `storage/article.json`:

```json
{
  "headline": "My First Article",
  "content": "This is the content",
  "images": ["uuid1", "uuid2"],
  "media": ["uuid3"]
}
```

Run:

```bash
php artisan article:export-file storage/article.json
```

This allows handling hundreds of media entries easily.
Sample article file: `storage/article.json.example`.

---

## Project Structure

```
app/
 ┣ Console/Commands/   # Artisan commands
 ┣ Entities/           # Plain entities (POPOs)
 ┣ Interfaces/         # Interfaces for repositories and validation strategies
 ┣ Repositories/       # FileMediaRepository, InMemoryMediaRepository
 ┣ Services/           # MediaService, MediaMetadataService, MediaResolverService
 ┗ Strategies/         # Validation strategies (Default, Relaxed)
```

---

## Tests

Unit tests are located in `tests/Unit` and executed with [Pest](https://pestphp.com/).

### Run all tests

```bash
./vendor/bin/pest
```

### Run specific test file

```bash
./vendor/bin/pest --filter=MediaService
```

### Alternative (using composer script)

```bash
composer test
```

The tests cover:

* Media creation and validation (with strategy pattern)
* Metadata enrichment
* Media resolution for articles
* Article export serialization
