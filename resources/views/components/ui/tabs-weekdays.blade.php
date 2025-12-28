@props(['active'=>'Senin','days'=>['Senin','Selasa','Rabu','Kamis','Jumat']])

@php
  $uid = 'weekdayTabs_' . \Illuminate\Support\Str::uuid();
@endphp

<div class="card-soft p-2">
  <ul class="nav nav-pills nav-fill gap-2" id="{{ $uid }}" role="tablist">
    @foreach($days as $d)
      @php($isActive = $d === $active)
      <li class="nav-item" role="presentation">
        <button
          class="nav-link {{ $isActive ? 'active' : '' }}"
          id="{{ $uid }}-{{ $loop->index }}-tab"
          data-bs-toggle="tab"
          data-bs-target="#{{ $uid }}-{{ $loop->index }}"
          type="button"
          role="tab"
          aria-controls="{{ $uid }}-{{ $loop->index }}"
          aria-selected="{{ $isActive ? 'true' : 'false' }}"
          style="border-radius:12px; font-weight:600;"
        >
          {{ $d }}
        </button>
      </li>
    @endforeach
  </ul>

  <div class="tab-content mt-3">
    @foreach($days as $d)
      @php($isActive = $d === $active)
      <div
        class="tab-pane fade {{ $isActive ? 'show active' : '' }}"
        id="{{ $uid }}-{{ $loop->index }}"
        role="tabpanel"
        aria-labelledby="{{ $uid }}-{{ $loop->index }}-tab"
        tabindex="0"
      >
        {{ $slot }}
      </div>
    @endforeach
  </div>
</div>
