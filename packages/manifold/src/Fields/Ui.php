<?php

namespace Manifold\Cms\Fields;

/** Renders a note in the admin form; owns no data. */
class Ui extends Field
{
    public static function note(string $text): static
    {
        return static::make('ui_note_'.substr(md5($text), 0, 6))->help($text);
    }

    public function hasColumn(): bool
    {
        return false;
    }

    public function type(): string
    {
        return 'ui';
    }

    public function sqlType(): string
    {
        return '';
    }

    protected function baseStatement(): string
    {
        return '';
    }
}
