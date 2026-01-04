@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    <div class="d-flex flex-column gap-5">
        <div class="d-flex align-items-end justify-content-between">
            <div>
                <h2 class="fw-bold text-dark mb-1" style="font-size:1.75rem; letter-spacing:-0.02em">
                    {{ $greeting }}
                </h2>
                <p class="text-secondary mb-0" style="font-size:1.05rem">
                    Siap untuk mengajar hari ini?
                </p>
            </div>
            <div class="d-none d-md-block text-end">
                <div class="text-muted small text-uppercase fw-bold tracking-wide">Hari ini</div>
                <div class="fw-bold text-dark fs-5">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </div>
            </div>
        </div>

        <div class="row g-4 lg:g-5">
            <div class="col-12 col-lg-8">
                <div class="mb-4 position-relative">
                    <input type="text" id="searchInput"
                        class="form-control form-control-lg border-0 shadow-sm ps-5 py-3 fs-6"
                        placeholder="Cari mata pelajaran atau kelas..." style="border-radius:16px; background:white;">
                    <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                </div>

                <h4 class="fw-bold text-dark mb-3" style="font-family: 'Poppins', sans-serif;">Jadwal Hari Ini</h4>

                <div class="d-flex flex-column gap-3">
                    @forelse($todaySchedule as $sched)
                        <div
                            class="p-4 rounded-4 bg-white shadow-sm position-relative d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-1">
                                    <span class="badge bg-light text-dark border">{{ $sched->classroom->name }}</span>
                                    <span
                                        class="fw-bold text-dark">{{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}
                                        - {{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}</span>
                                </div>
                                <h5 class="fw-bold text-dark mb-0">{{ $sched->subject->name }}</h5>
                            </div>

                            <button type="button"
                                class="btn btn-primary d-flex align-items-center gap-2 btn-bap-modal shadow-sm px-4 py-2"
                                style="border-radius:12px;" data-bs-toggle="modal" data-bs-target="#bapModal"
                                data-session-id="{{ $sched->id }}" data-subject="{{ $sched->subject->name }}"
                                data-class="{{ $sched->classroom->name }}">
                                <span>Buka Kelas</span>
                            </button>
                        </div>
                    @empty
                        <div class="d-flex align-items-center justify-content-center text-muted bg-white rounded-4 shadow-sm"
                            style="min-height: 200px;">
                            <p class="mb-0 fw-medium">Tidak ada jadwal hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-12 col-lg-4 d-flex flex-column gap-4">
                <div class="card-soft p-4 border-0 shadow-sm" style="background:white; border-radius:24px">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="fw-bold text-dark mb-0" style="font-family: 'Poppins', sans-serif;">Perlu Dinilai</h4>
                        @if(count($ungradedAssignments) > 0)
                            <span class="badge bg-danger rounded-pill px-3">{{ count($ungradedAssignments) }} Tugas</span>
                        @endif
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse($ungradedAssignments as $ua)
                            <a href="{{ route('pages.teacher.materi', ['session' => $ua->schedule_session_id]) }}?open_grading={{ $ua->id }}"
                                class="text-decoration-none">
                                <div class="d-flex align-items-center gap-3 p-3 rounded-4 hover-lift transition-all position-relative"
                                    style="background:#FFF5F5; border:1px solid #FED7D7">
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>

                                    <div class="rounded-3 d-flex align-items-center justify-content-center text-danger flex-shrink-0"
                                        style="width:48px; height:48px; background:white; font-size:1.2rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                        </svg>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h6 class="fw-bold text-dark mb-1"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;">
                                            {{ $ua->title }}
                                        </h6>
                                        <div class="d-flex align-items-center gap-2 text-muted small">
                                            <span
                                                class="badge bg-white text-danger border border-danger-subtle px-2 py-0 rounded-pill fs-xs">{{ $ua->submissions_count }}
                                                Siswa</span>
                                            <span class="text-truncate"
                                                style="max-width: 100px;">{{ $ua->session->classroom->name }}</span>
                                        </div>
                                    </div>
                                    <div class="text-secondary opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4 text-muted small">
                                Semua tugas sudah dinilai! 🎉
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h4 class="fw-bold text-dark mb-4" style="font-family: 'Poppins', sans-serif;">Mata Pelajaran Anda</h4>
            <div class="row g-4">
                @foreach($courses as $c)
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="{{ route('pages.teacher.materi', ['session' => $c['session_id']]) }}"
                            class="text-decoration-none">
                            <div class="card-soft p-4 h-100 border-0 shadow-sm hover-lift transition-all position-relative"
                                style="background:white; border-radius:24px">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="rounded-4 d-flex align-items-center justify-content-center text-white"
                                        style="width:56px; height:56px; background:var(--core-primary); font-size:1.5rem; font-weight:700">
                                        {{ mb_substr($c['name'], 0, 1) }}
                                    </div>
                                    <span class="badge bg-light text-dark border">{{ $c['class'] }}</span>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">{{ $c['name'] }}</h5>
                                <p class="text-muted small mb-0">{{ $c['class'] }} • {{ $c['room'] ?? 'Ruang Kelas' }}</p>

                                <div
                                    class="mt-3 text-end text-primary fw-semibold small d-flex align-items-center justify-content-end gap-2">
                                    Lihat Materi
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="modal fade" id="bapModal" tabindex="-1" aria-labelledby="bapModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="border-radius:18px">
                    <form id="bapForm">
                        <div class="modal-header">
                            <h5 class="modal-title fw-semibold" id="bapModalLabel">BAP — <span id="modalSubject"></span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Hidden Session ID -->
                            <input type="hidden" id="bapSessionId">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small text-uppercase fw-bold">Tanggal</label>
                                        <input type="text" class="form-control" id="bapDate" readonly
                                            style="border-radius:12px; background:#f3f4f6">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small text-uppercase fw-bold">Kelas</label>
                                        <input type="text" class="form-control" id="bapClass" readonly
                                            style="border-radius:12px; background:#f3f4f6">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Materi Pembelajaran</label>
                                <input type="text" class="form-control" id="bapTopic" name="topic"
                                    placeholder="Topik materi hari ini" required style="border-radius:12px">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Indikator Pencapaian</label>
                                        <textarea class="form-control" id="bapNotes" name="observation_notes" rows="2"
                                            placeholder="Catatan / Indikator" style="border-radius:12px"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tempat</label>
                                        <input type="text" class="form-control" id="bapLocation" name="location"
                                            placeholder="Ex: R. 203" style="border-radius:12px">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold mb-3">Presensi Siswa</h6>
                            <div class="table-responsive" style="max-height: 300px; overflow-y:auto">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light sticky-top">
                                        <tr>
                                            <th>NIS</th>
                                            <th>Nama Siswa</th>
                                            <th class="text-center" style="width:150px">Kehadiran</th>
                                            <th class="text-center" style="width:100px">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="attendanceParams">
                                        <!-- Populated by JS -->
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                style="border-radius:12px">Batal</button>
                            <button type="submit" class="btn btn-primary" style="border-radius:12px" id="btnSaveBap">Simpan
                                BAP</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.addEventListener('keyup', function () {
                        const query = this.value.toLowerCase();
                        const scheduleItems = document.querySelectorAll('.col-lg-8 .p-4.rounded-4.bg-white.shadow-sm');
                        const courseCards = document.querySelectorAll('.card-soft');
                        scheduleItems.forEach(item => {
                            if (item.querySelector('img')) return;

                            const text = item.textContent.toLowerCase();
                            if (text.includes(query)) {
                                item.style.display = 'flex';
                                item.classList.remove('d-none');
                            } else {
                                item.style.display = 'none';
                                item.classList.add('d-none');
                            }
                        });

                        courseCards.forEach(card => {
                            const text = card.textContent.toLowerCase();
                            const parentCol = card.closest('.col-12');

                            if (text.includes(query)) {
                                if (parentCol) parentCol.style.display = 'block';
                            } else {
                                if (parentCol) parentCol.style.display = 'none';
                            }
                        });
                    });
                }

                const bapModal = document.getElementById('bapModal');
                const btnSave = document.getElementById('btnSaveBap');

                if (bapModal) {
                    bapModal.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        const sessionId = button.getAttribute('data-session-id');

                        document.getElementById('bapForm').reset();
                        document.getElementById('attendanceParams').innerHTML = '<tr><td colspan="4" class="text-center text-muted">Memuat data...</td></tr>';

                        fetch(`/teacher/schedule/${sessionId}/bap-data`)
                            .then(r => r.json())
                            .then(data => {
                                document.getElementById('bapSessionId').value = sessionId;
                                document.getElementById('modalSubject').textContent = data.session.subject.name;
                                document.getElementById('bapClass').value = data.session.classroom.name;
                                document.getElementById('bapDate').value = data.date_formatted;

                                if (data.journal) {
                                    document.getElementById('bapTopic').value = data.journal.topic || '';
                                    document.getElementById('bapNotes').value = data.journal.observation_notes || '';
                                    document.getElementById('bapLocation').value = data.journal.location || '';
                                }

                                const tbody = document.getElementById('attendanceParams');
                                tbody.innerHTML = '';

                                data.students.forEach((s, index) => {
                                    let statusHtml = '';
                                    let checkboxHtml = '';
                                    const isReadOnly = (s.status === 'sakit' || s.status === 'izin');
                                    const isHadir = (s.status === 'hadir');

                                    let badgeClass = 'bg-secondary';
                                    if (s.status === 'hadir') badgeClass = 'bg-success';
                                    else if (s.status === 'alpa') badgeClass = 'bg-danger';
                                    else if (s.status === 'sakit') badgeClass = 'bg-warning';
                                    else if (s.status === 'izin') badgeClass = 'bg-info';

                                    if (isReadOnly) {
                                        statusHtml = `<span class="badge ${badgeClass} text-uppercase">${s.status}</span>`;
                                        checkboxHtml = `<input type="hidden" name="attendance[${index}][status]" value="${s.status}">`;
                                    } else {
                                        const badgeId = `badge-${s.nis}`;
                                        statusHtml = `<span id="${badgeId}" class="badge ${badgeClass} text-uppercase">${s.status}</span>`;

                                        checkboxHtml = `
                                                        <input type="hidden" name="attendance[${index}][nis]" value="${s.nis}">
                                                        <div class="form-check d-flex justify-content-center">
                                                            <input class="form-check-input" type="checkbox" 
                                                                name="attendance[${index}][status]" 
                                                                value="hadir" 
                                                                ${isHadir ? 'checked' : ''}
                                                                style="transform:scale(1.3)"
                                                                onchange="
                                                                    const status = this.checked ? 'hadir' : 'alpa';
                                                                    const badge = document.getElementById('${badgeId}');
                                                                    badge.textContent = status;
                                                                    badge.className = 'badge text-uppercase ' + (status === 'hadir' ? 'bg-success' : 'bg-danger');
                                                                    this.nextElementSibling.value = status;
                                                                ">
                                                            <input type="hidden" name="attendance[${index}][status]" value="${isHadir ? 'hadir' : 'alpa'}">
                                                        </div>
                                                    `;
                                    }

                                    const row = `
                                                    <tr>
                                                        <td>${s.nis}</td>
                                                        <td>${s.name}</td>
                                                        <td class="text-center">${checkboxHtml}</td>
                                                        <td class="text-center">${statusHtml}</td>
                                                    </tr>
                                                `;
                                    tbody.insertAdjacentHTML('beforeend', row);
                                });
                            })
                            .catch(err => {
                                console.error(err);
                                alert('Gagal memuat data jadwal.');
                            });
                    });

                    document.getElementById('bapForm').addEventListener('submit', function (e) {
                        e.preventDefault();
                        const sessionId = document.getElementById('bapSessionId').value;
                        const formData = new FormData(this);

                        const json = Object.fromEntries(formData.entries());
                        btnSave.disabled = true;
                        btnSave.textContent = 'Menyimpan...';

                        fetch(`/teacher/schedule/${sessionId}/bap`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                            .then(r => r.json())
                            .then(res => {
                                if (res.message) {
                                    alert(res.message);
                                    const modalEl = document.getElementById('bapModal');
                                    const modal = bootstrap.Modal.getInstance(modalEl);
                                    modal.hide();
                                } else {
                                    alert('Terjadi kesalahan.');
                                }
                            })
                            .catch(err => alert('Error sending data'))
                            .finally(() => {
                                btnSave.disabled = false;
                                btnSave.textContent = 'Simpan BAP';
                            });
                    });
                }
            });
        </script>
@endsection