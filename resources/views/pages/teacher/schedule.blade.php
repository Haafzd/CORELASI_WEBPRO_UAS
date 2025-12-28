@extends('layouts.app')

@section('title','Teaching Schedule')
@section('header','Jadwal Mengajar')

@section('content')
  <div class="d-flex flex-column gap-5">
    @foreach($scheduleByDay as $day => $sessions)
        <div class="day-section">
            {{-- Day Header --}}
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="px-3 py-1 rounded-3 bg-white border fw-bold text-uppercase shadow-sm" style="color:var(--core-primary); letter-spacing:0.05em">
                    {{ $day }}
                </div>
                <div class="h-px bg-secondary opacity-10 flex-grow-1"></div>
            </div>

            @if(count($sessions) > 0)
                <div class="row g-3">
                    @foreach($sessions as $session)
                        <div class="col-12 col-md-6 col-xl-4">
                             <a href="{{ route('pages.teacher.materi', ['session' => $session->id]) }}" class="text-decoration-none">
                                <div class="card-soft h-100 p-3 p-md-4 position-relative hover-lift">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="badge bg-primary-soft text-primary fw-bold" style="font-size:0.8rem">
                                            {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                                        </div>
                                        <span class="badge bg-light text-secondary border">{{ $session->classroom->name }}</span>
                                    </div>
                                    
                                    <h5 class="fw-bold text-dark mb-1">{{ $session->subject->name }}</h5>
                                    <p class="text-secondary small mb-0">{{ Str::limit($session->subject->description, 50) }}</p>

                                    <div class="mt-3 pt-3 border-top d-flex justify-content-center">
                                        <a href="{{ route('teacher.schedule.history', $session->id) }}" class="btn btn-outline-primary fw-semibold d-flex align-items-center gap-2 px-3 text-decoration-none" style="border-radius:10px; border-color:var(--core-primary); color:var(--core-primary)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                            BAP & Absensi
                                        </a>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card-soft p-4 d-flex align-items-center justify-content-center text-muted" style="border-style:dashed; background:rgba(255,255,255,0.4)">
                    <span class="small">Tidak ada jadwal mengajar.</span>
                </div>
            @endif
        </div>
    @endforeach
  </div>
@endsection
