@extends('layouts.mainLayouts')

@section('navbar_header')
Notulen - <b>{{session('current_project_char')}}</b>
@endsection

@section('header_title')
Daftar Notulen
@endsection

@section('content')
<div class="container-xxl">
  <div class="row justify-content-center">
    <div class="col-md">
      <div class="card">
        <div class="card-body">
          <div class="mb-3">
            <a href="{{ route('viewInputNotulen') }}" class="btn btn-primary">+ Buat Notulen</a>
          </div>
          <table class="table table-bordered table-sm" id="tblNotulen">
            <thead>
              <tr>
                <th>No</th>
                <th>Nomor</th>
                <th>Tema</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rows as $i => $r)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>{{ $r->NOTULEN_TRANS_NOCHAR }}</td>
                  <td>{{ $r->NOTULEN_TRANS_NAME }}</td>
                  <td>{{ $r->NOTULEN_TRANS_DATETIME }}</td>
                  <td>
                    <a href="{{ route('editNotulen', ['id' => $r->NOTULEN_TRANS_ID]) }}" class="btn btn-sm btn-warning">Edit</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


