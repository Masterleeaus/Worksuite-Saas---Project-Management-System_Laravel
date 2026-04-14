# Titan Tier 2 Filament Migration

## Real entities discovered (installed Worksuite)

- **Employees / Staff**: `App\Models\User`, `App\Models\EmployeeDetails`, `App\Models\EmployeeDocument`
- **Attendance / Time Logs**: `App\Models\Attendance`, `App\Models\ProjectTimeLog`
- **Leave**: `App\Models\Leave`, `App\Models\LeaveType`, `App\Models\LeaveFile`
- **Notes / Activity / Follow-ups**: `App\Models\ProjectNote`, `App\Models\TaskComment`, `App\Models\UserActivity`, `App\Models\EmployeeActivity`
- **Files / Documents**: `App\Models\ProjectFile`, `App\Models\InvoiceFiles`, `App\Models\ClientDocument`, `App\Models\EmployeeDocument`

## Tier 2 resources implemented

- `EmployeeResource` (model: `User`, employee-scoped)
- `AttendanceResource` (model: `Attendance`)
- `TimeLogResource` (model: `ProjectTimeLog`)
- `LeaveResource` (model: `Leave`)
- `ProjectNoteResource` (model: `ProjectNote`)
- `ProjectFileResource` (model: `ProjectFile`)

All resources are wired into Titan panel registration and use tenant-safe querying through `BaseTenantResource` (or an explicit tenant-aware override for project-note flow through `projects.company_id`).

## Models/tables reused

- `users`, `employee_details`
- `attendances`, `project_time_logs`
- `leaves`, `leave_types`, `leave_files`
- `project_notes`
- `project_files`, `invoice_files`, `client_docs`, `employee_docs`

No new business tables were introduced.

## Tenant logic used

- Resource query scoping via `BaseTenantResource::getEloquentQuery()` for `company_id` tables.
- For `ProjectNote`, tenant scope is enforced through related project (`projects.company_id`).
- Record-level checks additionally deny cross-tenant view access unless superadmin.

## Permission logic reused

- Worksuite permission names reused directly:
  - `view_employees`
  - `view_attendance`
  - `view_timelogs`
  - `view_leave`
  - `view_project_note`
  - `view_project_files`
- Existing scope semantics (`all`, `added`, `owned`, `both`, `none`) are honored in resource access checks.
- Titan panel gate remains enforced by existing `TitanPanelProvider` + middleware.

## Relationship map wired into Titan

### Employee-centric

`EmployeeResource` relation managers added for:
- Projects (`member` / project membership)
- Tasks (`tasks`)
- Attendance (`attendance`)
- Time Logs (`timeLogs`)
- Leave (`leaves`)
- Documents (`documents`)

### Project / Invoice / Client document-note surfaces

- `ProjectResource` relation managers added:
  - `ProjectNotesRelationManager`
  - `ProjectFilesRelationManager`
  - `ProjectTimeLogsRelationManager`
- `InvoiceResource` relation manager added:
  - `InvoiceFilesRelationManager`
- `ClientResource` relation manager added:
  - `ClientDocumentsRelationManager`

## Widgets added (Command Centre + panel)

- Employees Active Today
- Clocked In Now
- Leave Requests Pending
- Recent Notes (24h)
- Recent Files (7d)
- Missing Time Logs
- Upcoming Absences (7d)

Widgets query real tenant data and avoid hardcoded values.

## Navigation updates

New Titan navigation groups added:
- Team
- Operations
- Activity
- Documents

## Tests added

`tests/Feature/Titan/TitanTierTwoResourcesTest.php`

Coverage includes:
- tenant-isolated list query assertions for new Tier 2 resources
- guest blocked / admin allowed checks
- owned/self-only visibility check for attendance record access

## Remaining after Tier 2

- Expand write flows (`create/edit`) only where full controller/service parity can be guaranteed in Filament forms.
- Add deeper follow-up/activity resources if needed (`UserActivity`/`EmployeeActivity`) with richer timeline widgets.
- Add broader DB-backed tests for every scope variant (`added`, `both`) per resource.
