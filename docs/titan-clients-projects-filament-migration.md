# Titan Clients + Projects Filament Migration

## Entities migrated
- Clients (`App\\Filament\\Resources\\ClientResource`)
- Projects (`App\\Filament\\Resources\\ProjectResource`)

## Fields exposed
- Clients: `name`, `company_name`, `email`, `phone` (`mobile`), `status`, `created_at`
- Projects: `project_name`, `client`, `status`, `progress` (`completion_percent`), `start_date`, `deadline`, `project_admin`, `created_at`

## Relationships wired
- Client resource reads client company details from `User -> clientDetails`
- Project resource reads relationships: `client`, `projectAdmin`, `members`, `tasks`, `invoices`

## Tenant logic used
- Both resources extend `BaseTenantResource`
- `BaseTenantResource::getEloquentQuery()` enforces `company_id` filtering for the authenticated tenant
- Additional resource query constraints keep clients scoped to role `client`

## Permission logic reused
- Titan panel access still enforced via `TitanPanelProvider::canAccess()`
- Client resource reuses Worksuite permission keys: `view_clients`, `add_clients`, `edit_clients`
- Project resource reuses Worksuite permission keys: `view_projects`, `add_projects`, `edit_projects`
- Superadmin/admin allowed; non-authorized users blocked by the same permission semantics

## Navigation + widgets
- Navigation groups added: `CRM` (Clients), `Operations` (Projects)
- Command Centre widgets added:
  - `TotalClientsWidget`
  - `ActiveProjectsWidget`

## Next migration targets
- Invoices
- Tasks
- Contracts
- Estimates
