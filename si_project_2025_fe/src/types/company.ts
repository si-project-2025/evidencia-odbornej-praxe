export interface CreateCompanyPayload {
  name: string
  ico: string
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
  address_id: number
}
