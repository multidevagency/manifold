export interface FieldSchema {
  name: string
  column: string
  type: string
  label: string
  required?: boolean
  unique?: boolean
  useAsTitle?: boolean
  default?: any
  help?: string
  options?: Record<string, string>
  to?: string
  from?: string
}

export interface CollectionSchema {
  slug: string
  labelSingular: string
  labelPlural: string
  titleField: string | null
  defaultSort: string
  previewUrl: string | null
  fields: FieldSchema[]
  relationships: Record<string, string>
}

export interface GlobalSchema {
  slug: string
  label: string
  fields: FieldSchema[]
}

export const useSchema = () => {
  const collections = useState<CollectionSchema[]>('mf.schema', () => [])
  const globals = useState<GlobalSchema[]>('mf.globals', () => [])

  const refresh = async () => {
    const res = await useApi()<{ collections: CollectionSchema[]; globals: GlobalSchema[] }>('/api/manifold/schema')
    collections.value = res.collections
    globals.value = res.globals ?? []
  }

  const load = async () => {
    if (collections.value.length) return
    await refresh()
  }

  const get = (slug: string) => collections.value.find(c => c.slug === slug)
  const getGlobal = (slug: string) => globals.value.find(g => g.slug === slug)

  return { collections, globals, load, get, getGlobal, refresh }
}
