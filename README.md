# Modular CMS (PHP Backend Challenge)

This project is a modular PHP backend component built with **Laravel 10**.  
It simulates a **Media Management Module** for a future CMS, with support for media storage, metadata enrichment, and search capabilities.  

---

## 🚀 Requirements

- PHP 8.2+  
- Composer  
- SQLite or JSON file storage (we are using JSON persistence for this challenge)  

Recommended extras:
- [Intelephense VSCode Extension](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client) for autocompletion.
- [PHPStan](https://phpstan.org/) for static analysis.

---

## 🔧 Installation

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

## ▶️ Running the Application

We are not using HTTP routes or controllers.
Instead, all interaction is done via **Artisan commands** in the CLI.

Laravel’s built-in commands work as usual, e.g.:

```bash
php artisan about
php artisan list
```

---

## 📦 Media Module Commands

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

## 📂 Project Structure

```
app/
 ┣ Console/Commands/   # Artisan commands
 ┣ Entities/           # Plain entities (POPOs)
 ┣ Interfaces/         # Interfaces for repositories
 ┣ Repositories/       # FileMediaRepository, InMemoryMediaRepository
 ┗ Services/           # MediaService, MediaMetadataService
```

---

## 🧪 Tests (coming soon)

Unit tests will be located in `tests/Unit`.
They will cover:

* Media creation and validation
* Metadata enrichment
* Media resolution for articles
