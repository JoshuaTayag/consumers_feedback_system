@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Change Meter Contractors Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('change-meter-contractor.create') }}"> Create New Record </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>No.</th>
                  <th>Name</th>
                  <th>Address</th>
                  <th>Mobile No.</th>
                  <th>Account</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($contractors as $key => $contractor)
                 <tr>
                   <td>{{ $loop->iteration }}</td>
                   <td>{{ $contractor->full_name }}</td>
                   <td>{{ $contractor->address }}</td>
                   <td>{{ $contractor->mobile_number }}</td>
                   <td>{{ $contractor->email_account }}</td>
                   <td>
                      <a class="btn btn-primary" href="{{ route('change-meter-contractor.edit',$contractor->id) }}">Edit</a>
                      <form method="POST" action="{{ route('change-meter-contractor.destroy', $contractor->id) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger">
                      </form>
                   </td>
                 </tr>
                @endforeach
               </table>
               <div id="pagination">{{ $contractors->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection