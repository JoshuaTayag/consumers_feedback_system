@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Barangays Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('barangays.create') }}"> Create New Barangay </a>
                    <a class="btn btn-success" href="{{ route('barangays.exportCsv') }}"> Export to CSV </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('barangays.index') }}" class="mb-3">
                <div class="row g-2 align-items-end">
                  <div class="col-lg-5 col-md-12">
                    <input
                      type="text"
                      class="form-control"
                      id="barangay"
                      name="barangay"
                      placeholder="Enter barangay name"
                      value="{{ request('barangay') }}">
                  </div>
                  <div class="col-lg-2 col-md-12">
                    <button type="submit" class="btn btn-info">Filter</button>
                    <a href="{{ route('barangays.index') }}" class="btn btn-outline-secondary">Clear</a>
                  </div>
                </div>
              </form>
              <table class="table table-bordered">
                <tr>
                  <th>No</th>
                  <th>Barangay</th>
                  <th>Municipality</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($barangays as $key => $barangay)
                 <tr>
                   <td>{{ $loop->iteration }}</td>
                   <td>{{ $barangay->barangay_name }}</td>
                   <td>{{ $barangay->municipality->municipality_name }}</td>
                   <td>
                      <a class="btn btn-primary" href="{{ route('barangays.edit',$barangay->id) }}">Edit</a>
                       {!! Form::open(['method' => 'DELETE','route' => ['barangays.destroy', $barangay->id],'style'=>'display:inline','class'=>'delete-barangay-form','data-barangay-name'=>$barangay->barangay_name]) !!}
                           {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                       {!! Form::close() !!}
                   </td>
                 </tr>
                @endforeach
               </table>
               <div id="pagination">{{ $barangays->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-barangay-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      const barangayName = this.dataset.barangayName || 'this barangay';

      Swal.fire({
        title: 'Delete Barangay?',
        text: `Are you sure you want to delete "${barangayName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  });
});
</script>
@endsection