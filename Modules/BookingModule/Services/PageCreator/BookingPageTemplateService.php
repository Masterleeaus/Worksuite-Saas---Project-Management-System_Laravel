<?php

namespace Modules\BookingModule\Services\PageCreator;

class BookingPageTemplateService
{
    public function templates(): array
    {
        return [
            'premium-home-cleaning' => [
                'name' => 'Premium Home Cleaning',
                'headline' => 'Book premium home cleaning in minutes.',
                'subheadline' => 'Fast online booking, trusted cleaners, live dispatch updates, and a polished customer experience.',
                'hero_badge' => 'Most Popular',
                'primary_button_label' => 'Book now',
                'primary_button_url' => '/account/admin/booking/list',
                'secondary_button_label' => 'Get a quote',
                'secondary_button_url' => '/account/admin/booking/check',
                'service_lines' => [
                    'Standard home cleaning',
                    'Deep cleaning',
                    'End of lease cleaning',
                    'Recurring weekly and fortnightly visits',
                ],
                'trust_lines' => [
                    'Fully managed dispatch and scheduling',
                    'SMS and email-ready booking follow-up',
                    'Clear arrival windows and status tracking',
                ],
                'faq_lines' => [
                    'How soon can I book? | Same-day and scheduled bookings are both supported depending on team availability.',
                    'Can I make recurring bookings? | Yes, recurring schedules are supported and can be tailored to the customer.',
                ],
                'theme' => ['accent' => '#2563eb', 'surface' => '#0f172a', 'soft' => '#e2e8f0'],
            ],
            'property-maintenance' => [
                'name' => 'Property Maintenance',
                'headline' => 'Property jobs booked, assigned, and dispatched from one premium workflow.',
                'subheadline' => 'Perfect for managed premises, facilities teams, strata support, and recurring site services.',
                'hero_badge' => 'Managed Premises',
                'primary_button_label' => 'Request service',
                'primary_button_url' => '/account/admin/booking/list',
                'secondary_button_label' => 'Talk to dispatch',
                'secondary_button_url' => '/account/admin/booking/check',
                'service_lines' => [
                    'Routine site maintenance',
                    'Urgent callout dispatch',
                    'Inspection and reporting visits',
                    'Multi-site service coordination',
                ],
                'trust_lines' => [
                    'Site-ready scheduling flow',
                    'Assignment by team, trade, or availability',
                    'Evidence capture and completion tracking',
                ],
                'faq_lines' => [
                    'Can this handle multiple sites? | Yes, pages can be tailored for portfolios, buildings, and recurring locations.',
                    'Can I send leads into Worksuite? | Yes, the page acts as a front-end booking surface for your booking workflow.',
                ],
                'theme' => ['accent' => '#0f766e', 'surface' => '#111827', 'soft' => '#d1fae5'],
            ],
        ];
    }

    public function resolve(string $template): array
    {
        $templates = $this->templates();
        return $templates[$template] ?? $templates['premium-home-cleaning'];
    }

    public function lineStringToArray(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();
    }

    public function faqStringToArray(?string $value): array
    {
        return collect($this->lineStringToArray($value))
            ->map(function (string $item) {
                [$q, $a] = array_pad(explode('|', $item, 2), 2, '');
                return ['question' => trim($q), 'answer' => trim($a)];
            })
            ->filter(fn (array $item) => $item['question'] !== '')
            ->values()
            ->all();
    }
}
