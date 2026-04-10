# Post Steps — Pass 2

1) Deploy/Upload the ZIP and unzip into your modules directory.

2) Clear caches.

3) No migrations required for Pass 2.

## Commands (absolute paths)

- Clear caches:
  - `cd /home/saassmar/domains/buildsm.art/public_html && php artisan optimize:clear`

- If views are cached:
  - `cd /home/saassmar/domains/buildsm.art/public_html && php artisan view:clear`
