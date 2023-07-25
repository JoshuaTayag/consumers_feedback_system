@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Service Connect Order</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    @can('service-connect-order-create')
                      <a class="btn btn-success" href="{{ route('service-connect-order.create') }}"> Create New SCO </a>
                    @endcan
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>SCO No</th>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Spouse</th>
                  <th>Address</th>
                  <th>Meter No.</th>
                  <th>Date Installed</th>
                  <th width="280px">Action</th>
                </tr>
                  @foreach ($scos as $key => $sco)
                  <tr>
                      <td>{{ $sco->SCONo }}</td>
                      <td>{{ $sco->Lastname }}</td>
                      <td>{{ $sco->Firstname }}</td>
                      <td>{{ $sco->{'CO spouse'} }}</td>
                      <td>{{ $sco->Address }}</td>
                      <td>{{ $sco->MeterNo }}</td>
                      
                      <td>{{ $sco->{'Date Installed'} }}</td>
                      <td>
                          <a class="btn btn-info" href="{{ route('service-connect-order.show', $sco->ID) }}">Show</a>
                          @can('service-connect-order-edit')
                              <a class="btn btn-primary" href="{{ route('roles.edit',$sco->ID) }}">Edit</a>
                          @endcan
                          @can('service-connect-order-delete')
                              {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $sco->ID],'style'=>'display:inline']) !!}
                                  {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                              {!! Form::close() !!}
                          @endcan
                      </td>
                  </tr>
                  @endforeach
              </table>
              <div id="pagination">{{ $scos->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection