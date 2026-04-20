# Titan Conversational OS and Canvas Handoff Protocol

Defines how Titan Zero interprets chat intent, routes it into operational modes, and hands structured work from conversation into controlled UI/action surfaces.

## Purpose

Conversation is the primary command surface, but not every task should stay as raw chat. This protocol defines how Titan turns conversational intent into structured action without breaking context.

## Core Principles

- chat is the front door
- structure appears only when it reduces ambiguity or risk
- handoff must preserve conversation context
- users should be able to return from structure back to chat seamlessly
- no hidden execution without visible state and approval rules

## Main Components

- intent detector
- mode decider
- action classifier
- context pack builder
- canvas renderer
- approval gate
- result summarizer

## Intent Classes

Suggested classes:
- ask
- find
- draft
- update
- approve
- schedule
- send
- compare
- diagnose
- automate
- navigate

## Mode Routing

The Mode Decider should route intent into a distinct mode, such as:
- Jobs Mode
- Comms Mode
- Finance Mode
- Admin Mode
- Social Media Mode

Mode determines:
- vocabulary mapping
- available tools
- risk thresholds
- card/widget surfaces
- likely domain objects

## Handoff Triggers

A handoff from chat to structured canvas should occur when:
- a form-like action is needed
- multiple fields require confirmation
- a list/table comparison is better than prose
- approvals are required
- a domain object must be created or edited
- there is high execution risk
- the user asks for visual planning or scheduling

## Handoff Envelope

Each handoff should include:
- conversation id
- source message id(s)
- detected intent
- mode
- target domain object type
- prefilled fields
- unresolved questions
- risk level
- suggested next actions

## Canvas Surface Types

Suggested surface types:
- form
- table
- comparison grid
- approval card
- schedule board
- routing panel
- draft editor
- summary card
- wizard stepper

## Bidirectional Continuity

The canvas must retain a link back to the originating conversation.

Chat should be able to:
- open a canvas
- update a canvas draft
- approve/reject a canvas suggestion
- summarize canvas results back into conversation

## Approval Gate

Before sensitive actions, the system should present:
- what is about to happen
- affected records
- confidence/risk notes
- approval requirement
- rollback or cancellation consequences if relevant

## Structured Result Return

After a canvas action, Titan should return a compact conversational summary containing:
- what changed
- what is pending
- any blockers
- next recommended action

## Error Handling

When handoff fails:
- preserve the conversation state
- explain the blocked field or domain issue
- keep partial structured inputs recoverable
- avoid losing user-entered data

## Non-Execution Rule

A handoff does not imply execution.

Execution only occurs when the governing approval rules for the mode and action are satisfied.
