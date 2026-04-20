# Titan Finance Mode Specification

Defines the operational mode for quotes, invoices, payments, collections, recovery, and financial review signals.

## Purpose

Finance Mode should expose money-at-risk, state transitions, and action prompts rather than raw ledger clutter.

## Core Objects

- Quote
- Invoice
- Payment
- PaymentSession
- RecoveryAction
- RefundRequest
- CustomerBalance
- FollowUpPolicy

## Core Surfaces

- money at risk panel
- invoice queue
- quote pipeline
- payment recovery queue
- exception list
- approvals panel
- payment link/session view

## Key Signals

- quote_created
- quote_sent
- invoice_issued
- payment_due
- payment_failed
- payment_received
- followup_due
- refund_requested
- finance_review_required

## Required Guards

- high-risk action approval
- financial idempotency
- customer messaging consent
- reconciliation checks
- payment session timeout handling
- refund policy enforcement

## Recommended Tables

- finance_quotes
- finance_invoices
- finance_payments
- finance_payment_sessions
- finance_recovery_actions
- finance_refunds
- finance_customer_balances
