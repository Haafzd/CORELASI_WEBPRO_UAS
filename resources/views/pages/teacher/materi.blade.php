@extends('layouts.app')

@section('title', 'Materi & Tugas')
@section('header', 'Materi & Tugas')

@section('content')
    <div class="container-fluid px-0">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">{{ $subject->name }} ({{ $subject->code }}) —
                    {{ $session->classroom->name ?? 'Kelas' }}</h2>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Materi Pembelajaran</h5>
                        <p class="text-muted small mb-0">Sumber & Panduan Pembelajaran Siswa</p>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addModal"
                        onclick="setModalType('materi')">
                        + Materi
                    </button>
                </div>

                <div class="d-flex flex-column gap-3">
                    @forelse($materi as $m)
                        <div class="card-soft p-4 position-relative group-action">
                            <div class="position-absolute top-0 end-0 p-3">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted no-caret p-0" data-bs-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                            <path
                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 overflow-hidden">
                                        <li><a class="dropdown-item py-2 small" href="#"
                                                onclick="openEditMaterial('{{ $m->id }}','{{ $m->title }}','{{ $m->description }}','{{ $m->external_link }}')">Edit</a>
                                        </li>
                                        <li>
                                            <form action="{{ route('teacher.material.destroy', $m->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus materi ini?')">
                                                @csrf @method('DELETE')
                                                <button class="dropdown-item py-2 small text-danger">Hapus</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mb-2">
                                <span
                                    class="badge bg-blue-100 text-blue-700 rounded-pill px-3 py-1 small fw-semibold">Materi</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-2" style="font-size:1.1rem">{{ $m->title }}</h6>
                            <p class="text-secondary mb-3 description-text" style="line-height:1.6; font-size:.95rem">
                                @if(strlen($m->description) > 120)
                                    <span class="text-truncated">
                                        {{ Str::limit($m->description, 120) }}
                                    </span>
                                    <span class="text-full d-none">
                                        {{ $m->description }}
                                    </span>
                                    <a href="javascript:void(0)" class="text-primary text-decoration-none small fw-bold ms-1"
                                        onclick="toggleDescription(this)">Baca Selengkapnya</a>
                                @else
                                    {{ $m->description }}
                                @endif
                            </p>
                            @if($m->external_link)
                                <a href="{{ $m->external_link }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 d-inline-flex align-items-center gap-2">
                                    Buka Tautan
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                        class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z" />
                                        <path fill-rule="evenodd"
                                            d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted bg-light rounded-3 border border-dashed">
                            Belum ada materi dibagikan.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Penugasan & Latihan</h5>
                        <p class="text-muted small mb-0">Tenggat waktu & Instruksi</p>
                    </div>
                    <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal"
                        data-bs-target="#addModal" onclick="setModalType('tugas')">
                        + Tugas
                    </button>
                </div>

                <div class="d-flex flex-column gap-3">
                    @forelse($tugas as $t)
                        <div class="card-soft p-4 position-relative group-action">
                            <div class="position-absolute top-0 end-0 p-3">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted no-caret p-0" data-bs-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                            <path
                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 overflow-hidden">
                                        <li><a class="dropdown-item py-2 small" href="#"
                                                onclick="openEditAssignment('{{ $t->id }}','{{ $t->title }}','{{ $t->instruction }}','{{ $t->external_problem_link }}','{{ $t->deadline_at }}')">Edit</a>
                                        </li>
                                        <li>
                                            <form action="{{ route('teacher.assignment.destroy', $t->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus tugas ini?')">
                                                @csrf @method('DELETE')
                                                <button class="dropdown-item py-2 small text-danger">Hapus</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mb-2 d-flex gap-2">
                                <span
                                    class="badge bg-orange-100 text-orange-700 rounded-pill px-3 py-1 small fw-semibold">Tugas</span>
                                @if($t->deadline_at)
                                    <span
                                        class="badge bg-red-50 text-red-600 border border-red-100 rounded-pill px-2 py-1 small d-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor"
                                            class="bi bi-clock" viewBox="0 0 16 16">
                                            <path
                                                d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($t->deadline_at)->format('d M H:i') }}
                                    </span>
                                @endif
                            </div>
                            <h6 class="fw-bold text-dark mb-2" style="font-size:1.1rem">{{ $t->title }}</h6>
                            <p class="text-secondary mb-3 description-text" style="line-height:1.6; font-size:.95rem">
                                @if(strlen($t->instruction) > 120)
                                    <span class="text-truncated">
                                        {{ Str::limit($t->instruction, 120) }}
                                    </span>
                                    <span class="text-full d-none">
                                        {{ $t->instruction }}
                                    </span>
                                    <a href="javascript:void(0)" class="text-primary text-decoration-none small fw-bold ms-1"
                                        onclick="toggleDescription(this)">Baca Selengkapnya</a>
                                @else
                                    {{ $t->instruction }}
                                @endif
                            </p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-semibold"
                                    data-id="{{ $t->id }}" data-title="{{ $t->title }}" onclick="openViewAssignment(this)">
                                    Lihat Pengumpulan
                                </button>
                                @if($t->external_problem_link)
                                    <a href="{{ $t->external_problem_link }}" target="_blank"
                                        class="btn btn-sm btn-outline-success rounded-pill px-4 d-flex align-items-center gap-2">
                                        Link Soal
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                            class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z" />
                                            <path fill-rule="evenodd"
                                                d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted bg-light rounded-3 border border-dashed">
                            Belum ada tugas diberikan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px">
                <form action="{{ route('teacher.materi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="schedule_session_id" value="{{ $session->id }}">
                    <input type="hidden" name="type" id="modalTypeInput">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="addModalTitle">Tambah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">Judul</label>
                            <input type="text" name="title" class="form-control form-control-lg"
                                placeholder="Judul materi atau tugas" required style="border-radius:12px">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">Deskripsi /
                                Instruksi</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan detailnya..."
                                style="border-radius:12px"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">Tautan Luar
                                (Opsional)</label>
                            <input type="url" name="link" class="form-control" placeholder="https://..."
                                style="border-radius:12px">
                        </div>
                        <div class="mb-3 d-none" id="deadlineInputGroup">
                            <label class="form-label fw-semibold small text-muted text-uppercase text-danger">Batas Waktu
                                (Deadline)</label>
                            <input type="datetime-local" name="deadline_at" id="deadlineInput" class="form-control"
                                style="border-radius:12px">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editMaterialModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px">
                <form id="editMaterialForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Materi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">Judul</label>
                            <input type="text" name="title" id="editMatTitle" class="form-control form-control-lg" required
                                style="border-radius:12px">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">Deskripsi</label>
                            <textarea name="description" id="editMatDesc" class="form-control" rows="4"
                                style="border-radius:12px"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">Tautan Luar</label>
                            <input type="url" name="link" id="editMatLink" class="form-control" style="border-radius:12px">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAssignmentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px">
                <form id="editAssignmentForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Tugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">Judul</label>
                            <input type="text" name="title" id="editAssTitle" class="form-control form-control-lg" required
                                style="border-radius:12px">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">Instruksi</label>
                            <textarea name="description" id="editAssDesc" class="form-control" rows="4"
                                style="border-radius:12px"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">Tautan Soal</label>
                            <input type="url" name="link" id="editAssLink" class="form-control" style="border-radius:12px">
                        </div>
                        <div class="mb-3">
                            <label
                                class="form-label fw-semibold small text-muted text-uppercase text-danger">Deadline</label>
                            <input type="datetime-local" name="deadline_at" id="editAssDeadline" class="form-control"
                                required style="border-radius:12px">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="gradingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Submitted - <span id="gradingTitle" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-secondary small fw-bold text-uppercase">
                                <tr>
                                    <th class="py-3 px-3 rounded-start">No.</th>
                                    <th class="py-3">Nama Siswa</th>
                                    <th class="py-3">Deskripsi</th>
                                    <th class="py-3 px-3 text-center rounded-end" style="width: 180px;">Nilai</th>
                                </tr>
                            </thead>
                            <tbody id="gradingList">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setModalType(type) {
            document.getElementById('modalTypeInput').value = type;
            const titleEl = document.getElementById('addModalTitle');
            const deadlineGroup = document.getElementById('deadlineInputGroup');
            const deadlineInput = document.getElementById('deadlineInput');

            if (type === 'materi') {
                titleEl.textContent = 'Tambah Materi';
                deadlineGroup.classList.add('d-none');
                deadlineInput.required = false;
            } else {
                titleEl.textContent = 'Tambah Tugas';
                deadlineGroup.classList.remove('d-none');
                deadlineInput.required = true;
            }
        }

        function openEditMaterial(id, title, desc, link) {
            document.getElementById('editMatTitle').value = title;
            document.getElementById('editMatDesc').value = desc;
            document.getElementById('editMatLink').value = link || '';
            document.getElementById('editMaterialForm').action = `/teacher/materials/${id}`;

            new bootstrap.Modal(document.getElementById('editMaterialModal')).show();
        }

        function openEditAssignment(id, title, desc, link, deadline) {
            document.getElementById('editAssTitle').value = title;
            document.getElementById('editAssDesc').value = desc;
            document.getElementById('editAssLink').value = link || '';
            document.getElementById('editAssDeadline').value = deadline.replace(' ', 'T').substring(0, 16);

            document.getElementById('editAssignmentForm').action = `/teacher/assignments/${id}`;

            new bootstrap.Modal(document.getElementById('editAssignmentModal')).show();
        }

        function openViewAssignment(btn) {
            const id = btn.getAttribute('data-id');
            const title = btn.getAttribute('data-title');

            document.getElementById('gradingTitle').textContent = title;
            const tbody = document.getElementById('gradingList');
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted">Memuat data...</td></tr>';

            new bootstrap.Modal(document.getElementById('gradingModal')).show();

            fetch(`/teacher/assignments/${id}/submissions`)
                .then(r => {
                    if (!r.ok) throw new Error('Server error');
                    return r.json();
                })
                .then(data => {
                    tbody.innerHTML = '';
                    if (data.students.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted">Tidak ada siswa.</td></tr>';
                    } else {
                        data.students.forEach((s, index) => {
                            let scoreHtml = '';
                            if (s.score !== null) {
                                scoreHtml = `<span class="fw-bold text-dark fs-5">${s.score}</span>`;
                            } else {
                                scoreHtml = `<button class="btn btn-sm btn-warning text-dark fw-bold px-3 rounded-pill" onclick="enableGrading(this, '${data.assignment.id}', '${s.nis}')">Beri Nilai</button>`;
                            }

                            const row = `
                                <tr>
                                    <td class="px-3 fw-bold text-secondary">${index + 1}</td>
                                    <td class="fw-medium text-dark">${s.name}</td>
                                    <td class="text-secondary small" style="max-width:250px">${s.note}</td>
                                    <td class="text-center px-3" id="score-cell-${s.nis}">
                                        ${scoreHtml}
                                    </td>
                                </tr>
                            `;
                            tbody.insertAdjacentHTML('beforeend', row);
                        });
                    }
                })
                .catch(err => {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-danger">Gagal memuat data. Silakan coba lagi.</td></tr>';
                });
        }

        function enableGrading(btn, assignId, nis) {
            const cell = btn.closest('td');
            cell.innerHTML = `
                <div class="input-group input-group-sm justify-content-center" style="max-width: 140px; margin:0 auto;">
                    <input type="number" 
                           class="form-control text-center fw-bold" 
                           id="input-${nis}" 
                           min="0" max="100" 
                           placeholder="0-100"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3); if(Number(this.value) > 100) this.value = 100;"
                    >
                    <button class="btn btn-success" type="button" onclick="saveGrade('${assignId}', '${nis}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16"><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/></svg>
                    </button>
                </div>
            `;
            document.getElementById(`input-${nis}`).focus();
        }

        function saveGrade(assignId, nis) {
            const inputRaw = document.getElementById(`input-${nis}`).value;
            const score = parseInt(inputRaw);

            if (isNaN(score) || inputRaw === '') {
                alert('Harap masukkan nilai angka.');
                return;
            }

            fetch('/teacher/submissions/grade', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ assignment_id: assignId, student_nis: nis, score: score })
            })
                .then(r => r.json())
                .then(res => {
                    const cell = document.getElementById(`score-cell-${nis}`);
                    cell.innerHTML = `<span class="fw-bold text-dark fs-5 fade-in">${score}</span>`;
                })
                .catch(err => alert('Gagal menyimpan nilai. Periksa koneksi internet.'));
        }

        function toggleDescription(el) {
            const container = el.parentElement;
            const truncated = container.querySelector('.text-truncated');
            const full = container.querySelector('.text-full');

            if (full.classList.contains('d-none')) {
                full.classList.remove('d-none');
                truncated.classList.add('d-none');
                el.textContent = 'Sembunyikan';
            } else {
                full.classList.add('d-none');
                truncated.classList.remove('d-none');
                el.textContent = 'Baca Selengkapnya';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const openGradingId = urlParams.get('open_grading');

            if (openGradingId) {
                const btn = document.querySelector(`button[data-id="${openGradingId}"]`);
                if (btn) {
                    btn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => {
                        openViewAssignment(btn);
                    }, 500);
                }
            }
        });
    </script>
@endsection