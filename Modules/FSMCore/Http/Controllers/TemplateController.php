<?php

namespace Modules\FSMCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMCore\Models\FSMTemplate;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = FSMTemplate::orderBy('name')->paginate(50);
        return view('fsmcore::templates.index', compact('templates'));
    }

    public function create()
    {
        return view('fsmcore::templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                       => 'required|string|max:256',
            'description'                => 'nullable|string|max:65535',
            'checklist'                  => 'nullable|string',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
            'active'                     => 'nullable|boolean',
        ]);

        // Parse checklist to rich JSON step array
        // Supports two input formats from the admin form:
        //   1. Plain textarea (one instruction per line) → adds defaults
        //   2. JSON array of rich objects (from the dynamic step builder)
        if (!empty($data['checklist'])) {
            $decoded = json_decode($data['checklist'], true);
            if (is_array($decoded)) {
                // Already a JSON array (from step builder) — normalise each item
                $rich = array_values(array_filter(array_map(function ($item): ?array {
                    if (is_array($item)) {
                        return [
                            'instruction'        => trim($item['instruction'] ?? ''),
                            'photo_required'     => (bool) ($item['photo_required'] ?? false),
                            'signature_required' => (bool) ($item['signature_required'] ?? false),
                            'is_required'        => (bool) ($item['is_required'] ?? true),
                        ];
                    }
                    if (is_string($item) && trim($item) !== '') {
                        return [
                            'instruction'        => trim($item),
                            'photo_required'     => false,
                            'signature_required' => false,
                            'is_required'        => true,
                        ];
                    }
                    return null;
                }, $decoded), fn($s) => $s !== null && $s['instruction'] !== ''));
                $data['checklist'] = json_encode($rich);
            } else {
                // Plain textarea: one instruction per line
                $lines = array_filter(array_map('trim', explode("\n", $data['checklist'])));
                $rich  = array_values(array_map(fn(string $l): array => [
                    'instruction'        => $l,
                    'photo_required'     => false,
                    'signature_required' => false,
                    'is_required'        => true,
                ], $lines));
                $data['checklist'] = json_encode($rich);
            }
        }

        FSMTemplate::create($data);

        return redirect()->route('fsmcore.templates.index')
            ->with('success', 'Template created.');
    }

    public function edit(int $id)
    {
        $template = FSMTemplate::findOrFail($id);
        return view('fsmcore::templates.edit', compact('template'));
    }

    public function update(Request $request, int $id)
    {
        $template = FSMTemplate::findOrFail($id);

        $data = $request->validate([
            'name'                       => 'required|string|max:256',
            'description'                => 'nullable|string|max:65535',
            'checklist'                  => 'nullable|string',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
            'active'                     => 'nullable|boolean',
        ]);

        if (!empty($data['checklist'])) {
            $decoded = json_decode($data['checklist'], true);
            if (is_array($decoded)) {
                $rich = array_values(array_filter(array_map(function ($item): ?array {
                    if (is_array($item)) {
                        return [
                            'instruction'        => trim($item['instruction'] ?? ''),
                            'photo_required'     => (bool) ($item['photo_required'] ?? false),
                            'signature_required' => (bool) ($item['signature_required'] ?? false),
                            'is_required'        => (bool) ($item['is_required'] ?? true),
                        ];
                    }
                    if (is_string($item) && trim($item) !== '') {
                        return [
                            'instruction'        => trim($item),
                            'photo_required'     => false,
                            'signature_required' => false,
                            'is_required'        => true,
                        ];
                    }
                    return null;
                }, $decoded), fn($s) => $s !== null && $s['instruction'] !== ''));
                $data['checklist'] = json_encode($rich);
            } else {
                $lines = array_filter(array_map('trim', explode("\n", $data['checklist'])));
                $rich  = array_values(array_map(fn(string $l): array => [
                    'instruction'        => $l,
                    'photo_required'     => false,
                    'signature_required' => false,
                    'is_required'        => true,
                ], $lines));
                $data['checklist'] = json_encode($rich);
            }
        }

        $template->update($data);

        return redirect()->route('fsmcore.templates.index')
            ->with('success', 'Template updated.');
    }

    public function destroy(int $id)
    {
        $template = FSMTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('fsmcore.templates.index')
            ->with('success', 'Template deleted.');
    }
}
