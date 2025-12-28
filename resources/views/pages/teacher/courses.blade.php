@extends('layouts.app')

@section('title','Mata Pelajaran')
@section('header','Mata Pelajaran')

@section('content')
  <div class="d-flex flex-column gap-4">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="fw-bold text-dark mb-0">Daftar Mata Pelajaran</h2>
    </div>

    <div class="row g-4">
        @foreach($courses as $c)
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('pages.teacher.materi', ['session' => $c['session_id']]) }}" class="text-decoration-none">
                    <div class="card-soft h-100 p-4 hover-lift d-flex flex-column gap-3 transition-all">
                        <div class="d-flex align-items-start justify-content-between">
                             <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm" 
                                  style="width:56px; height:56px; background:var(--core-primary); font-size:1.5rem; font-weight:700">
                                {{ mb_substr($c['name'], 0, 1) }}
                            </div>
                            <span class="badge bg-light text-secondary border">{{ $c['class'] }}</span>
                        </div>
                        
                        <div>
                            <h5 class="fw-bold text-dark mb-1">{{ $c['name'] }}</h5>
                            <div class="text-muted small">Kelola materi dan tugas</div>
                        </div>

                        <div class="mt-auto pt-3 border-top">
                            <button class="btn btn-outline-primary w-100">Buka Mapel</button>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
  </div>
@endsection
