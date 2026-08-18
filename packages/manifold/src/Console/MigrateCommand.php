<?php

namespace Manifold\Cms\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Manifold\Cms\Registry;
use Manifold\Cms\Support\MigrationGenerator;
use Manifold\Cms\Support\SchemaDiffer;

class MigrateCommand extends Command
{
    protected $signature = 'manifold:migrate {--dry-run : Show the diff without writing or running anything}';

    protected $description = 'Diff collections against the database schema, generate migrations, and run them';

    public function handle(Registry $registry, SchemaDiffer $differ, MigrationGenerator $generator): int
    {
        $written = [];
        $stamp = now();

        foreach ($registry->all() as $slug => $collection) {
            $diff = $differ->diff($collection);

            if ($diff === []) {
                $this->line("  <fg=gray>{$slug}: up to date</>");

                continue;
            }

            if (isset($diff['create'])) {
                $this->info("  {$slug}: create table {$collection->table()}");
                $contents = $generator->forCreate($collection);
                $name = "create_{$collection->table()}_table";
            } else {
                foreach (['add', 'rename', 'change', 'drop'] as $op) {
                    foreach ($diff[$op] as $item) {
                        $column = is_string($item) ? $item : $item->column();
                        $this->info("  {$slug}: {$op} column {$column}");
                    }
                }
                $contents = $generator->forUpdate($collection, $diff);
                $name = "update_{$collection->table()}_table";
            }

            if ($this->option('dry-run')) {
                continue;
            }

            // Migrations run in filename order, so every generated file needs a
            // timestamp that is unique AND later than anything already on disk.
            do {
                $stamp = $stamp->addSecond();
                $prefix = $stamp->format('Y_m_d_His');
            } while (glob(database_path("migrations/{$prefix}_*.php")));

            $path = database_path("migrations/{$prefix}_{$name}.php");
            file_put_contents($path, $contents);
            $written[] = $path;
            $this->line('    -> '.Str::after($path, base_path().'/'));
        }

        if ($this->option('dry-run') || ! $written) {
            $this->newLine();
            $this->line($this->option('dry-run') ? 'Dry run complete.' : 'Nothing to migrate.');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->call('migrate');

        return self::SUCCESS;
    }
}
