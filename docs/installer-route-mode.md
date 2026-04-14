# Installer Route Mode

This package now uses **Option A** only:

- `routes/Titan/custom-module-installer.routes.php`

The broad host route bundles below were intentionally removed from this package:

- `routes/web-settings.php`
- `routes/SuperAdmin/web.php`

Load only the dedicated installer route file when integrating this overlay.
