<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

/**
 * TitanChatWidget
 *
 * Placeholder widget for the Titan Command Centre.
 * Will become the Titan chatbot OS control surface once the AI bridge is wired.
 *
 * Logic to be implemented in a future Titan Zero sprint.
 */
class TitanChatWidget extends Widget
{
    protected static ?int $sort = 5;

    protected static string $view = 'filament.widgets.titan-chat-widget';

    protected int | string | array $columnSpan = 'full';
}
