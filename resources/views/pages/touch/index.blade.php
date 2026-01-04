@extends('layouts.app')

@section('title','CORELASI Touch')
@section('header','Attendance — QR Scan')

@section('content')
  <div class="row g-4">
    <div class="col-12 col-lg-6">
      <div class="card-soft p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="fw-semibold">Scan QR</div>
          <span class="badge text-bg-primary" style="border-radius:12px">Camera</span>
        </div>

        <video id="preview" class="w-100 rounded-3 bg-black" style="aspect-ratio:16/9" playsinline></video>
        <div id="result" class="mt-3 text-muted" style="font-size:.9rem">Siapkan QR di kamera.</div>

        <button id="btnStart" class="btn btn-primary mt-3" style="border-radius:12px">Mulai Kamera</button>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card-soft p-4">
        <div class="fw-semibold mb-3">Manual Fallback</div>

        <form id="frmManual" class="d-grid gap-3">
          <input class="form-control" placeholder="NIS" style="border-radius:12px">
          <input class="form-control" placeholder="Session ID" style="border-radius:12px">
          <button class="btn btn-outline-secondary" type="submit" style="border-radius:12px">Tandai Hadir</button>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      document.getElementById('btnStart').addEventListener('click', async ()=>{
        try{
          const v = document.getElementById('preview');
          const s = await navigator.mediaDevices.getUserMedia({ video: { facingMode:'environment' }});
          v.srcObject = s; await v.play();
          document.getElementById('result').textContent = 'Kamera aktif. Arahkan QR ke tengah layar.';
        }catch(e){
          alert('Camera error: '+e);
        }
      });

      document.getElementById('frmManual').addEventListener('submit', (e)=>{
        e.preventDefault();
        alert('Hadir (manual) — demo UI');
      });
    </script>
  @endpush
@endsection
