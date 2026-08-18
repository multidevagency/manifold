export interface ManifoldClientOptions {
  /** Base URL of the Laravel app, e.g. "http://localhost:8000". */
  url: string
  /** Sanctum bearer token. Omit for public (guest-filtered) reads. */
  token?: string | null
  fetch?: typeof fetch
}

export interface ListParams {
  page?: number
  perPage?: number
  /** Field name, prefix with "-" for descending, e.g. "-published_at". */
  sort?: string
  /** Matches against the collection's title field. */
  search?: string
  filter?: Record<string, string | number | boolean>
}

export interface ListResult<T = Record<string, unknown>> {
  data: T[]
  meta: { total: number; page: number; perPage: number; lastPage: number }
}

export interface CollectionApi<T = Record<string, unknown>> {
  list(params?: ListParams): Promise<ListResult<T>>
  find(id: number): Promise<T>
  create(data: Partial<T>): Promise<T>
  update(id: number, data: Partial<T>): Promise<T>
  delete(id: number): Promise<null>
}

export interface ManifoldClient {
  readonly token: string | null
  setToken(token: string | null): void
  login(email: string, password: string): Promise<{ token: string; user: { id: number; name: string; email: string } }>
  schema(): Promise<{ collections: unknown[]; globals: unknown[] }>
  global<T = Record<string, unknown>>(slug: string): {
    get(): Promise<T>
    update(data: Partial<T>): Promise<T>
  }
  collection<T = Record<string, unknown>>(slug: string): CollectionApi<T>
}

export declare function createClient(options: ManifoldClientOptions): ManifoldClient
