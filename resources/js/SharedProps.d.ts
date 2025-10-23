import { User } from "./globalTypes"

interface SharedProps {
  title: string
  sidebar: boolean
  logo: {
    lg: string
    sm: string
  }
  pageTitle: string
  flash?: {
    error: {
      title: string
      content: string
    }
    success: {
      title: string
      content: string
    }
  }
  auth?: User
  pending_task_count: number

  [key: string]: any,
  system_permissions: string []
}


export default SharedProps
