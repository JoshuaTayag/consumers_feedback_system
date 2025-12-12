@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">kWh Meter Request</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('kwh-meter-request.create') }}"> Create New Request</a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>No</th>
                  <th>Requested By</th>
                  <th>Purpose</th>
                  <th>Meter Type</th>
                  <th>Quantity</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                @foreach ($kwh_meter_requests as $key => $kwh_meter_request)
                 <tr>
                   <td>{{ $loop->iteration }}</td>
                   <td>{{ $kwh_meter_request->user->name }}</td>
                   <td>{{ $kwh_meter_request->purpose }}</td>
                   <td>{{ $kwh_meter_request->meterType->meter_code }}</td>
                   <td>{{ $kwh_meter_request->quantity }}</td>
                   <td><span class="badge p-2 {{ $kwh_meter_request->is_liquidated ? 'bg-success' : ($kwh_meter_request->approved_at ? 'bg-primary' : 'bg-warning text-dark') }}">{{ $kwh_meter_request->is_liquidated ? 'Liquidated' : ($kwh_meter_request->approved_at ? 'Approved' : 'Pending') }}</span></td>
                   <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                        <a type="button" class="btn btn-outline-warning" 
                                href="{{ route('kwh-meter-request.edit',$kwh_meter_request->id) }}" title="Edit Meter Type">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="confirmDelete({{ $kwh_meter_request->id }}, '{{ $kwh_meter_request->meterType->meter_brand }}')" title="Delete Details">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <form id="delete-form-{{ $kwh_meter_request->id }}" method="POST" action="{{ route('kwh-meter-request.destroy', $kwh_meter_request->id) }}" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                  </td>
                 </tr>
                @endforeach
               </table>
               <div id="pagination">{{ $kwh_meter_requests->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>
function confirmDelete(meterId, meterBrand) {
    Swal.fire({
        title: 'Delete Meter Type?',
        text: `Are you sure you want to delete "${meterBrand}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${meterId}`).submit();
        }
    });
}

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        toast: true,
        position: 'top-end',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endsection