@extends('layouts.mainLayouts')

@section('navbar_header')
Edit Minutes Of Meeting- <b>{{ $header->NOTULEN_TRANS_NOCHAR }}</b>
@endsection

@section('header_title')
Edit Minutes Of Meeting
@endsection

@section('content')
<div class="container-xxl">
  <div class="row justify-content-center">
    <div class="col-md">
      <div class="card">
        <div class="card-body">
          <div style="padding-left: 5px;">
            @if(session()->has('success'))
              <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ session()->get('success') }}</strong>
              </div>
            @endif
            @if(session()->has('error'))
              <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ session()->get('error') }}</strong>
              </div>
            @endif
          </div>

          <form id="form-notulen" action="{{ route('updateNotulen', ['id' => $header->NOTULEN_TRANS_ID]) }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control" id="tema_rapat" name="tema_rapat" value="{{ $header->NOTULEN_TRANS_NAME }}" required>
            </div>

            <div class="form-group">
              <label>Date & Time</label>
              <input type="datetime-local" class="form-control" id="tanggal_rapat" name="tanggal_rapat" value="{{ date('Y-m-d\TH:i', strtotime($header->NOTULEN_TRANS_DATETIME)) }}" required>
            </div>

            <hr>
            <h5>Attendance</h5>
            <div class="row">
              <div class="col-sm-3">
                <input type="text" class="form-control" id="att_name" placeholder="Nama">
              </div>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="att_job_level" placeholder="Job Level">
              </div>
              <div class="col-sm-3">
                <input type="email" class="form-control" id="att_email" placeholder="Email (Optional)">
              </div>
              <div class="col-sm-3">
                <button type="button" class="btn btn-primary" onclick="addAttendance()">Add</button>
              </div>
            </div>
            <div class="mt-2">
              <ul id="attendance_list" class="list-group"></ul>
            </div>

            <hr>

            <div class="form-group">
              <label>Choose PIC</label>
              <select id="point_pic" class="form-control" style="max-width:300px;">
                <option value="">Choose PIC </option>
              </select>
            </div>
            <h5>Point Meeting Title</h5>
            <div class="form-group">
              <input type="text" class="form-control" id="point_title" placeholder="Point Meeting Title">
            </div>
            <h5>Point Meeting</h5>
            <div class="form-group" style="width:100%;">
              <textarea class="form-control" id="point_desc" style="min-height:120px; width:100%;"></textarea>
            </div>
            <div class="d-flex justify-content-end mb-2">
              <button type="button" class="btn btn-primary" onclick="addPoint()">Add</button>
            </div>
            <div class="mt-2">
              <table class="table table-sm table-bordered" id="points_table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Point</th>
                    <th>PIC</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>

            <button type="submit" class="btn btn-success" name="action" value="close" style="float:right; margin-left:10px;">Update & Close</button>
            <button type="submit" class="btn btn-primary" name="action" value="stay" style="float:right;">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
  <?php
    $attArr = [];
    $attIdMap = [];
    foreach ($attendance as $idx => $a) {
      $attArr[] = [
        'name' => $a->NOTULEN_ATTENDANCE_NAME, 
        'job_level' => $a->NOTULEN_ATTENDANCE_LEVEL,
        'email' => $a->NOTULEN_ATTENDANCE_EMAIL
      ];
      $attIdMap[$a->NOTULEN_ATTENDANCE_ID] = $idx;
    }
    $pointArr = [];
    foreach ($details as $d) {
      $attIdx = isset($attIdMap[$d->NOTULEN_ATTENDANCE_ID]) ? $attIdMap[$d->NOTULEN_ATTENDANCE_ID] : 0;
      $pointArr[] = [
        'title' => $d->NOTULEN_DETAIL_TITLE,
        'desc' => $d->NOTULEN_DETAIL_DESC, 
        'attendance_index' => $attIdx
      ];
    }
  ?>
  let attendance = {!! json_encode($attArr) !!};
  let points = {!! json_encode($pointArr) !!};
  let isEditingAttendance = false;
  let isEditingPoint = false;

  function refreshAttendanceUI() {
    const ul = document.getElementById('attendance_list');
    ul.innerHTML = '';
    attendance.forEach((a, idx) => {
      const li = document.createElement('li');
      li.className = 'list-group-item d-flex justify-content-between align-items-center';
      let displayText = a.name || '';
      if (a.job_level) displayText += ' - ' + a.job_level;
      if (a.email) displayText += ' - ' + a.email;
      li.innerHTML = `
        <span>${displayText}</span>
        <div>
          <button type="button" class="btn btn-sm btn-warning" onclick="editAttendance(${idx})">Edit</button>
          <button type="button" class="btn btn-sm btn-danger" onclick="removeAttendance(${idx})">Delete</button>
        </div>
      `;
      ul.appendChild(li);
    });

    const pic = document.getElementById('point_pic');
    const current = pic.value;
    pic.innerHTML = '<option value="">Choose PIC </option>';
    attendance.forEach((a, idx) => {
      const opt = document.createElement('option');
      opt.value = idx;
      opt.text = a.name;
      pic.appendChild(opt);
    });
    if (current !== '' && current < attendance.length) pic.value = current;
  }

  function addAttendance() {
    const name = document.getElementById('att_name').value.trim();
    const jobLevel = document.getElementById('att_job_level').value.trim();
    const email = document.getElementById('att_email').value.trim();
    if (!name) { alert('Nama wajib diisi'); return; }
    if (email && !email.endsWith('@gmail.com')) { alert('Email harus menggunakan @gmail.com'); return; }
    attendance.push({name, job_level: jobLevel, email});
    document.getElementById('att_name').value = '';
    document.getElementById('att_job_level').value = '';
    document.getElementById('att_email').value = '';
    isEditingAttendance = false;
    refreshAttendanceUI();
  }

  function refreshPointsUI() {
    const tbody = document.querySelector('#points_table tbody');
    tbody.innerHTML = '';
    points.forEach((p, i) => {
      const tr = document.createElement('tr');
      const tdNo = document.createElement('td'); tdNo.textContent = i+1; tr.appendChild(tdNo);
      const tdTitle = document.createElement('td'); tdTitle.textContent = p.title || ''; tr.appendChild(tdTitle);
      const tdDesc = document.createElement('td'); tdDesc.innerHTML = p.desc; tr.appendChild(tdDesc);
      const tdPic = document.createElement('td'); tdPic.textContent = attendance[p.attendance_index]?.name || ''; tr.appendChild(tdPic);
      const tdAksi = document.createElement('td');
      tdAksi.innerHTML = `
        <div class="d-flex flex-column align-items-center gap-1">
          <button type="button" class="btn btn-sm btn-warning mb-1" style="width:60px;" onclick="editPoint(${i})">Edit</button>
          <button type="button" class="btn btn-sm btn-danger" style="width:60px;" onclick="removePoint(${i})">Delete</button>
        </div>
      `;
      tr.appendChild(tdAksi);
      tbody.appendChild(tr);
    });
  }

  // Inisialisasi CKEditor untuk point_desc
  CKEDITOR.replace('point_desc', {versionCheck: false});

  function addPoint() {
    // Ambil value dari CKEditor
    const title = document.getElementById('point_title').value.trim();
    const desc = CKEDITOR.instances['point_desc'].getData().trim();
    const picIdx = document.getElementById('point_pic').value;
    if (!title) { alert('Point meeting title wajib diisi'); return; }
    if (!desc) { alert('Point meeting wajib diisi'); return; }
    if (picIdx === '') { alert('Pilih PIC dari attendance'); return; }
    points.push({title, desc, attendance_index: parseInt(picIdx)});
    document.getElementById('point_title').value = '';
    CKEDITOR.instances['point_desc'].setData('');
    isEditingPoint = false;
    refreshPointsUI();
  }

  function removeAttendance(idx) {
    if (confirm('Hapus attendance ini?')) {
      attendance.splice(idx, 1);
      refreshAttendanceUI();
    }
  }

  function editAttendance(idx) {
    if (isEditingAttendance) {
      alert('Selesaikan edit attendance yang sedang berlangsung terlebih dahulu!');
      return;
    }
    if (isEditingPoint) {
      alert('Selesaikan edit point meeting yang sedang berlangsung terlebih dahulu!');
      return;
    }
    const att = attendance[idx];
    document.getElementById('att_name').value = att.name;
    document.getElementById('att_job_level').value = att.job_level || '';
    document.getElementById('att_email').value = att.email || '';
    // Hapus item dari array untuk diedit
    attendance.splice(idx, 1);
    isEditingAttendance = true;
    refreshAttendanceUI();
  }

  function removePoint(idx) {
    if (confirm('Hapus point ini?')) {
      points.splice(idx, 1);
      refreshPointsUI();
    }
  }

  function editPoint(idx) {
    if (isEditingPoint) {
      alert('Selesaikan edit point meeting yang sedang berlangsung terlebih dahulu!');
      return;
    }
    if (isEditingAttendance) {
      alert('Selesaikan edit attendance yang sedang berlangsung terlebih dahulu!');
      return;
    }
    const p = points[idx];
    document.getElementById('point_title').value = p.title || '';
    CKEDITOR.instances['point_desc'].setData(p.desc);
    document.getElementById('point_pic').value = p.attendance_index;
    // Hapus item dari array untuk diedit
    points.splice(idx, 1);
    isEditingPoint = true;
    refreshPointsUI();
  }

  document.addEventListener('DOMContentLoaded', function(){
    refreshAttendanceUI();
    refreshPointsUI();
  });

  document.getElementById('form-notulen').addEventListener('submit', function(e){
    if (attendance.length === 0) { e.preventDefault(); alert('Tambahkan minimal 1 attendance'); return; }
    if (points.length === 0) { e.preventDefault(); alert('Tambahkan minimal 1 point'); return; }
    const form = this;
    attendance.forEach((a, i) => {
      const n = document.createElement('input'); n.type='hidden'; n.name=`attendance[${i}][name]`; n.value=a.name; form.appendChild(n);
      const j = document.createElement('input'); j.type='hidden'; j.name=`attendance[${i}][job_level]`; j.value=a.job_level||''; form.appendChild(j);
      const m = document.createElement('input'); m.type='hidden'; m.name=`attendance[${i}][email]`; m.value=a.email||''; form.appendChild(m);
    });
    points.forEach((p, i) => {
      const t = document.createElement('input'); t.type='hidden'; t.name=`points[${i}][title]`; t.value=p.title; form.appendChild(t);
      const d = document.createElement('input'); d.type='hidden'; d.name=`points[${i}][desc]`; d.value=p.desc; form.appendChild(d);
      const a = document.createElement('input'); a.type='hidden'; a.name=`points[${i}][attendance_index]`; a.value=p.attendance_index; form.appendChild(a);
    });
  });
</script>
@endsection


