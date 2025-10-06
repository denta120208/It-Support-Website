@extends('layouts.mainLayouts')

@section('navbar_header')
Minutes Of Meeting - <b>{{session('current_project_char')}}</b>
@endsection

@section('header_title')
Minutes Of Meeting
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

          <form id="form-notulen" action="{{ route('saveNotulen') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control" id="tema_rapat" name="tema_rapat" placeholder="Title" required>
            </div>

            <div class="form-group">
              <label>Date & Time</label>
              <input type="datetime-local" class="form-control" id="tanggal_rapat" name="tanggal_rapat" required>
            </div>

            <hr>
            <h5>Attendance</h5>
            <div class="row">
              <div class="col-sm-3">
                <input type="text" class="form-control" id="att_name" placeholder="Name">
              </div>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="att_job_level" placeholder="Job Level">
              </div>
              <div class="col-sm-3">
                <input type="email" class="form-control" id="att_email" placeholder="Email (Optional)">
              </div>
              <div class="col-sm-3">
                <button type="button" class="btn btn-primary" onclick="addAttendance()">Add </button>
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
            <h5>Point Meeting Topic</h5>
            <div class="form-group">
              <input type="text" class="form-control" id="point_title" placeholder="Point Meeting Title">
            </div>
            <h5>Point Meeting</h5>
            <div class="form-group" style="width:100%;">
              <textarea class="form-control" id="point_desc" name="point_desc" style="min-height:120px; width:100%;"></textarea>
            </div>
            <div class="d-flex justify-content-end mb-2">
              <button type="button" class="btn btn-primary" onclick="addPoint()">Add</button>
            </div>
            <div class="mt-2">
              <table class="table table-sm table-bordered" id="points_table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Topic</th>
                    <th>Point</th>
                    <th>PIC</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>

            <button type="submit" class="btn btn-success" style="float:right">Submit</button>
            <button type="button" class="btn btn-secondary mr-2" id="cancelFormBtn" style="float:right; margin-right:10px;">Cancel</button>
          </form>

          <!-- List notulen dihilangkan, hanya form input yang tampil di halaman ini -->
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
  let attendance = []; // {name, job_level, email}
  let points = [];     // {title, desc, attendance_index}

  // Inisialisasi CKEditor untuk point_desc
  CKEDITOR.replace('point_desc', {versionCheck: false});

  // Hilangkan toggle form, form langsung tampil

  function refreshAttendanceUI() {
    const ul = document.getElementById('attendance_list');
    ul.innerHTML = '';
    attendance.forEach((a, idx) => {
      const li = document.createElement('li');
      li.className = 'list-group-item d-flex justify-content-between align-items-center';
      let displayText = a.name || '';
      if (a.job_level) displayText += ' - ' + a.job_level;
      if (a.email) displayText += ' - ' + a.email;
      // Tambahkan tombol hapus
      const delBtn = document.createElement('button');
      delBtn.className = 'btn btn-danger btn-sm';
      delBtn.textContent = 'Hapus';
      delBtn.style.marginLeft = '10px';
      delBtn.onclick = function() {
        removeAttendance(idx);
      };
      const span = document.createElement('span');
      span.textContent = displayText;
      li.appendChild(span);
      li.appendChild(delBtn);
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

  function removeAttendance(idx) {
    // Hapus attendance pada index idx
    attendance.splice(idx, 1);
    // Juga update points yang refer ke attendance_index
    points = points.filter(p => p.attendance_index !== idx);
    // Update attendance_index pada points yang lebih besar dari idx
    points.forEach(p => {
      if (p.attendance_index > idx) p.attendance_index--;
    });
    refreshAttendanceUI();
    refreshPointsUI();
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
      tbody.appendChild(tr);
    });
  }

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
    refreshPointsUI();
  }

  document.getElementById('form-notulen').addEventListener('submit', function(e){
    if (attendance.length === 0) { e.preventDefault(); alert('Tambahkan minimal 1 attendance'); return; }
    if (points.length === 0) { e.preventDefault(); alert('Tambahkan minimal 1 point'); return; }
    // inject hidden inputs
    const form = this;
    // attendance
    attendance.forEach((a, i) => {
      const n = document.createElement('input'); n.type='hidden'; n.name=`attendance[${i}][name]`; n.value=a.name; form.appendChild(n);
      const j = document.createElement('input'); j.type='hidden'; j.name=`attendance[${i}][job_level]`; j.value=a.job_level||''; form.appendChild(j);
      const m = document.createElement('input'); m.type='hidden'; m.name=`attendance[${i}][email]`; m.value=a.email||''; form.appendChild(m);
    });
    // points
    points.forEach((p, i) => {
      const t = document.createElement('input'); t.type='hidden'; t.name=`points[${i}][title]`; t.value=p.title; form.appendChild(t);
      const d = document.createElement('input'); d.type='hidden'; d.name=`points[${i}][desc]`; d.value=p.desc; form.appendChild(d);
      const a = document.createElement('input'); a.type='hidden'; a.name=`points[${i}][attendance_index]`; a.value=p.attendance_index; form.appendChild(a);
    });
  });

  // Cancel button: redirect ke halaman list MOM
  document.getElementById('cancelFormBtn').addEventListener('click', function() {
    window.location.href = '/notulen';
  });
</script>
@endsection


