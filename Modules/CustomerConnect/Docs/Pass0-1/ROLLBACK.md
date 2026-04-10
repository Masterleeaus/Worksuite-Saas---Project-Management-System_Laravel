# Rollback

1) Remove the module folder if needed.
2) Roll back the migration (optional):
   - php artisan migrate:rollback --step=1

Note: If any data has been written to the new columns, rolling back will drop those columns.
