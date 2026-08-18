# AI content generation

Textarea and rich text fields get a **✨ generate** button in the admin. It calls `POST /api/manifold/ai/generate`, which prompts Claude with the entry's other field values as context and writes the result into the field — excerpt, body, and meta description are the natural fits.

## Setup

```bash
# .env — server-side only, never exposed to the browser
ANTHROPIC_API_KEY=sk-ant-your-key
MANIFOLD_AI_MODEL=claude-opus-5   # optional override
```

Without a key the endpoint returns a clear `422`; the admin shows the message inline. Requests are authenticated and rate-limited.
