export type Role = 'student' | 'garant' | 'firma'

export type SortableField =
  | 'company'
  | 'student'
  | 'semester'
  | 'year'
  | 'start_at'
  | 'end_at'
  | 'is_paid'
  | 'status'
  | 'created_at'

export type Column = {
  field: SortableField
  label: string
  w: string
  align?: 'left' | 'center' | 'right'
}

const COLUMNS_BY_ROLE: Record<Role, Column[]> = {
  garant: [
    { field: 'company', label: 'Firma', w: '3fr' },
    { field: 'student', label: 'Študent', w: '3fr' },
    { field: 'semester', label: 'Semester', w: '2fr' },
    { field: 'year', label: 'Rok', w: '1fr' },
    { field: 'start_at', label: 'Začiatok praxe', w: '2fr' },
    { field: 'end_at', label: 'Koniec praxe', w: '2fr' },
    { field: 'is_paid', label: 'Typ praxe', w: '2fr' },
    { field: 'status', label: 'Stav', w: '1.4fr', align: 'right' },
  ],
  student: [
    { field: 'company', label: 'Firma', w: '3fr' },
    { field: 'semester', label: 'Semester', w: '2fr' },
    { field: 'year', label: 'Rok', w: '1fr' },
    { field: 'start_at', label: 'Začiatok praxe', w: '2fr' },
    { field: 'end_at', label: 'Koniec praxe', w: '2fr' },
    { field: 'is_paid', label: 'Typ praxe', w: '2fr' },
    { field: 'status', label: 'Stav', w: '1.4fr', align: 'right' },
  ],
  firma: [
    { field: 'student', label: 'Študent', w: '3fr' },
    { field: 'semester', label: 'Semester', w: '2fr' },
    { field: 'year', label: 'Rok', w: '1fr' },
    { field: 'start_at', label: 'Začiatok praxe', w: '2fr' },
    { field: 'end_at', label: 'Koniec praxe', w: '2fr' },
    { field: 'is_paid', label: 'Typ praxe', w: '2fr' },
    { field: 'status', label: 'Stav', w: '1.4fr', align: 'right' },
  ],
}

export const getColumns = (role: Role) => COLUMNS_BY_ROLE[role]

export const getGridTemplate = (role: Role) =>
  getColumns(role)
    .map((c) => c.w)
    .join(' ')

export const getAlignClass = (col: Column) => {
  if (col.align === 'right') return 'justify-end text-right'
  if (col.align === 'center') return 'justify-center text-center'
  return 'justify-start text-left'
}
