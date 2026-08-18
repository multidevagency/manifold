<?php

namespace Manifold\Cms\Support;

use Manifold\Cms\Collections\Collection;

class MigrationGenerator
{
    public function forCreate(Collection $collection): string
    {
        $table = $collection->table();
        $lines = ['$table->id();'];

        foreach ($collection->columnFields() as $field) {
            $lines[] = $field->columnStatement().';';
        }

        $lines[] = '$table->timestamps();';
        $body = implode("\n            ", $lines);

        return <<<PHPFILE
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            public function up(): void
            {
                Schema::create('{$table}', function (Blueprint \$table) {
                    {$body}
                });
            }

            public function down(): void
            {
                Schema::dropIfExists('{$table}');
            }
        };

        PHPFILE;
    }

    public function forUpdate(Collection $collection, array $diff): string
    {
        $table = $collection->table();
        $up = [];
        $down = [];

        foreach ($diff['rename'] as $field) {
            $up[] = "\$table->renameColumn('{$field->previousColumn()}', '{$field->column()}');";
            $down[] = "\$table->renameColumn('{$field->column()}', '{$field->previousColumn()}');";
        }

        foreach ($diff['add'] as $field) {
            $up[] = $field->columnStatement(forceNullable: true).';';
            $down[] = "\$table->dropColumn('{$field->column()}');";
        }

        foreach ($diff['change'] as $field) {
            $up[] = $field->columnStatement().'->change();';
        }

        foreach ($diff['drop'] as $column) {
            $up[] = "\$table->dropColumn('{$column}');";
        }

        $upBody = implode("\n            ", $up);
        $downBody = $down ? implode("\n            ", $down) : '//';

        return <<<PHPFILE
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            public function up(): void
            {
                Schema::table('{$table}', function (Blueprint \$table) {
                    {$upBody}
                });
            }

            public function down(): void
            {
                Schema::table('{$table}', function (Blueprint \$table) {
                    {$downBody}
                });
            }
        };

        PHPFILE;
    }
}
