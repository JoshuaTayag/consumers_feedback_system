@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row align-items-stretch mb-4">
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header fw-bold fs-5">
          PROJECT DETAILS
        </div>
        <div class="card-body fs-5">
          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>Project Name</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
              {{ $mrf->project_name }}
            </div>
          </div>

          <hr class="my-1">

          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>Area</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
              {{Config::get('constants.area_id.'.($mrf->district_id).'.name')}}
            </div>
          </div>

          <hr class="my-1">

          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>Sitio</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
            {{ $mrf->sitio }}
            </div>
          </div>

          <hr class="my-1">

          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>Barangay</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
              {{ $mrf->barangay ? $mrf->barangay->barangay_name : 'None' }}
            </div>
          </div>

          <hr class="my-1">

          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>Municipality</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
              {{ $mrf->municipality->municipality_name }}
            </div>
          </div>

          <hr class="my-1">

          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>Date Acted</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
              {{  date('F d, Y', strtotime($mrf->date_acted)) }} - {{  date('F d, Y', strtotime($mrf->date_finished)) }}
            </div>
          </div>

          <hr class="my-1">

          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>Project remarks</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
              <textarea name="mrf_remarks" id="mrf_remarks" class="form-control" disabled>{{$mrf->remarks}}</textarea>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header fw-bold fs-5">
          OTHER DETAILS
        </div>
        <div class="card-body fs-5">
          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>Requested By</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
              {{ $mrf->requested_name }} <span class="text-danger"> ({{ date('F d, Y h:i A', strtotime($mrf->requested_by)) }})</span>
            </div>
          </div>

          <hr class="my-1">

          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>Approved By</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
              {{ $mrf->user_approved->name }} <span class="text-danger"> ({{ date('F d, Y h:i A', strtotime($mrf->approved_by)) }})</span>
            </div>
          </div>

          <hr class="my-1">

          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>References</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
              @foreach ($mrf->mrf_liquidations as $liquidations)    
                <p class="fw-bold mb-0">{{ $liquidations->type }}# {{ $liquidations->type_number }}</p>
              @endforeach
            </div>
          </div>

          <hr class="my-1">

          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>Work Order No.</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
              {{ $mrf->with_wo }}
            </div>
          </div>

          <hr class="my-1">

          <div class="row">
            <div class="col-lg-4">
              <div class="d-flex justify-content-between">
                  <span>Liquidation remarks</span><span class="text-end">:</span>
              </div>
            </div>
            <div class="col-lg-8 fw-bold">
              <textarea name="cetd_remarks" id="cetd_remarks" class="form-control" disabled>{{$mrf->liquidation_remarks}}</textarea>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="row justify-content-center mb-4">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header fs-5 fw-bold">REQUESTED ITEMS</div>
        <div class="card-body">
          <table class="table table-striped table-hover fw-bold">
            <thead class="bg-dark">
              <tr>
                <th>NEA CODE</th>
                <th>DESCRIPTION</th>
                <th>COST</th>
                <th>QTY REQUESTED</th>
                <th>QTY USED</th>
                <th>QTY TO RETURN</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($mrf->items as $item)  
              <tr>
                <td>{{ $item->nea_code }}</td>
                <td>{{ $item->item->Description }}</td>
                <td>{{ number_format($item->unit_cost, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->liquidation_quantity }}</td>
                <td class="text-{{$item->quantity-$item->liquidation_quantity == 0 ? 'success' : 'danger'}}">{{ ($item->quantity-$item->liquidation_quantity) }}</td>
              </tr>
              @endforeach
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="row align-items-stretch mb-4">

  <div class="row align-items-stretch mb-4">
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header fw-bold fs-5">
          MCRT DETAILS
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="row mb-3">
                <div class="col-lg-4">
                  <label for="mcrt_no" class="fw-bold" >MCRT #</label>
                  <input type="text" name="mcrt_no" class="form-control" id="mcrt_no" value="{{ $mcrt_detail->MCRTNo }}" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="mcrt_no" class="fw-bold" >MCRT DATE</label>
                  <input type="text" name="mcrt_no" class="form-control" id="mcrt_no" value="{{ date('m/d/Y', strtotime($mcrt_detail->MCRTDate)) }}" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="mcrt_no" class="fw-bold" >RETURNED</label>
                  <input type="text" name="mcrt_no" class="form-control" id="mcrt_no" value="{{ $mcrt_detail->ReturnedBy }}" readonly>
                </div>
                <div class="col">
                  <label for="mcrt_no" class="fw-bold" >Note</label>
                  <input type="text" name="mcrt_no" class="form-control" id="mcrt_no" value="{{ $mcrt_detail->Note }}" readonly>
                </div>
              </div>
              <table class="table table-striped table-hover fw-bold">
                <thead class="bg-dark">
                  <tr>
                    <th>NEA CODE</th>
                    <th>ITEM DESCRIPTION</th>
                    <th>QUANTITY</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($mrf->mcrt_items as $item)  
                    <tr>
                      <td>{{ $item->CodeNo }}</td>
                      <td>{{ $item->stock_item ? $item->stock_item->Description : "" }}</td>
                      <td>{{ (int) $item->MCRTQty }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header fw-bold fs-5">
          MST DETAILS
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div class="row mb-3">
                <div class="col-lg-4">
                  <label for="mcrt_no" class="fw-bold" >MST #</label>
                  <input type="text" name="mcrt_no" class="form-control" id="mcrt_no" value="{{ $mst_detail->MSTNo }}" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="mcrt_no" class="fw-bold" >MST DATE</label>
                  <input type="text" name="mcrt_no" class="form-control" id="mcrt_no" value="{{ date('m/d/Y', strtotime($mst_detail->MSTDate)) }}" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="mcrt_no" class="fw-bold" >RETURNED</label>
                  <input type="text" name="mcrt_no" class="form-control" id="mcrt_no" value="{{ $mst_detail->ReturnedBy }}" readonly>
                </div>
                <div class="col">
                  <label for="mcrt_no" class="fw-bold" >Note</label>
                  <input type="text" name="mcrt_no" class="form-control" id="mcrt_no" value="{{ $mst_detail->Note }}" readonly>
                </div>
              </div>
              <table class="table table-striped table-hover fw-bold">
                <thead class="bg-dark">
                  <tr>
                    <th>NEA CODE</th>
                    <th>ITEM DESCRIPTION</th>
                    <th>QUANTITY</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($mrf->mst_items as $item)  
                    <tr>
                      <td>{{ $item->CodeNo }}</td>
                      <td>{{ $item->stock_item ? $item->stock_item->Description : "" }}</td>
                      <td>{{ (int) $item->MCRTQty }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row align-items-stretch mb-4">
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header fw-bold fs-5">
          IMAGES (BEFORE)
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div id="carouselBefore" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                  @foreach($mrf->image_name->where('type', 'BEFORE') as $key => $image)
                    <div class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}">
                      <img src="{{ asset($image->image_path)}}" class="d-block w-100" alt="...">
                    </div>
                  @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselBefore" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselBefore" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
                
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header fw-bold fs-5">
          IMAGES (AFTER)
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <div id="carouselAfter" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                  @foreach($mrf->image_name->where('type', "AFTER") as $key => $image)
                    <div class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}">
                      <img src="{{ asset($image->image_path)}}" class="d-block w-100" alt="...">
                    </div>
                  @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselAfter" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselAfter" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
                
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="row mb-4">
      <div class="col-lg-12 text-end">
          <div class="d-inline-block">
            @if(Auth::user()->hasRole('IAD') || Auth::user()->hasRole('Warehouse'))
              <button class="btn btn-danger btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#disApproved">
                <i class="fas fa-times"></i> DISAPPROVED
              </button>
            @endif
          </div>

          <div class="d-inline-block">
              <form method="POST" action="{{ route('mrfLiquidationApprovalUpdate', $mrf->id) }}">
                  @method('PUT')
                  @csrf
                  <button class="btn btn-success btn-sm fw-bold" type="submit">
                      <i class="fas fa-check"></i> APPROVED
                  </button>
              </form>
          </div>
      </div>
  </div>

  <div class="modal fade" id="disApproved" tabindex="-1" aria-labelledby="disApprovedLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="disApprovedLabel">DISAPPROVED</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
          <form method="POST" action="{{ route('mrfLiquidationIadDisapproved', $mrf->id) }}">
            @method('PUT')
            @csrf
            <div class="modal-body">
              <div class="alert alert-danger py-2" role="alert">
                Remarks are required for disapproving.
              </div>

              <div class="row">
                <div class="col">
                  <textarea name="remarks" id="remarks" class="form-control"></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="d-inline-block">
                  <button type="button" class="btn btn-secondary btn-sm fw-bold" data-bs-dismiss="modal">Close</button>
              </div>

              <div class="d-inline-block">
                  
                      <button class="btn btn-danger btn-sm fw-bold" type="submit">
                          <i class="fas fa-times"></i> DISAPPROVED
                      </button>
                  
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>

</div>
@endsection