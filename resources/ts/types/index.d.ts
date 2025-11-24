import { InertiaLinkProps } from '@inertiajs/vue3'
import type { LucideIcon } from 'lucide-vue-next'

export interface Auth {
  user: App.Data.User.UserData
  can: Record<string, boolean>
}

export interface BreadcrumbItem {
  title: string
  href: string
}

export interface NavItem {
  title: string
  href: NonNullable<InertiaLinkProps['href']>
  icon?: LucideIcon
  isActive?: boolean
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
    first: string | null
    last: string | null
    next: string | null
    prev: string | null
  }
}

export type BreadcrumbItemType = BreadcrumbItem
