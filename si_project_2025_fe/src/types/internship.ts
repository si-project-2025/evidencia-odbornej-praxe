export interface Company {
  company_id: number
  name: string
  ico: number
  address_id: number
}

export interface Status {
  status_id: number
  type: string
}

export interface Garant {
  users_id: number
  first_name: string
  last_name: string
}

export interface Internship {
  internships_id: number
  semester: 'Z' | 'L'
  hours_total: number
  year: number
  created_at: string | null
  updated_at: string | null
  end_at: string | null
  company: Company
  status: Status
  garant: Garant
}
