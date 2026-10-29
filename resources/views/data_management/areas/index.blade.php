@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Area Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('areas.create') }}"> Create New Area </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Feeders</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($areas as $key => $area)
                 <tr>
                   <td>{{ $loop->iteration }}</td>
                   <td>{{ $area->name }}</td>
                   <td>{{ $area->feeders->pluck('feeder_name')->implode(', ') }}</td>
                   <td>
                      <a class="btn btn-primary" href="{{ route('areas.edit',$area->id) }}">Edit</a>
                       <form method="POST" action="{{ route('areas.destroy', $area->id) }}" style="display:inline" class="delete-area-form" data-area-name="{{ $area->name }}">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="btn btn-danger">Delete</button>
                       </form>
                   </td>
                 </tr>
                @endforeach
               </table>
               <div id="pagination">{{ $areas->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-area-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      const areaName = this.dataset.areaName || 'this area';

      Swal.fire({
        title: 'Delete Area?',
        text: `Are you sure you want to delete "${areaName}"?`,
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