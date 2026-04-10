# Post Steps — Pass 8

No migrations.

```bash
cd /home/saassmar/domains/buildsm.art/public_html && php artisan optimize:clear
```

## Test (optional)
Trigger an arrival notice to an existing contact by phone:

```bash
cd /home/saassmar/domains/buildsm.art/public_html && php artisan customerconnect:cleaning:status on_the_way --company_id=1 --user_id=1 --phone="+61400111222" --client_name="Sam" --eta_window="15–25 min"
```

Trigger completion (will queue delayed review + quality follow-up):

```bash
cd /home/saassmar/domains/buildsm.art/public_html && php artisan customerconnect:cleaning:status completed --company_id=1 --user_id=1 --phone="+61400111222" --client_name="Sam" --review_link="https://..."
```
