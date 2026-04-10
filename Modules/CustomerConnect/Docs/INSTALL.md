# CustomerConnect — Install (WorkSuite / nwidart)

## Location
Extract this module into your WorkSuite project root so it lands at:

- `Modules/CustomerConnect/...`

## Commands
```bash
cd /home/saassmar/domains/ops.tradiesm.art/public_html
php artisan optimize:clear
php artisan module:discover
php artisan module:enable CustomerConnect
php artisan migrate
php artisan optimize:clear
```

## Troubleshooting
- If the module does not appear: `php artisan module:list | grep CustomerConnect`
- If routes missing: `php artisan route:list | grep customerconnect`
- Clear caches: `php artisan optimize:clear`
