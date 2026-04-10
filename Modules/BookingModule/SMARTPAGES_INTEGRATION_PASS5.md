# Smart Pages Integration Surface — BookingModule Pass 5

This pass upgrades the module to serve Smart Pages dynamic booking blocks through stable public APIs.

## Public endpoints
- `GET /booking/api/services?slug={slug}`
- `GET /booking/api/availability?days=6`
- `POST /booking/api/request`
- `POST /booking/api/quote-request`
- `GET /booking/api/portal/summary`
- `GET /booking/api/portal/bookings`
- `GET /booking/api/job-status/{reference}`

## Intended Smart Pages block pairings
- `service_selector` → services
- `availability_calendar` → availability
- `booking_request_form` → request
- `quote_request_form` → quote-request
- `portal_dashboard` → portal/summary + portal/bookings
- `job_status_tracker` → job-status

## Notes
- Booking logic remains in BookingModule.
- Smart Pages should render shells and call these APIs.
- Both `Config/config.php` and `config/config.php` carry Worksuite product metadata.
