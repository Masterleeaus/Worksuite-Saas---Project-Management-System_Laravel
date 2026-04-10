@php
    $canViewDocs = true;
    try {
        if (function_exists('user') && user()) {
            if (method_exists(user(), 'isAbleTo')) {
                $canViewDocs = user()->isAbleTo('view_documents') || user()->isAbleTo('manage_templates') || user()->isAbleTo('ai document manage');
            }
        }
    } catch (\Throwable $e) {
        $canViewDocs = true;
    }
@endphp

@if($canViewDocs)
<li class="sidebar-item">
  <a class="sidebar-link sidebar-toggle" href="#">
    <i class="fa fa-file-contract sidebar-icon"></i>
    <span>{{ __('TitanDocs') }}</span>
  </a>
  <ul class="sidebar-submenu">
    @if(\Illuminate\Support\Facades\Route::has('titandocs.templates.index'))
    <li>
      <a href="{{ route('titandocs.templates.index') }}">
        <i class="fa fa-layer-group"></i>
        {{ __('Template Library') }}
      </a>
    </li>
    @endif
    @if(\Illuminate\Support\Facades\Route::has('aidocument.index'))
    <li>
      <a href="{{ route('aidocument.index') }}">
        <i class="fa fa-robot"></i>
        {{ __('AI Document') }}
      </a>
    </li>
    @endif
    @if(\Illuminate\Support\Facades\Route::has('aidocument.document.history'))
    <li>
      <a href="{{ route('aidocument.document.history') }}">
        <i class="fa fa-history"></i>
        {{ __('History') }}
      </a>
    </li>
    @endif
  </ul>
</li>
@endif
