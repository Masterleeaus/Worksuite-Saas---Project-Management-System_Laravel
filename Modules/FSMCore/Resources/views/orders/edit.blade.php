@extends('fsmcore::layouts.master')

@section('fsm_content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Edit Order: {{ $order->name }}</h2>
    <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('fsmcore.orders.update', $order->id) }}">
            @csrf
            @include('fsmcore::orders._form', ['order' => $order])

            @if(class_exists(\Modules\FSMSkill\Http\Controllers\OrderSkillController::class) && \Illuminate\Support\Facades\Schema::hasTable('fsm_order_skill_requirements'))
            <div id="skill-match-panel" class="mt-3" style="display:none;">
                <div class="alert" id="skill-match-alert"></div>
            </div>
            <script>
            (function () {
                var personSelId  = 'order_person_id';
                // Fallback: any select named person_id
                var personSel = document.getElementById(personSelId)
                             || document.querySelector('select[name="person_id"]');
                var panel     = document.getElementById('skill-match-panel');
                var alertBox  = document.getElementById('skill-match-alert');
                if (!personSel || !panel || !alertBox) return;

                function check(userId) {
                    if (!userId) { panel.style.display = 'none'; return; }
                    fetch('{{ route("fsmskill.order-skills.validate-worker", $order->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({ user_id: parseInt(userId) })
                    })
                    .then(function(r){ return r.json(); })
                    .then(function(res){
                        panel.style.display = '';
                        alertBox.className = 'alert ' + (res.match ? 'alert-success' : 'alert-danger');
                        var html = res.match
                            ? '<strong>✔ Worker meets all skill requirements.</strong>'
                            : '<strong>✘ Skill requirements not met:</strong><ul class="mb-0 mt-1">';
                        if (!res.match) {
                            res.issues.forEach(function(i){ html += '<li>' + i + '</li>'; });
                            html += '</ul>';
                        }
                        if (res.warnings && res.warnings.length) {
                            html += '<ul class="mb-0 mt-1">';
                            res.warnings.forEach(function(w){ html += '<li class="text-warning">⚠ ' + w + '</li>'; });
                            html += '</ul>';
                        }
                        alertBox.innerHTML = html;
                    })
                    .catch(function(){ panel.style.display = 'none'; });
                }

                personSel.addEventListener('change', function(){ check(this.value); });
                // Check on page load if a worker is already selected
                if (personSel.value) check(personSel.value);
            })();
            </script>
            @endif

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('fsmcore.orders.show', $order->id) }}" class="btn btn-link">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
