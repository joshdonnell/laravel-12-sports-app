export interface Auth {
  user: App.Data.Auth.UserData
  can: Record<string, boolean>
}

export interface BreadcrumbItem {
  title: string
  href: string
}

export type SharedData<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  name: string
  auth: Auth
}

export interface Pagination<T> {
  current_page: number
  data_count: number
  from: number
  last_page: number
  per_page: number
  to: number
  total: number
  data: T[]
  links: {
    url: string | null
    label: string
    page: number | null
    active: boolean
  }[]
}

export type BreadcrumbItemType = BreadcrumbItem
