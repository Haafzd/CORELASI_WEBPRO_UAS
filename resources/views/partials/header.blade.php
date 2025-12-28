<header class="core-header sticky-top" style="background-color:#F6F7FB;">
  <div class="px-3 px-md-4 py-3 d-flex align-items-center gap-3">
    {{-- Burger (mobile) --}}
    <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#coreSidebar" aria-controls="coreSidebar" aria-label="Open sidebar">
      <span style="font-size:20px; line-height:1">&#9776;</span>
    </button>

    <h1 class="m-0 fw-semibold text-truncate" style="font-size:1.05rem; color:#868686;">
      @yield('header','')
    </h1>

    {{-- Search Removed as per request --}}
    <div class="flex-grow-1"></div>

    <div class="ms-auto d-flex align-items-center gap-3">
      {{-- Notifications dropdown --}}
      <div class="dropdown">
        <button class="btn p-0 rounded-circle d-flex align-items-center justify-content-center bg-white border shadow-sm position-relative hover-lift transition-all" 
                type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications" style="width:40px;height:40px; border-color: #eee !important;">
           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell text-secondary" viewBox="0 0 16 16">
              <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
           </svg>
          
          @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="width:10px;height:10px">
              <span class="visually-hidden">New alerts</span>
            </span>
          @endif
        </button>
        <div class="dropdown-menu dropdown-menu-end p-2 border-0 shadow-lg rounded-4" style="width:320px; margin-top:10px">
          <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom mb-2">
            <div class="fw-bold">Notifikasi</div>
            <a href="{{ route('notifications.index') }}" class="text-decoration-none fw-semibold small" style="color:var(--core-primary)">Lihat semua</a>
          </div>
          
          @php
              $recentNotifs = Auth::check() ? Auth::user()->notifications()->take(3)->get() : collect([]);
          @endphp

          <div class="d-flex flex-column gap-1">
             @forelse($recentNotifs as $n)
                <a href="{{ $n->data['link'] ?? route('notifications.index') }}" class="text-decoration-none">
                    <div class="card-soft p-2 hover-bg-light rounded-3">
                        <div class="d-flex justify-content-between align-items-start">
                             <div class="fw-semibold text-dark small">{{ $n->data['title'] ?? 'Info' }}</div>
                             @if(!$n->read_at)<span class="p-1 bg-primary rounded-circle" style="width:6px;height:6px"></span>@endif
                        </div>
                        <div class="text-secondary small text-truncate">{{ $n->data['message'] ?? '-' }}</div>
                        <div class="text-muted extra-small" style="font-size:10px">{{ $n->created_at->diffForHumans() }}</div>
                    </div>
                </a>
             @empty
                <div class="text-center py-3 text-muted small">Tidak ada notifikasi.</div>
             @endforelse
          </div>
        </div>
      </div>

      {{-- Profile --}}
      <div class="dropdown">
        <button class="btn p-0 rounded-circle d-flex align-items-center justify-content-center bg-white border shadow-sm position-relative hover-lift transition-all" 
                type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Profile" style="width:40px;height:40px; border-color: #eee !important;">
           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-fill text-secondary" viewBox="0 0 16 16">
             <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
           </svg>
        </button>
        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2" style="min-width: 200px;">
          <li class="px-3 py-2">
            <div class="fw-bold text-dark">{{ Auth::user()->full_name ?? Auth::user()->username ?? 'User' }}</div>
            <div class="text-muted extra-small">Guru</div>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
              <a class="dropdown-item text-danger fw-semibold rounded-3 py-2 d-flex align-items-center gap-2" href="#" 
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 0h-8A1.5 1.5 0 0 0 0 1.5v9A1.5 1.5 0 0 0 1.5 12h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                  </svg>
                  Keluar / Logout
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
              </form>
          </li>
        </ul>
      </div>
    </div>
  </div>

  {{-- Mobile search row removed --}}
</header>
