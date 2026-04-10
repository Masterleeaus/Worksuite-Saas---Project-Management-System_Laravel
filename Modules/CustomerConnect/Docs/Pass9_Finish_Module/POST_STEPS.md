# Post-steps (Worksuite)

## Upload
Upload and unzip into your Worksuite `public_html` so the module lives at:
`Modules/CustomerConnect`

## Run
```bash
cd /home/saassmar/domains/admin.cleanhub.pro/public_html && php artisan module:enable CustomerConnect
cd /home/saassmar/domains/admin.cleanhub.pro/public_html && php artisan migrate

# Backfill module_settings for existing companies (required)
cd /home/saassmar/domains/admin.cleanhub.pro/public_html && php artisan customerconnect:activate

# Clear caches
cd /home/saassmar/domains/admin.cleanhub.pro/public_html && php artisan cache:clear
cd /home/saassmar/domains/admin.cleanhub.pro/public_html && php artisan config:clear
cd /home/saassmar/domains/admin.cleanhub.pro/public_html && php artisan view:clear
cd /home/saassmar/domains/admin.cleanhub.pro/public_html && php artisan optimize:clear
```

## Packages label fix (required)
Open the patch note:
`PATCHES/core/resources/lang/en/modules.php.ADD_THIS_SNIPPET.txt`
and add the snippet to your core modules translation file.

## Verify (acceptance criteria)
- Superadmin → Packages → Create/Edit: **customerconnect** appears ✅
- Add to package → assign package to test company ✅
- Login company admin: sidebar shows **Titan Connect** ✅
- Remove from package: menu disappears ✅
- Set permission `view_customerconnect` to `none`: menu hidden ✅
