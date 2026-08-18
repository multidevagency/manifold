# REST API

Everything lives under `/api/manifold`:

```
POST   /auth/login                 { email, password } -> { token, user }
GET    /auth/me                    (auth)
POST   /auth/logout                (auth)

GET    /schema                     collections + field schemas
GET    /{collection}               ?page&perPage&sort&search&filter[field]=v
POST   /{collection}
GET    /{collection}/{id}
PATCH  /{collection}/{id}          partial update
DELETE /{collection}/{id}

POST   /uploads                    multipart file -> { path, url }
POST   /ai/generate                (auth) AI field content
```

Auth is a Sanctum bearer token. List responses:

```json
{
  "data": [...],
  "meta": { "total": 42, "page": 1, "perPage": 25, "lastPage": 2 }
}
```

- `sort=-published_at` — `-` prefix for descending; only real fields accepted
- `search=needle` — matches the collection's title field
- `filter[status]=published` — exact matches on any field column
- Validation failures return `422` with Laravel's `errors` map
