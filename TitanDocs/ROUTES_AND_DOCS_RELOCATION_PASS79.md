# Pass 79 packaging changes

- Route files are now stored in `routes/Titan/` to avoid overwriting existing route files.
- Doctor routes were moved from `routes/web-settings.php` to `routes/Titan/custom-module-doctor.routes.php`.
- Installer routes were moved from `routes/custom-module-installer.overlay.php` to `routes/Titan/custom-module-installer.routes.php`.
- SuperAdmin marketplace route overlay was moved to `routes/Titan/superadmin-marketplace.routes.php`.
- Documentation and integration notes were moved under `TitanDocs/` at the project root.
