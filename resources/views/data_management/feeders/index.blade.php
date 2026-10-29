@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Feeder Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('feeders.create') }}"> Create New Feeder </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>Area</th>
                  <th>Feeder</th>
                  <th>Coverage</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($feeders as $key => $feeder)
                 <tr>
                   <td class="text-nowrap">{{ $feeder->area->name }}</td>
                   <td>{{ $feeder->feeder_name }}</td>
                   <td>{{ $feeder->coverage_name }}</td>
                   <td>
                      <a class="btn btn-primary" href="{{ route('feeders.edit',$feeder->id) }}">Edit</a>
                       {!! Form::open(['method' => 'DELETE','route' => ['feeders.destroy', $feeder->id],'style'=>'display:inline','class'=>'delete-feeder-form','data-feeder-name'=>$feeder->name]) !!}
                           {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                       {!! Form::close() !!}
                   </td>
                 </tr>
                @endforeach
               </table>
               <div id="pagination">{{ $feeders->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-feeder-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      const feederName = this.dataset.feederName || 'this feeder';

      Swal.fire({
        title: 'Delete Feeder?',
        text: `Are you sure you want to delete "${feederName}"?`,
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