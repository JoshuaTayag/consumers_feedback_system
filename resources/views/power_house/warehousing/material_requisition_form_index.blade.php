@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Material/Equipment Requisition</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('mrfLiquidationReport') }}" target="_blank"> <i class="fa fa-eye"></i> Liquidation Report </a>
                    @if($unliquidated_mrf < 10)
                      <a class="btn btn-success" href="{{ route('material-requisition-form.create') }}"> Create New Request </a>
                    @endif
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr class="text-center">
                  <th>MER #</th>
                  <th>Project Name</th>
                  <th>Address</th>
                  {{-- <th>Items</th> --}}
                  <th>Requested By</th>
                  <th>Requested Date</th>
                  <th>References</th>
                  <th>Status</th>
                  <th style="min-width: 60px;">Action</th>
                </tr>
                <tbody>
                  @foreach ($mrfs as $index => $mrf)  
                  @php $liquidation = $liquidations->where('material_requisition_form_id', $mrf->id)->count(); @endphp                      
                    <tr class="text-center">
                        <th style="white-space: nowrap;">{{  date('y', strtotime($mrf->created_at)). "-". str_pad($mrf->id,5,'0',STR_PAD_LEFT) }}</th>
                        <th>{{ $mrf->project_name }}</th>
                        <th>{{ $mrf->district->district_name }}, {{ $mrf->barangay->barangay_name }}, {{ $mrf->municipality->municipality_name }}</th>
                        {{-- <th>
                          @foreach ($mrf->items as $index => $mrf_item) 
                            <div class="row">
                              <div class="col text-start">
                                <li>{{$mrf_item->quantity}}  {{ $mrf_item->item->Description }}</li>
                              </div>
                            </div>
                          @endforeach
                        </th> --}}
                        <th>{{ $mrf->requested_name }}</th>
                        <th>{{ date('F d, Y', strtotime($mrf->requested_by)) }}</th>
                        <th style="white-space: nowrap;" >
                          @if(count($mrf->mrf_liquidations) != 0)
                            @foreach($mrf->mrf_liquidations as $index => $mrvs)
                                {{$mrvs->type. '# '.$mrvs->type_number }} <br>
                            @endforeach
                          @else
                            None
                          @endif
                          
                        </th>
                        <th class="badge rounded-pill text-white bg-{{ $mrf->status == 0 ? 'secondary' : ($mrf->status == 1 && $liquidation == 0 ? 'success' : ($mrf->status == 2 && $liquidation != 0 ? 'primary' : ($mrf->status == 3 ? 'warning' : 'danger'))) }}"  >{{ $mrf->status == 0 ? "Pending" : ($mrf->status == 1 && $liquidation == 0 ? 'Approved'  : ($mrf->status == 2 && $liquidation != 0 ? 'Processed' : ($mrf->status == 3 ? 'Liquidated' : "DisApproved"))) }}</th>
                        <th>
                          <!-- {{$liquidations->where('material_requisition_form_id', $mrf->id)->count()}} -->
                          <div class="row p-1">
                            <div class="col">
                              <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                  Action
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                  @if($mrf->status == 0)
                                    @if($mrf->requested_id == auth()->user()->id)
                                      <li><a href="{{route('material-requisition-form.edit', $mrf->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> View</a></li>
                                    @endif
                                    @if($mrf->requested_id == auth()->user()->id)
                                      <li>
                                        <form method="POST" action="{{ route('material-requisition-form.destroy', $mrf->id) }}">
                                          @method('DELETE')
                                          @csrf
                                          <a class="confirm-button dropdown-item" type="submit" style="text-decoration: none;" href="#"><i class="fa fa-trash"></i> Delete</a>
                                        </form>
                                      </li>
                                    @endif
                                  @endif
                                  @if($mrf->status == 1)
                                    @if(Auth::user()->hasRole('CETD') || Auth::user()->hasRole('CETD SPRC'))
                                      <li><a href="{{route('material-requisition-form.edit', $mrf->id)}}" class="dropdown-item"><i class="fa fa-gear"></i> Process</a></li>
                                    @endif
                                    <li><a href="{{route('material-requisition-form.edit', $mrf->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> View</a></li>
                                  @endif
                                  @if($mrf->status == 2)
                                    <!-- <li><a href="{{route('material-requisition-form.edit', $mrf->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> View</a></li> -->
                                    <li><a href="{{ route('mrfPrintPdf', $mrf->id) }}" target="_blank" class="dropdown-item"><i class="fa fa-print"></i> Print MRF</a></li>
                                    
                                    @if($mrf->requested_id == auth()->user()->id)
                                      <li><a href="{{ route('mrfLiquidate', $mrf->id) }}" target="_blank" class="dropdown-item"><i class="fa fa-pencil"></i> Liquidate</a></li>
                                    @endif
                                  @endif
                                  @if($mrf->status == 3)
                                    <li><a href="{{route('viewLiquidatedMrf', $mrf->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> View</a></li>
                                  @endif
                                </ul>
                              </div>
                            </div>
                            {{-- <div class="col-lg-3 p-1 text-center">
                              <a href="{{route('material-requisition-form.edit', $mrf->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i></a>
                            </div>
                            @if($mrf->status == 1)
                              <div class="col-lg-3 p-1 text-center">
                                <a href="{{ route('mrfPrintPdf', $mrf->id) }}" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-print"></i></a>
                              </div>
                              <div class="col-lg-3 p-1 text-center">
                                <a href="{{ route('mrfLiquidate', $mrf->id) }}" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                              </div>
                            @endif
                            <div class="col-lg-3 p-1 text-center">
                              <form method="POST" action="{{ route('material-requisition-form.destroy', $mrf->id) }}">
                                  @method('DELETE')
                                  @csrf
                                  <button class="btn btn-danger btn-sm confirm-button" type="submit"><i class="fa fa-trash"></i></button>
                              </form>
                            </div> --}}
                          </div>
                          
                        </th>
                    </tr>
                  @endforeach
                </tbody>
               </table>
               <div id="pagination">{{ $mrfs->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function () {
  $('.confirm-button').click(function(event) {
    var form =  $(this).closest("form");
    event.preventDefault();
    swal.fire({
        title: `Are you sure you want to delete this row?`,
        text: "It will gone forever",
        icon: "warning",
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed){
            form.submit();
        }
        else{

        }
            
    });
  });
});
</script>
@endsection