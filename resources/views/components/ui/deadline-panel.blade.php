@props(['items'=>[]])

<div class="card-soft p-4">
  <div class="d-flex align-items-center justify-content-between mb-2">
    <div class="fw-semibold">Deadline Tugas</div>
    <a href="#" class="text-decoration-none" style="font-size:.9rem;color:var(--core-primary)">Lihat semua</a>
  </div>

  <div class="list-group list-group-flush">
    @forelse($items as $it)
      <div class="list-group-item d-flex align-items-start justify-content-between py-3">
        <div class="me-3">
          <div class="fw-semibold">{{ $it['title'] }}</div>
          <div class="text-muted" style="font-size:.85rem">{{ $it['subject'] }} • {{ $it['class'] }}</div>
        </div>
        <div class="text-nowrap" style="font-size:.9rem">{{ $it['due'] }}</div>
      </div>
    @empty
      <div class="list-group-item text-muted" style="font-size:.9rem">Belum ada deadline.</div>
    @endforelse
  </div>
</div>
