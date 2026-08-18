<?php

namespace Manifold\Cms\Http\Controllers;

use Anthropic\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Manifold\Cms\Fields\RichText;
use Manifold\Cms\Registry;

class AiController extends Controller
{
    public function generate(Request $request, Registry $registry): JsonResponse
    {
        $data = $request->validate([
            'collection' => 'required|string',
            'field' => 'required|string',
            'context' => 'array',
        ]);

        $collection = $registry->get($data['collection']);
        abort_if($collection === null, 404, "Unknown collection [{$data['collection']}]");

        $field = $collection->field($data['field']);
        abort_if($field === null, 404, "Unknown field [{$data['field']}]");

        $apiKey = config('manifold.anthropic_api_key');
        abort_unless($apiKey, 422, 'Set ANTHROPIC_API_KEY in .env to enable AI generation.');

        $context = collect($data['context'] ?? [])
            ->filter(fn ($v) => is_scalar($v) && $v !== '' && $v !== null)
            ->map(fn ($v, $k) => "{$k}: ".str($v)->limit(500))
            ->implode("\n");

        $format = $field instanceof RichText
            ? 'Clean HTML using only <h2>, <p>, <ul>, <li>, <strong>, <em> tags. 3-6 paragraphs. No <html> or <body> wrapper, no markdown.'
            : 'Plain text, 1-3 sentences. No quotes around the output, no markdown.';

        $client = new Client(apiKey: $apiKey);

        $message = $client->messages->create(
            model: config('manifold.anthropic_model'),
            maxTokens: 2048,
            system: 'You write content for a CMS. Respond with ONLY the requested content — no preamble, no explanation.',
            messages: [[
                'role' => 'user',
                'content' => "Write the \"{$field->name()}\" field for a \"{$collection->labelSingular()}\" entry.\n\n"
                    ."Existing entry data:\n{$context}\n\nOutput format: {$format}",
            ]],
        );

        $text = '';
        foreach ($message->content as $block) {
            if ($block->type === 'text') {
                $text .= $block->text;
            }
        }

        return response()->json(['data' => ['text' => trim($text)]]);
    }
}
