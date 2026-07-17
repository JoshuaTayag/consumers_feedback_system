@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Municipality Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('municipalities.create') }}"> Create New Municipality </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>No</th>
                  <th>Municipality</th>
                  <th>District</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($municipalities as $key => $municipality)
                 <tr>
                   <td>{{ $loop->iteration }}</td>
                   <td>{{ $municipality->municipality_name }}</td>
                   <td>{{ $municipality->district->district_name }}</td>
                   <td>
                      <a class="btn btn-primary" href="{{ route('municipalities.edit',$municipality->id) }}">Edit</a>
                       {!! Form::open(['method' => 'DELETE','route' => ['municipalities.destroy', $municipality->id],'style'=>'display:inline','class'=>'delete-municipality-form','data-municipality-name'=>$municipality->municipality_name]) !!}
                           {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                       {!! Form::close() !!}
                   </td>
                 </tr>
                @endforeach
               </table>
               <div id="pagination">{{ $municipalities->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-municipality-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      const municipalityName = this.dataset.municipalityName || 'this municipality';

      Swal.fire({
        title: 'Delete Municipality?',
        text: `Are you sure you want to delete "${municipalityName}"?`,
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