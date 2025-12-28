@php
  $links = [
    ['label' => 'Dashboard', 'route' => 'teacher.dashboard', 'icon' => 'grid'],
    ['label' => 'Jadwal Mengajar', 'route' => 'teacher.schedule', 'icon' => 'calendar'],
    ['label' => 'Mata Pelajaran', 'route' => 'teacher.courses', 'icon' => 'book'],
  ];
@endphp

<div class="offcanvas-lg offcanvas-start bg-white border-end h-100 flex-column" tabindex="-1" id="coreSidebar" style="width:280px; position:fixed; top:0; left:0; overflow-y:auto; z-index:1050">
  <div class="p-4 d-flex align-items-center justify-content-between">
    <a href="{{ route('teacher.dashboard') }}" class="d-flex align-items-center gap-3 text-decoration-none text-dark">
      <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center text-white fw-bold fs-4" style="width:40px;height:40px;">C</div>
      <div style="line-height:1.2">
        <div class="fw-bold tracking-tight" style="font-size:1.1rem">CORELASI</div>
        <div class="text-secondary small">Teacher Panel</div>
      </div>
    </a>
    <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#coreSidebar"></button>
  </div>

  <div class="px-3">
    <div class="text-uppercase text-secondary fw-bold small px-3 mb-2" style="font-size:0.75rem; letter-spacing:0.05em">Menu</div>
    <nav class="d-flex flex-column gap-1">
      @foreach($links as $l)
        @php($isActive = request()->routeIs($l['route']))
        <a href="{{ route($l['route']) }}" class="sidebar-link {{ $isActive ? 'active' : '' }}">
          @if($l['icon'] === 'grid')
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
          @elseif($l['icon'] === 'calendar')
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
          @elseif($l['icon'] === 'book')
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
          @else
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/></svg>
          @endif
          <span>{{ $l['label'] }}</span>
        </a>
      @endforeach
    </nav>
  </div>
</div>
