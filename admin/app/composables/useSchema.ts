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
  fields: FieldSchema[]
  relationships: Record<string, string>
}

export const useSchema = () => {
  const collections = useState<CollectionSchema[]>('mf.schema', () => [])

  const load = async () => {
    if (collections.value.length) return
    const res = await useApi()<{ collections: CollectionSchema[] }>('/api/manifold/schema')
    collections.value = res.collections
  }

  const get = (slug: string) => collections.value.find(c => c.slug === slug)

  return { collections, load, get }
}
