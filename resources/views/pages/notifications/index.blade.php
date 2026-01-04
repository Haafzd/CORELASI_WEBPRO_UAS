@extends('layouts.app')

@section('title', 'Notifikasi')
@section('header', 'Notifikasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        
        {{-- Header Section --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold mb-0">Notifikasi</h4>
            
            {{-- Mark All Read Form --}}
            @if(Auth::user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none fw-semibold p-0" style="color:var(--core-primary); font-size:0.9rem">
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>

        <p class="text-muted mb-4">{{ Auth::user()->unreadNotifications->count() }} Notifikasi belum dibaca</p>

        {{-- Tabs --}}
        <div class="d-flex bg-white p-1 rounded-4 shadow-sm mb-4" style="border:1px solid #eee">
            <a href="{{ route('notifications.index', ['type' => 'all']) }}" 
               class="flex-fill text-center py-2 rounded-4 text-decoration-none fw-bold {{ $type == 'all' ? 'bg-white shadow-sm text-dark' : 'text-muted' }}"
               style="{{ $type == 'all' ? 'border:1px solid #eee' : '' }}">
                Semua
            </a>
            <a href="{{ route('notifications.index', ['type' => 'tugas']) }}" 
               class="flex-fill text-center py-2 rounded-4 text-decoration-none fw-bold {{ $type == 'tugas' ? 'bg-white shadow-sm text-dark' : 'text-muted' }}"
               style="{{ $type == 'tugas' ? 'border:1px solid #eee' : '' }}">
                Tugas
            </a>
            <a href="{{ route('notifications.index', ['type' => 'presensi']) }}" 
               class="flex-fill text-center py-2 rounded-4 text-decoration-none fw-bold {{ $type == 'presensi' ? 'bg-white shadow-sm text-dark' : 'text-muted' }}"
               style="{{ $type == 'presensi' ? 'border:1px solid #eee' : '' }}">
                Presensi
            </a>
        </div>

        {{-- Notification List --}}
        <div class="d-flex flex-column gap-3">
            @forelse($notifications as $notif)
                @php
                    $data = $notif->data;
                    $isRead = $notif->read_at !== null;
                    // Icon logic based on type
                    $icon = 'bi-info-circle'; 
                    $color = 'text-primary';
                    $bg = 'bg-blue-50'; // Tailwind class or custom style
                    
                    if(isset($data['type']) && $data['type'] == 'grade_released') {
                        $icon = 'bi-clipboard-check';
                        $color = 'text-success';
                        $bg = '#F0FFF4'; // Light green
                    }
                    if(isset($data['type']) && $data['type'] == 'assignment_new') {
                        $icon = 'bi-file-earmark-text';
                        $color = 'text-primary';
                        $bg = '#EFF6FF'; // Light blue
                    }
                    if(isset($data['type']) && $data['type'] == 'attendance') {
                        $icon = 'bi-calendar-check';
                        $color = 'text-indigo';
                        $bg = '#EEF2FF'; // Indigo tint
                    }
                @endphp

                <div class="card-soft p-3 border {{ $isRead ? 'bg-white' : 'bg-white border-primary border-opacity-25' }} rounded-4 position-relative hover-lift transition-all shadow-sm">
                    {{-- Blue Dot for Unread --}}
                    @if(!$isRead)
                        <span class="position-absolute top-0 end-0 mt-3 me-3 p-1 bg-primary rounded-circle"></span>
                    @endif

                    <div class="d-flex gap-3">
                        {{-- Icon Box --}}
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                             style="width:48px; height:48px; background:{{ $bg }}; color:{{ $data['type']=='grade_released' ? '#22c55e' : ($data['type']=='assignment_new'?'#3b82f6':'#64748b') }}">
                            @if(isset($data['type']) && $data['type'] == 'grade_released')
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                </svg>
                            @elseif(isset($data['type']) && $data['type'] == 'assignment_new')
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                                  <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/>
                                  <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5L9.5 0zm0 1v2A1.5 1.5 0 0 0 11 2.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                                </svg>
                            @elseif(isset($data['type']) && $data['type'] == 'attendance')
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                                  <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                  <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                  <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-dark mb-1">{{ $data['title'] ?? 'Notifikasi Baru' }}</h6>
                            <p class="text-secondary small mb-2">
                                {{ $data['message'] ?? '-' }}
                            </p>
                            <div class="text-muted" style="font-size:0.75rem">
                                {{ $notif->created_at->diffForHumans() }}
                            </div>
                        </div>

                        {{-- Action (if needed) --}}
                         <a href="{{ $data['link'] ?? '#' }}" class="stretched-link"></a> 
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <img src="{{ asset('assets/img/logo.png') }}" class="mb-3 opacity-0 grayscale" style="width:220px">
                    <p>Tidak ada notifikasi saat ini.</p>
                </div>
            @endforelse

            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
