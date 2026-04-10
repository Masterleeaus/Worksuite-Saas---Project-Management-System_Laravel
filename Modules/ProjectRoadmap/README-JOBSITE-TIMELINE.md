Jobsite Timeline Module (Tradies & Builders)

This module is based on the original ProjectRoadmap module but with UI wording
adapted for tradies and builders.

Key changes:
- Sidebar menu item now reads "Jobsite Timeline".
- Stats labels:
  - "Jobs by Stage"      -> "Jobsites by Stage"
  - "Jobs by Priority"   -> "Jobsites by Priority"
  - "Tasks by Status"    remains the same
  - "Hours Planned vs Logged" remains the same

All internal namespaces, table names, route names and config keys remain using
`ProjectRoadmap` / `projectroadmap` to keep the module fully compatible with
Worksuite SaaS and existing migrations/settings.

You can further localise wording globally using your main language files
(e.g. app.php where "Projects" are renamed to "Worksites", "Tasks" to "Jobs", etc).
