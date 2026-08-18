<?php

namespace Manifold\Cms\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCollectionCommand extends Command
{
    protected $signature = 'make:collection {name : Class name, e.g. Products}';

    protected $description = 'Generate a Manifold collection class';

    public function handle(): int
    {
        $class = Str::studly($this->argument('name'));
        $slug = Str::of($class)->snake('-')->toString();
        $path = app_path("Collections/{$class}.php");

        if (file_exists($path)) {
            $this->error("{$path} already exists.");

            return self::FAILURE;
        }

        @mkdir(app_path('Collections'), 0755, true);
        file_put_contents($path, <<<STUB
        <?php

        namespace App\Collections;

        use Manifold\Cms\Collections\Collection;
        use Manifold\Cms\Fields\Slug;
        use Manifold\Cms\Fields\Text;

        class {$class} extends Collection
        {
            protected string \$slug = '{$slug}';

            public function fields(): array
            {
                return [
                    Text::make('title')->required()->useAsTitle(),
                    Slug::make('slug')->from('title'),
                ];
            }
        }

        STUB);

        $this->info("Created {$path}");
        $this->line('Register it in config/manifold.php, then run: php artisan manifold:migrate');

        return self::SUCCESS;
    }
}
