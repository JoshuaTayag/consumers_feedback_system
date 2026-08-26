@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Kwh Meter Damage Cause Types Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('kwh-meter-damage-cause-types.create') }}">Create New Record</a>
                  </div>
              </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('kwh-meter-damage-cause-types.index') }}" class="mb-3">
                <div class="row g-2 align-items-end">
                  <div class="col-lg-5 col-md-12">
                    <input
                      type="text"
                      class="form-control"
                      id="name"
                      name="name"
                      placeholder="Enter name"
                      value="{{ request('name') }}">
                  </div>
                  <div class="col-lg-2 col-md-12">
                    <button type="submit" class="btn btn-info">Filter</button>
                    <a href="{{ route('kwh-meter-damage-cause-types.index') }}" class="btn btn-outline-secondary">Clear</a>
                  </div>
                </div>
              </form>
              <table class="table table-bordered">
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($kwhMeterDamageCauseTypes as $key => $kwhMeterDamageCauseType)
                 <tr>
                   <td>{{ $loop->iteration }}</td>
                   <td>{{ $kwhMeterDamageCauseType->name }}</td>
                   <td>{{ $kwhMeterDamageCauseType->description }}</td>
                   <td>{{ $kwhMeterDamageCauseType->status }}</td>
                   <td>
                      <a class="btn btn-primary" href="{{ route('kwh-meter-damage-cause-types.edit',$kwhMeterDamageCauseType->id) }}">Edit</a>
                       {!! Form::open(['method' => 'DELETE','route' => ['kwh-meter-damage-cause-types.destroy', $kwhMeterDamageCauseType->id],'style'=>'display:inline','class'=>'delete-kwh-meter-damage-cause-type-form','data-kwh-meter-damage-cause-type-name'=>$kwhMeterDamageCauseType->name]) !!}
                           {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                       {!! Form::close() !!}
                   </td>
                 </tr>
                @endforeach
               </table>
               <div id="pagination">{{ $kwhMeterDamageCauseTypes->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-kwh-meter-damage-cause-type-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      const kwhMeterDamageCauseTypeName = this.dataset.kwhMeterDamageCauseTypeName || 'this kwh meter damage cause type';

      Swal.fire({
        title: 'Delete Kwh Meter Damage Cause Type?',
        text: `Are you sure you want to delete "${kwhMeterDamageCauseTypeName}"?`,
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