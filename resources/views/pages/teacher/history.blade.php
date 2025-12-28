@extends('layouts.app')

@section('title', 'Riwayat BAP & Absensi')
@section('header', 'Riwayat Pertemuan')

@section('content')
<div class="d-flex flex-column gap-4">
    {{-- Header Card --}}
    <div class="card-soft p-4 border-0 shadow-sm bg-white" style="border-radius:18px">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ $session->subject->name }}</h4>
                <div class="d-flex align-items-center gap-3 text-muted">
                    <span class="badge bg-light text-dark border">{{ $session->classroom->name }}</span>
                    <span>•</span>
                    <span>{{ $journals->count() }} Pertemuan Tercatat</span>
                </div>
            </div>
            <a href="{{ route('teacher.schedule') }}" class="btn btn-light text-secondary fw-semibold">Kembali</a>
        </div>
    </div>

    {{-- History List --}}
    @if($journals->count() > 0)
        <div class="card-soft border-0 shadow-sm bg-white overflow-hidden" style="border-radius:18px">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase small fw-bold">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Materi / Topik</th>
                            <th class="text-center px-4 py-3">Kehadiran</th>
                            <th class="text-end px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($journals as $j)
                            <tr>
                                <td class="px-4 py-3 fw-medium text-dark">{{ $j['date'] }}</td>
                                <td class="px-4 py-3 text-secondary">{{ Str::limit($j['topic'], 50) }}</td>
                                <td class="px-4 py-3">
                                    <div class="d-flex justify-content-center gap-2">
                                        <span class="badge bg-success bg-opacity-10 text-success" title="Hadir">{{ $j['hadir'] }} H</span>
                                        <span class="badge bg-warning bg-opacity-10 text-warning" title="Sakit">{{ $j['sakit'] }} S</span>
                                        <span class="badge bg-info bg-opacity-10 text-info" title="Izin">{{ $j['izin'] }} I</span>
                                        <span class="badge bg-danger bg-opacity-10 text-danger" title="Alpa">{{ $j['alpa'] }} A</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <button class="btn btn-sm btn-light text-primary fw-bold rounded-pill px-3" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal" 
                                            data-id="{{ $j['id'] }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-3 text-muted opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
            </div>
            <h5 class="fw-bold text-dark mb-1">Belum ada riwayat</h5>
            <p class="text-secondary small">Belum ada BAP yang dibuat untuk kelas ini.</p>
        </div>
    @endif
</div>


{{-- Detail Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Detail Pertemuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Topik Materi</h6>
                    <p class="fw-medium text-dark mb-0 fs-5" id="modalTopic">Loading...</p>
                    <p class="text-secondary small mb-0" id="modalDate">...</p>
                </div>
                
                <div class="mb-4">
                     <h6 class="text-muted small text-uppercase fw-bold mb-2">Catatan / Jurnal</h6>
                     <div class="p-3 bg-light rounded-3 text-secondary small" id="modalNotes" style="font-style:italic">
                         -
                     </div>
                </div>

                <h6 class="text-muted small text-uppercase fw-bold mb-3">Kehadiran Siswa</h6>
                <div class="d-flex flex-column gap-2" id="modalAttendanceList" style="max-height: 250px; overflow-y:auto">
                     <!-- Populated by JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detailModal = document.getElementById('detailModal');
        const modalTopic = document.getElementById('modalTopic');
        const modalDate = document.getElementById('modalDate');
        const modalNotes = document.getElementById('modalNotes');
        const modalList = document.getElementById('modalAttendanceList');

        detailModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const journalId = button.getAttribute('data-id');

            // Reset
            modalTopic.textContent = 'Memuat...';
            modalDate.textContent = '';
            modalNotes.textContent = '-';
            modalList.innerHTML = '<div class="text-center py-3 text-muted">Mengambil data...</div>';

            // Fetch
            fetch(`/teacher/journal/${journalId}/detail`)
                .then(r => r.json())
                .then(data => {
                    modalTopic.textContent = data.topic;
                    modalDate.textContent = data.date + (data.location ? ` • ${data.location}` : '');
                    modalNotes.textContent = data.notes || 'Tidak ada catatan tambahan.';

                    modalList.innerHTML = '';
                    if(data.attendance.length === 0) {
                        modalList.innerHTML = '<div class="text-center py-2 text-muted">Belum ada data absensi.</div>';
                    } else {
                        data.attendance.forEach(s => {
                            // Badge Color Logic
                            let badgeClass = 'bg-secondary';
                            if (s.status === 'hadir') badgeClass = 'bg-success';
                            else if (s.status === 'alpa') badgeClass = 'bg-danger';
                            else if (s.status === 'sakit') badgeClass = 'bg-warning text-dark';
                            else if (s.status === 'izin') badgeClass = 'bg-info text-dark';

                            const item = `
                                <div class="d-flex align-items-center justify-content-between p-2 border rounded-3 bg-white">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                        <div class="avatar-circle small bg-light text-primary fw-bold d-flex align-items-center justify-content-center" style="width:32px; height:32px; font-size:0.8rem">
                                            ${s.name.substring(0,1)}
                                        </div>
                                        <span class="small fw-medium text-dark text-truncate">${s.name}</span>
                                    </div>
                                    <span class="badge ${badgeClass} text-uppercase" style="font-size:0.7rem">${s.status}</span>
                                </div>
                            `;
                            modalList.insertAdjacentHTML('beforeend', item);
                        });
                    }
                })
                .catch(err => {
                    modalList.innerHTML = '<div class="text-center text-danger">Gagal memuat data.</div>';
                });
        });
    });
</script>
@endsection
