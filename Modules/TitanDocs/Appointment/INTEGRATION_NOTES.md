# Appointment Module Integration Notes

## What was fixed
- removed Spatie `permission:` route middleware usage that would crash Worksuite installs without that middleware package
- added Worksuite sidebar integration views so Appointment routes appear in the Work and Settings menus
- added `appointment:activate` command to create module row, permissions, module settings, and optional seed data

## Run after uploading
```bash
cd ~/domains/admin.buildsm.art/public_html
php artisan optimize:clear
php artisan module:migrate Appointment --force
php artisan appointment:activate
```

## Expected menu placement
- Work → Appointment Dashboard
- Work → Appointments
- Work → Questions
- Work → Bookings
- Work → Unassigned Appointments
- Work → Unassigned Schedules
- Work → My Appointments
- Work → My Schedules
- Work → Dispatch Board
- Work → Appointment Notifications
- Settings → Appointment Auto Assign / Public Booking Security / Notification Preferences / Staff Capacity / Legacy Import
