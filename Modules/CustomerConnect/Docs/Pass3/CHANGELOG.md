# Titan Connect — Pass 3 (Business Context Attachment)

## Added
- Thread-linked records (jobs/invoices/tickets/appointments/projects/other)
- Internal thread notes (operator-only)
- Thread view: Linked Records + Internal Notes panels

## Tech
- New tables: `customerconnect_thread_links`, `customerconnect_thread_notes`
- New entities: `ThreadLink`, `ThreadNote`
- New controller: `ThreadContextController`
- New tenant-scoped routes under inbox threads
