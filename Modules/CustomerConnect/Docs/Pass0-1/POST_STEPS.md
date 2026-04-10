# Post-steps (after upload + unzip)

1) Run migrations
   - php artisan migrate

2) Clear caches
   - php artisan optimize:clear

3) Queue worker
   - Ensure queue worker is running (SendDelivery / SendThreadMessage jobs)

4) Smoke test
   - Create a test contact + thread (or run an existing campaign)
   - Send an SMS channel delivery and confirm the provider creds load from Worksuite Sms module settings.
