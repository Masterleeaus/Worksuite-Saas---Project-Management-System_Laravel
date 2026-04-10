@extends('fsmstock::layouts.master')
@section('fsmstock_content')
<h4 class="mb-3">Stock Lines — Order #{{ $order->id }}</h4>

{{-- Add new line form --}}
<div class="card mb-4" style="max-width:720px">
    <div class="card-header">Add Stock Line</div>
    <div class="card-body">
        <form method="POST" action="{{ route('fsmstock.order-stock-lines.store', $order->id) }}">
            @csrf
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Product</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">— Select —</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Qty Planned</label>
                    <input type="number" name="qty_planned" class="form-control" step="0.0001" min="0.001" required>
                </div>
                <div class="col-md-2 pt-4">
                    <div class="form-check">
                        <input type="hidden" name="billable" value="0">
                        <input type="checkbox" name="billable" value="1" id="billable" class="form-check-input">
                        <label class="form-check-label" for="billable">Billable</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Add</button>
                </div>
            </div>
            <div class="mt-2">
                <label class="form-label">Notes</label>
                <input type="text" name="notes" class="form-control">
            </div>
        </form>
    </div>
</div>

{{-- Lines table --}}
<div class="card">
    <div class="card-header">Stock Lines</div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Product</th><th>Unit</th><th>Planned</th><th>Used</th>
                    <th>State</th><th>Billable</th><th>Notes</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lines as $line)
                <tr>
                    <td>{{ optional($line->product)->name }}</td>
                    <td>{{ optional($line->product)->unit }}</td>
                    <td>{{ $line->qty_planned }}</td>
                    <td>{{ $line->qty_used !== null ? $line->qty_used : '—' }}</td>
                    <td>
                        @php
                            $badgeMap = ['planned'=>'warning','consumed'=>'success','returned'=>'secondary'];
                            $badge = $badgeMap[$line->state] ?? 'light';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ $line->state }}</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $line->billable ? 'info' : 'light text-dark' }}">
                            {{ $line->billable ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td>{{ $line->notes }}</td>
                    <td>
                        {{-- Consume --}}
                        @if($line->state === 'planned')
                        <form method="POST" action="{{ route('fsmstock.order-stock-lines.consume', $line->id) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="qty_used" value="{{ $line->qty_planned }}">
                            <button type="submit" class="btn btn-xs btn-sm btn-success"
                                    onclick="return confirm('Consume {{ $line->qty_planned }} {{ optional($line->product)->unit }}?')">
                                Consume
                            </button>
                        </form>
                        @endif
                        {{-- Delete --}}
                        <form method="POST" action="{{ route('fsmstock.order-stock-lines.destroy', $line->id) }}" class="d-inline"
                              onsubmit="return confirm('Delete line?')">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted">No stock lines yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
