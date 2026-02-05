@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Change Meter Lead Contractors Management</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('change-meter-lead-contractor.create') }}"> Create New Record </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>No.</th>
                  <th>Full Name</th>
                  <th>Area</th>
                  <th>Municipality</th>
                  <th width="280px">Action</th>
                </tr>
                @foreach ($lead_contractors as $key => $lead_contractor)
                 <tr>
                   <td>{{ $loop->iteration }}</td>
                   <td>{{ $lead_contractor->contractor_team_leader_full_name }}</td>
                   <td>{{ $lead_contractor->area }}</td>
                   <td>{{ $lead_contractor->municipality }}</td>
                   <td>
                      <a class="btn btn-primary" href="{{ route('change-meter-lead-contractor.edit',$lead_contractor->id) }}">Edit</a>
                      <form method="POST" action="{{ route('change-meter-lead-contractor.destroy', $lead_contractor->id) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger">
                      </form>
                   </td>
                 </tr>
                @endforeach
               </table>
               <div id="pagination">{{ $lead_contractors->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection