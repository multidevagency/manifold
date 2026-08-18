<?php

namespace Manifold\Cms\Support;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Registry;
use ReflectionClass;

/**
 * Writes schema changes back into collection class files, so UI edits and
 * hand edits produce identical code. Only ever used in the local environment.
 */
class CollectionEditor
{
    protected const FIELD_CLASSES = [
        'text' => 'Text',
        'textarea' => 'Textarea',
        'richtext' => 'RichText',
        'email' => 'Email',
        'number' => 'Number',
        'boolean' => 'Boolean',
        'datetime' => 'DateTime',
        'slug' => 'Slug',
        'select' => 'Select',
        'radio' => 'Radio',
        'relationship' => 'Relationship',
        'code' => 'Code',
        'json' => 'Json',
        'date' => 'Date',
        'point' => 'Point',
        'upload' => 'Upload',
    ];

    public function createCollection(string $name): string
    {
        $class = Str::studly($name);

        if (! preg_match('/^[A-Z][A-Za-z0-9]{1,40}$/', $class)) {
            throw new InvalidArgumentException('Collection name must be alphanumeric.');
        }

        $slug = Str::of($class)->snake('-')->toString();
        $path = app_path("Collections/{$class}.php");

        if (file_exists($path)) {
            throw new InvalidArgumentException("Collection {$class} already exists.");
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

        $this->registerInConfig($class);

        return $slug;
    }

    public function addField(Collection $collection, array $spec): string
    {
        $line = $this->fieldLine($collection, $spec);
        $path = (new ReflectionClass($collection))->getFileName();
        $source = file_get_contents($path);

        // The in-memory class may predate earlier edits (PHP cannot reload a
        // loaded class), so the file is the authority for duplicate checks.
        if (str_contains($source, "::make('{$spec['name']}')")) {
            throw new InvalidArgumentException("Field [{$spec['name']}] already exists.");
        }

        $fieldsPos = strpos($source, 'public function fields(): array');
        $closePos = $fieldsPos === false ? false : strpos($source, '];', $fieldsPos);
        if ($closePos === false) {
            throw new InvalidArgumentException('Could not locate the fields() array in '.basename($path));
        }

        // "];" sits at the end of "        ];" — insert the new line above it.
        $lineStart = strrpos(substr($source, 0, $closePos), "\n") + 1;
        file_put_contents($path, substr_replace($source, $line."\n", $lineStart, 0));

        $class = self::FIELD_CLASSES[$spec['type']];
        $import = "use Manifold\\Cms\\Fields\\{$class};";
        if (! str_contains($source, $import)) {
            $this->insertImport($path, $import);
        }

        return $spec['name'];
    }

    public function fieldLine(Collection $collection, array $spec): string
    {
        $type = $spec['type'] ?? '';
        $name = $spec['name'] ?? '';
        $class = self::FIELD_CLASSES[$type] ?? null;

        if (! $class) {
            throw new InvalidArgumentException("Unknown field type [{$type}].");
        }
        if (! preg_match('/^[a-z][a-z0-9_]{0,40}$/', $name)) {
            throw new InvalidArgumentException('Field name must be snake_case.');
        }
        if ($collection->field($name)) {
            throw new InvalidArgumentException("Field [{$name}] already exists.");
        }

        $line = "            {$class}::make('{$name}')";

        if ($type === 'select' || $type === 'radio') {
            $options = array_values(array_filter((array) ($spec['options'] ?? [])));
            if (! $options) {
                throw new InvalidArgumentException('Select fields need at least one option.');
            }
            foreach ($options as $option) {
                if (! preg_match('/^[a-z0-9_-]{1,40}$/', $option)) {
                    throw new InvalidArgumentException("Option [{$option}] must be lowercase alphanumeric.");
                }
            }
            $line .= '->options(['.implode(', ', array_map(fn ($o) => "'{$o}'", $options)).'])';
        }

        if ($type === 'relationship') {
            $to = $spec['to'] ?? '';
            if (! app(Registry::class)->get($to)) {
                throw new InvalidArgumentException("Unknown target collection [{$to}].");
            }
            $line .= "->to('{$to}')";
        }

        if (! empty($spec['required']) && $type !== 'boolean') {
            $line .= '->required()';
        }

        return $line.',';
    }

    protected function registerInConfig(string $class): void
    {
        $path = config_path('manifold.php');
        $source = file_get_contents($path);

        $arrayPos = strpos($source, "'collections' => [");
        $closePos = $arrayPos === false ? false : strpos($source, '],', $arrayPos);
        if ($closePos === false) {
            throw new InvalidArgumentException("Could not locate the collections array in config/manifold.php — register App\\Collections\\{$class} manually.");
        }

        $lineStart = strrpos(substr($source, 0, $closePos), "\n") + 1;
        file_put_contents($path, substr_replace($source, "        App\\Collections\\{$class}::class,\n", $lineStart, 0));
    }

    protected function insertImport(string $path, string $import): void
    {
        $source = file_get_contents($path);
        preg_match_all('/^use [^;]+;$/m', $source, $m, PREG_OFFSET_CAPTURE);

        $imports = array_column($m[0], 0);
        $imports[] = $import;
        sort($imports);

        $first = $m[0][0][1];
        $last = end($m[0]);
        $end = $last[1] + strlen($last[0]);

        file_put_contents($path, substr_replace($source, implode("\n", $imports), $first, $end - $first));
    }
}
