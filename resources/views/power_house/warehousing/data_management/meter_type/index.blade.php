@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">
                                <i class="fas fa-tachometer-alt"></i> Meter Types
                            </h4>
                        </div>
                        <div class="col-md-6 text-end">
                          <a class="btn btn-sm btn-primary" href="{{ route('meter-type.create') }}">Create New Record</a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Meters Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th> Meter Brand</th>
                                    <th> Meter Code</th>
                                    <th> Meter Type</th>
                                    <th> Meter Description</th>
                                    <th> Date Created</th>
                                    <th class="text-center"><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody id="metersTableBody">
                                @forelse($meter_types as $meter)
                                <tr class="meter-row" data-meter-id="{{ $meter->id }}">
                                    <td>
                                        <span class="badge bg-info">{{ $meter->meter_brand }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $meter->meter_code }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $meter->meter_type }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $meter->meter_description }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $meter->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                      <div class="btn-group btn-group-sm" role="group">
                                          <a type="button" class="btn btn-outline-warning" 
                                                  href="{{ route('meter-type.edit',$meter->id) }}" title="Edit Meter Type">
                                              <i class="fas fa-edit"></i>
                                          </a>
                                          <button type="button" class="btn btn-outline-danger" 
                                                  onclick="confirmDelete({{ $meter->id }}, '{{ $meter->meter_brand }}')" title="Delete Details">
                                              <i class="fas fa-trash"></i>
                                          </button>
                                      </div>
                                      <form id="delete-form-{{ $meter->id }}" method="POST" action="{{ route('meter-type.destroy', $meter->id) }}" style="display: none;">
                                          @csrf
                                          @method('DELETE')
                                      </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No meter types found.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $meter_types->links() }}
                    </div>
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