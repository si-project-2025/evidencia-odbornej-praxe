export interface Address {
  address_id: number
  country: string
  city: string
  zip_code: string
  street: string
  house_number: string
}
export interface Company {
  company_id: number
  name: string
  ico: string
  address: Address
}

export interface Status {
  status_id: number
  type: string
}

export interface Garant {
  users_id: number
  name: string
  surname: string
  email: string
  alt_email: string | null
  phone_number: string | null
}

export interface Document {
  document_id: number
  type: string
  file_name: string
  is_verified: boolean
  internships_id: number
  created_at: string
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
  documents?: Document[]
}

export interface InternshipCreateInput {
  users_id: number
  company_id: number
  semester: 'Z' | 'L'
  year: number
  hours_total?: number
  end_at?: string
  status_id: number
  garant_id: number
}
