@extends('layouts.mainLayouts')

@section('navbar_header')
Minutes Of Meeting - <b>{{ $header->NOTULEN_TRANS_NOCHAR }}</b>
@endsection

@section('header_title')
Detail Minutes Of Meeting
@endsection

@section('content')
<div class="container-xxl">
  <div class="row justify-content-center">
    <div class="col-md">
      <div class="card">
        <div class="card-body">
          <div class="mb-3">
            <a href="{{ url('/notulen') }}" class="btn btn-secondary">Back</a>
          </div>
          <div class="form-group">
            <label>Document</label>
            <input type="text" class="form-control" value="{{ $header->NOTULEN_TRANS_NOCHAR }}" readonly>
          </div>
          <div class="form-group">
            <label>Title</label>
            <input type="text" class="form-control" value="{{ $header->NOTULEN_TRANS_NAME }}" readonly>
          </div>
          <div class="form-group">
            <label>Date & Time</label>
            <input type="text" class="form-control" value="{{ $header->NOTULEN_TRANS_DATETIME }}" readonly>
          </div>

          <h5 class="mt-4">Attendance</h5>
          <ul class="list-group">
            @foreach($attendance as $a)
            <li class="list-group-item">{{ $a->NOTULEN_ATTENDANCE_NAME }} @if($a->NOTULEN_ATTENDANCE_LEVEL) ({{ $a->NOTULEN_ATTENDANCE_LEVEL }}) @endif @if($a->NOTULEN_ATTENDANCE_EMAIL) - {{ $a->NOTULEN_ATTENDANCE_EMAIL }} @endif</li>
            @endforeach
          </ul>

          <h5 class="mt-4">Point Meeting</h5>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>No</th>
                <th>Topic</th>
                <th>Point</th>
                <th>PIC</th>
              </tr>
            </thead>
            <tbody>
              @foreach($details as $i => $d)
                <?php $pic = $attendance->firstWhere('NOTULEN_ATTENDANCE_ID', $d->NOTULEN_ATTENDANCE_ID); ?>
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>{{ $d->NOTULEN_DETAIL_TITLE }}</td>
                  <td>{!! $d->NOTULEN_DETAIL_DESC !!}</td>
                  <td>{{ $pic ? $pic->NOTULEN_ATTENDANCE_NAME : '' }}</td>
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


