<?php

namespace Modules\Aitools\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Optional log of tool calls made during a conversation.
 */
class AiToolsToolCall extends Model
{
    protected $table = 'ai_tools_tool_calls';

    protected $fillable = [
        'conversation_id',
        'company_id',
        'user_id',
        'tool_name',
        'args',
        'result',
        'status',
        'duration_ms',
    ];

    protected $casts = [
        'args' => 'array',
        'result' => 'array',
    ];
}
