@extends('layouts.app')

@section('style')
<style>
/* Toggle Switch Styles */
.status-switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
  vertical-align: middle;
  margin: 0;
}

.status-switch input[type="checkbox"] {
  opacity: 0 !important;
  width: 0 !important;
  height: 0 !important;
  position: absolute !important;
  pointer-events: none;
}

.status-switch .slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #dc3545;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 34px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
  border: none;
  outline: none;
}

.status-switch .slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 50%;
  box-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.status-switch input[type="checkbox"]:checked + .slider {
  background-color: #28a745 !important;
}

.status-switch input[type="checkbox"]:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

.status-switch input[type="checkbox"]:disabled + .slider {
  opacity: 0.5;
  cursor: not-allowed;
}

.status-switch:hover .slider {
  box-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

/* Badge Styles */
.status-badge {
  font-size: 0.75rem;
  margin-left: 10px;
  padding: 0.25rem 0.5rem;
}

/* Row Styles */
.user-row-inactive {
  background-color: #f8f9fa !important;
  opacity: 0.7;
}

/* Switch Container */
.status-container {
  display: flex;
  align-items: center;
  justify-content: flex-start;
}
</style>
@endsection

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Users Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Roles</th>
                  <th>Status</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($data as $key => $user)
                 <tr class="{{ $user->trashed() ? 'user-row-inactive' : '' }}">
                   <td>{{ $loop->iteration }}</td>
                   <td>{{ $user->name }}</td>
                   <td>{{ $user->email }}</td>
                   <td>
                     @if(!empty($user->getRoleNames()))
                       @foreach($user->getRoleNames() as $v)
                          <label class="badge bg-secondary">{{ $v }}</label>
                       @endforeach
                     @endif
                   </td>
                   <td>
                     <div class="status-container">
                       <label class="status-switch">
                         <input type="checkbox" 
                                {{ !$user->trashed() ? 'checked' : '' }} 
                                onchange="toggleUserStatus({{ $user->id }}, this)"
                                {{ $user->id == auth()->id() ? 'disabled title="Cannot modify your own status"' : '' }}
                                data-user-id="{{ $user->id }}">
                         <span class="slider"></span>
                       </label>
                     </div>
                   </td>
                   <td>
                      <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}">Edit</a>
                      {{-- @if(!$user->trashed() && $user->id != auth()->id())
                       <form method="POST" action="{{ route('userDestroy', $user->id) }}" style="display:inline" onsubmit="return confirm('Are you sure you want to permanently delete this user?')">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                       </form>
                      @elseif($user->id == auth()->id())
                       <span class="text-muted small">Current User</span>
                      @endif --}}
                   </td>
                 </tr>
                @endforeach
               </table>
               <div id="pagination">{{ $data->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>
console.log('User management script loaded');

function toggleUserStatus(userId, switchElement) {
    console.log('Toggle called for user:', userId, 'Current state:', switchElement.checked);
    
    // Get the current status
    const isActive = switchElement.checked;
    const action = isActive ? 'activate' : 'deactivate';
    const actionPast = isActive ? 'activated' : 'deactivated';
    
    // Show confirmation dialog
    Swal.fire({
        title: `${action.charAt(0).toUpperCase() + action.slice(1)} User?`,
        text: `Are you sure you want to ${action} this user?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: isActive ? '#28a745' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${action}!`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with the action
            performStatusToggle(userId, switchElement, isActive);
        } else {
            // User cancelled, revert the switch
            switchElement.checked = !isActive;
        }
    });
}

function performStatusToggle(userId, switchElement, isActive) {
    // Disable the switch while processing
    switchElement.disabled = true;
    
    // Send AJAX request
    fetch(`/users/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the row styling based on new status
            const row = switchElement.closest('tr');
            if (data.status === 'active') {
                row.classList.remove('user-row-inactive');
            } else {
                row.classList.add('user-row-inactive');
            }
            
            // Show success message with SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Status Updated!',
                text: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            
        } else {
            // Revert the switch if there was an error
            switchElement.checked = !isActive;
            
            // Show error message with SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert the switch on error
        switchElement.checked = !isActive;
        
        // Show network error with SweetAlert
        Swal.fire({
            icon: 'error',
            title: 'Network Error!',
            text: 'An error occurred while updating user status. Please try again.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });
    })
    .finally(() => {
        // Re-enable the switch
        switchElement.disabled = false;
    });
}
</script>
@endsection