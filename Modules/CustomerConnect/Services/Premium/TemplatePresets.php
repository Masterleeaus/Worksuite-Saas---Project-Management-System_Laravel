<?php

namespace Modules\CustomerConnect\Services\Premium;

/**
 * Opinionated, service-business-friendly campaign templates.
 *
 * Notes:
 * - Keep placeholders simple: {name}, {business}, {date}, {time}, {link}, {amount}
 * - Channel sanitization is handled by TemplateSanitizer.
 */
class TemplatePresets
{
    /**
     * @return array<string, array<string, array{label:string,subject:?string,body:string}>>
     */
    public function presets(): array
    {
        return [
            'job_reminder' => [
                'sms' => [
                    'label' => 'Cleaning booking reminder (SMS)',
                    'subject' => null,
                    'body' => "Hi {name} — reminder: your cleaning with {business} is booked for {date} at {time}. Reply YES to confirm. Any access notes/pets? Reply with details.",
                ],
                'email' => [
                    'label' => 'Cleaning booking reminder (Email)',
                    'subject' => 'Cleaning reminder: {date} at {time}',
                    'body' => "Hi {name},\n\nJust a reminder your cleaning with {business} is booked for {date} at {time}.\n\nIf you have entry instructions (gate code/keys) or pets on site, please reply with the details.\n\nThanks,\n{business}",
                ],
            ],
            'review_request' => [
                'sms' => [
                    'label' => 'Cleaning review request (SMS)',
                    'subject' => null,
                    'body' => "Thanks {name}! If you were happy with your clean today, could you leave a quick review? It really helps: {link}",
                ],
                'email' => [
                    'label' => 'Cleaning review request (Email)',
                    'subject' => 'How did we do today?',
                    'body' => "Hi {name},\n\nThanks for choosing {business}. If you have 30 seconds, we’d love a quick review of your cleaning: {link}\n\nAppreciate it!\n{business}",
                ],
            ],
            'payment_nudge' => [
                'sms' => [
                    'label' => 'Cleaning invoice reminder (SMS)',
                    'subject' => null,
                    'body' => "Hi {name} — friendly reminder your cleaning invoice of {amount} is due. Pay here: {link} (Reply PAID if done)",
                ],
                'email' => [
                    'label' => 'Cleaning invoice reminder (Email)',
                    'subject' => 'Invoice due: {amount}',
                    'body' => "Hi {name},\n\nJust a reminder your cleaning invoice for {amount} is due. You can pay here: {link}\n\nThanks,\n{business}",
                ],
            ],
            'broadcast' => [
                'sms' => [
                    'label' => 'Cleaning customer update (SMS)',
                    'subject' => null,
                    'body' => "Hi {name} — {message}",
                ],
                'email' => [
                    'label' => 'Cleaning customer update (Email)',
                    'subject' => '{business} update',
                    'body' => "Hi {name},\n\n{message}\n\nThanks,\n{business}",
                ],
            ],

            // Cleaning-specific operational messages
            'arrival_notice' => [
                'sms' => [
                    'label' => 'Cleaner on the way (SMS)',
                    'subject' => null,
                    'body' => "Hi {name} — your cleaner is on the way and will arrive around {time}. Please make sure we can access the property. Reply if anything changes.",
                ],
                'email' => [
                    'label' => 'Cleaner on the way (Email)',
                    'subject' => 'Your cleaner is on the way',
                    'body' => "Hi {name},\n\nYour cleaner is on the way and is expected around {time}.\n\nIf there are any access instructions, please reply to this email.\n\nThanks,\n{business}",
                ],
            ],
            'end_of_lease_check' => [
                'sms' => [
                    'label' => 'End-of-lease checklist (SMS)',
                    'subject' => null,
                    'body' => "Hi {name} — quick check before the end-of-lease clean: are utilities on and is the property empty? Reply YES/NO. If keys are needed, tell us how to collect them.",
                ],
                'email' => [
                    'label' => 'End-of-lease checklist (Email)',
                    'subject' => 'Before your end-of-lease clean',
                    'body' => "Hi {name},\n\nBefore your end-of-lease clean, please confirm:\n• Utilities are on (power/water)\n• Property is empty\n• Key/entry instructions\n\nReply to this email with any notes.\n\nThanks,\n{business}",
                ],
            ],
        ];
    }

    /**
     * @return array{label:string,subject:?string,body:string}|null
     */
    public function get(string $type, string $channel): ?array
    {
        $all = $this->presets();
        return $all[$type][$channel] ?? null;
    }
}
