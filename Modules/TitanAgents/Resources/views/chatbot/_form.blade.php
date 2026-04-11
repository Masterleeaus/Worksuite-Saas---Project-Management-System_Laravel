<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $chatbot->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">AI Provider <span class="text-danger">*</span></label>
        <select name="ai_provider" class="form-select @error('ai_provider') is-invalid @enderror" required>
            @foreach(['openai' => 'OpenAI', 'anthropic' => 'Anthropic', 'gemini' => 'Google Gemini'] as $value => $label)
                <option value="{{ $value }}" {{ old('ai_provider', $chatbot->ai_provider ?? 'openai') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('ai_provider')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">AI Model</label>
        <input type="text" name="ai_model" class="form-control @error('ai_model') is-invalid @enderror"
               value="{{ old('ai_model', $chatbot->ai_model ?? '') }}" placeholder="e.g. gpt-4o-mini">
        @error('ai_model')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Temperature</label>
        <input type="number" name="temperature" step="0.01" min="0" max="2"
               class="form-control @error('temperature') is-invalid @enderror"
               value="{{ old('temperature', $chatbot->temperature ?? 0.70) }}">
        @error('temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">Max Tokens</label>
        <input type="number" name="max_tokens" min="100" max="8000"
               class="form-control @error('max_tokens') is-invalid @enderror"
               value="{{ old('max_tokens', $chatbot->max_tokens ?? 2000) }}">
        @error('max_tokens')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description', $chatbot->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">System Prompt</label>
        <textarea name="system_prompt" class="form-control @error('system_prompt') is-invalid @enderror" rows="4"
                  placeholder="Instructions that define the chatbot's behaviour...">{{ old('system_prompt', $chatbot->system_prompt ?? '') }}</textarea>
        @error('system_prompt')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Welcome Message</label>
        <input type="text" name="welcome_message" class="form-control @error('welcome_message') is-invalid @enderror"
               value="{{ old('welcome_message', $chatbot->welcome_message ?? '') }}" placeholder="Hello! How can I help you?">
        @error('welcome_message')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Fallback Message</label>
        <input type="text" name="fallback_message" class="form-control @error('fallback_message') is-invalid @enderror"
               value="{{ old('fallback_message', $chatbot->fallback_message ?? '') }}" placeholder="Sorry, I am unable to respond right now.">
        @error('fallback_message')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
