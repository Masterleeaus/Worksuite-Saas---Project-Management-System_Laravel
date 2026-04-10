<?php

namespace Modules\Aitools\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Modules\Aitools\Entities\AiToolRegistry;

class AiToolDispatcher
{
    /**
     * Dispatch an allowlisted tool by name.
     * Tools are registered as classes in config('aitools.tools_map').
     */
    public static function dispatch(string $toolName, array $params = []): array
    {
        if (!Schema::hasTable('ai_tools_registry')) {
            return ['ok' => false, 'error' => 'Tools registry not installed'];
        }

        $tool = AiToolRegistry::where('tool_name', $toolName)->where('is_enabled', true)->first();
        if (!$tool) {
            return ['ok' => false, 'error' => 'Tool not found or disabled'];
        }

        $toolsMap = config('aitools.tools_map', []);
        $class = $toolsMap[$toolName] ?? null;
        if (!$class || !class_exists($class)) {
            return ['ok' => false, 'error' => 'Tool handler not registered'];
        }

        // Basic schema validation (very lightweight)
        if (is_array($tool->input_schema) && isset($tool->input_schema['required']) && is_array($tool->input_schema['required'])) {
            $rules = [];
            foreach ($tool->input_schema['required'] as $field) {
                $rules[$field] = 'required';
            }
            $v = Validator::make($params, $rules);
            if ($v->fails()) {
                return ['ok' => false, 'error' => 'Invalid params', 'details' => $v->errors()->toArray()];
            }
        }

        $handler = app($class);
        if (method_exists($handler, 'handle')) {
            return $handler->handle($params);
        }

        if (is_callable($handler)) {
            return $handler($params);
        }

        return ['ok' => false, 'error' => 'Tool handler is not callable'];
    }
}
