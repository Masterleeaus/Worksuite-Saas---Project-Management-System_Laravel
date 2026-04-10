<?php

namespace Modules\Aitools\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\Aitools\Services\PromptRunner;

class AiPromptRunController extends AccountBaseController
{
    public function run(Request $request)
    {
        abort_403(!(module_enabled('Aitools') && user()->permission('view_aitools') == 'all'));

        $request->validate([
            'namespace' => 'required|string|max:80',
            'slug' => 'required|string|max:120',
            'version' => 'nullable|integer|min:1',
            'locale' => 'nullable|string|max:12',
            'vars' => 'nullable',
            'system' => 'nullable|string',
            'temperature' => 'nullable|numeric|min:0|max:2',
            'operation' => 'nullable|string|max:40',
        ]);

        $vars = $request->input('vars');
        if (is_string($vars)) {
            $decoded = json_decode($vars, true);
            $vars = is_array($decoded) ? $decoded : [];
        }

        $resp = PromptRunner::run([
            'namespace' => $request->namespace,
            'slug' => $request->slug,
            'version' => (int)($request->version ?: 1),
            'locale' => $request->locale ?: 'en',
            'vars' => is_array($vars) ? $vars : [],
            'system' => $request->system ?: '',
            'temperature' => $request->temperature ?? 0.3,
            'operation' => $request->operation ?: 'prompt_run',
        ]);

        if (!($resp['ok'] ?? false)) {
            return Reply::error((string)($resp['reason'] ?? 'AI error'), ['run_id' => $resp['run_id'] ?? null]);
        }

        return Reply::dataOnly([
            'status' => 'success',
            'run_id' => $resp['run_id'] ?? null,
            'output' => $resp['output'] ?? '',
            'usage' => $resp['usage'] ?? null,
        ]);
    }
}
