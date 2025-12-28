@props(['name' => 'dot'])
@php
$paths = [
  'home' => 'M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z',
  'calendar' => 'M7 2v2M17 2v2M3 8h18M5 6h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z',
  'book' => 'M4 5a2 2 0 0 1 2-2h11a3 3 0 0 1 3 3v14H8a2 2 0 0 0-2 2H4z',
  'dot' => 'M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0-4 0'
];
@endphp
<svg class="w-5 h-5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
  <path d="{{ $paths[$name] ?? $paths['dot'] }}" />
</svg>
