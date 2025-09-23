@extends('layouts.mainLayouts')

@section('navbar_header')
MOM - <b>{{session('current_project_char')}}</b>
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
          <div class="mb-3">
            <a href="{{ route('createNotulen') }}" class="btn btn-primary">CREATE MOM</a>
          </div>
          <table class="table table-bordered table-sm" id="tblNotulen">
            <thead>
              <tr>
                <th>No</th>
                <th>Document</th>
                <th>Topic</th>
                <th>Date</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rows as $i => $r)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>{{ $r->NOTULEN_TRANS_NOCHAR }}</td>
                  <td>{{ $r->NOTULEN_TRANS_NAME }}</td>
                  <td>{{ $r->NOTULEN_TRANS_DATETIME }}</td>
                  <td class="text-center">
                    <a href="{{ route('showNotulen', ['id' => $r->NOTULEN_TRANS_ID]) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('printNotulen', ['id' => $r->NOTULEN_TRANS_ID]) }}" class="btn btn-sm btn-success" target="_blank">Print</a>
                    @php $currentUser = trim(session('first_name') . ' ' . session('last_name')); @endphp
                    @if($r->NOTULEN_TRANS_CREATED_BY == $currentUser)
                      <a href="{{ route('editNotulen', ['id' => $r->NOTULEN_TRANS_ID]) }}" class="btn btn-sm btn-warning">Edit</a>
                      <form action="{{ route('deleteNotulen', ['id' => $r->NOTULEN_TRANS_ID]) }}" method="post" style="display:inline" onsubmit="return confirm('Hapus notulen ini?')">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                      </form>
                    @endif
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


