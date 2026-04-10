# Post Steps — Pass 6

```bash
cd /home/saassmar/domains/buildsm.art/public_html && php artisan migrate
cd /home/saassmar/domains/buildsm.art/public_html && php artisan optimize:clear
```

Enable draft UI panel (optional):

```
CUSTOMERCONNECT_AI_SHOW_SUGGESTIONS=true
```
