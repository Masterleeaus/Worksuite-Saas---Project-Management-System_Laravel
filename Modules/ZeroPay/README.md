# ZeroPay Module

ZeroPay is a payment operations module layered on top of native Worksuite finance.

## Responsibilities
- Payment session creation and public session UX
- Rail selection (zero-fee first)
- Attempt tracking, follow-ups, voice queue stubs
- Reconciliation assist (bank-match queue)
- Callback capture and payment posting into native `payments`

## Non-goals
- Replacing Worksuite invoice/payment/accounting truth
- Parallel auth/accounting systems

## Tenant boundary
All business records are scoped by `company_id`.
