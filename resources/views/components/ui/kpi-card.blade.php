@props(['label'=>'','value'=>'-','icon'=>null])
<div class="kpi flex items-center gap-3">
  <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
    <x-ui.icon :name="$icon ?? 'dot'" />
  </div>
  <div>
    <div class="text-sm text-muted">{{ $label }}</div>
    <div class="text-2xl font-semibold">{{ $value }}</div>
  </div>
</div>
