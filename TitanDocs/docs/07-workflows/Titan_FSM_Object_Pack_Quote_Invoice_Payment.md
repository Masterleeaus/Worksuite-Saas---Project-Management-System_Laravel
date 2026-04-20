# Titan FSM Object Pack — Quote, Invoice, Payment

Defines canonical finance objects for service businesses.

## Quote

### Core Fields
- quote_id
- tenant_id
- customer_id
- site_id
- amount
- status
- expiry_date
- sent_at
- accepted_at

### Typical States
Draft → Sent → Viewed → Accepted | Rejected | Expired

## Invoice

### Core Fields
- invoice_id
- tenant_id
- customer_id
- quote_id
- due_date
- balance_due
- invoice_status
- delivery_status

### Typical States
Draft → Issued → Delivered → Due → Overdue → Paid | Written Off

## Payment

### Core Fields
- payment_id
- tenant_id
- invoice_id
- payment_method
- amount
- received_at
- reconciliation_status
- payment_status

### Typical States
Pending → Authorized → Settled | Failed | Refunded

## Required Guards
- finance approval for refunds
- idempotent payment session handling
- recovery actions must respect consent and quiet hours

## Suggested Tables

- finance_quotes
- finance_invoices
- finance_payments
- finance_payment_sessions
- finance_refunds
