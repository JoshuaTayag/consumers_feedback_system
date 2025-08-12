@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    @if($mrf->iad_remarks)
      <div class="col-lg-12">
          <div class="alert alert-danger" role="alert">
            <p class="fw-bold">TSD Manager</p>
              Remarks: {{ $mrf->iad_remarks }}
          </div>
      </div>
    @endif

    @if($mrf->warehouse_remarks)
      <div class="col-lg-12">
          <div class="alert alert-danger" role="alert">
            <p class="fw-bold">Richard Samson</p>
              <p>Remarks: {{ $mrf->warehouse_remarks }}</p>
          </div>
      </div>
    @endif

    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Liquidate MRF</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('material-requisition-form.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => ['mrfLiquidationCreate', $mrf->id],'method'=>'PUT', 'enctype' => 'multipart/form-data')) !!}
          @php $liquidation = true; @endphp
            <div class="row">
              <div class="col-lg-4 d-flex">
                <div class="card w-100" style="height:auto;">
                  <div class="card-header bg-info">
                    Project Name
                  </div>
                  <div class="card-body text-left">
                    <span class="fs-5">{{$mrf->project_name}}</span>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="card">
                  <div class="card-header bg-info">
                    Requested By
                  </div>
                  <div class="card-body text-left">
                    <span id="requested_by" class="fs-5">{{ $mrf->requested_name }}</span><br>
                    <span id="requested_by" class="fs-5 fw-bold text-danger">{{ date('F d, Y h:i A', strtotime($mrf->requested_by)) }}</span>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="card">
                  <div class="card-header bg-info">
                    Approved By
                  </div>
                  <div class="card-body text-left">
                    <span id="requested_by" class="fs-5">{{ $mrf->approved_name }}</span><br>
                    <span id="requested_by" class="fs-5 fw-bold text-danger">{{ date('F d, Y h:i A', strtotime($mrf->approved_by)) }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row pt-4">
              <div class="col-lg-4">
                <div class="card">
                  <div class="card-header bg-info">
                    Address
                  </div>
                  <div class="card-body text-left">
                    <div class="row">
                      <div class="col-lg-5 fs-5">
                        District:<br>
                        Municipality:<br>
                        Barangay:<br>
                        Sitio:<br>
                      </div>
                      <div class="col fs-5">
                        {{$mrf->district->district_name}} <br>
                        {{$mrf->municipality->municipality_name}} <br>
                        {{$mrf->barangay ? $mrf->barangay->barangay_name : null}} <br>
                        {{$mrf->sitio}} <br>
                      </div>
                    </div>
                    {{-- <span id="address" class="fs-5">
                      District: {{$mrf->district->district_name}} <br>
                      Municipality: {{$mrf->district->district_name}} <br>
                      Barangay: {{$mrf->district->district_name}} <br>
                      Sitio: {{$mrf->district->district_name}} <br>
                    </span> --}}
                  </div>
                </div>
              </div>
              <div class="col-lg-4 d-flex">
                <div class="card w-100" style="height:auto;">
                  <div class="card-header bg-info">
                    References
                  </div>
                  <div class="card-body text-left">
                    @if(count($mrf->mrf_liquidations) != 0)
                      @foreach($mrf->mrf_liquidations as $index => $mrvs)
                          <div class="row">
                            <div class="col-lg-12 fs-5 fw-bold">
                              {{$mrvs->type. '# '.$mrvs->type_number }}
                            </div>
                          </div>
                      @endforeach
                    @else
                      None
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-lg-4 d-flex">
                <div class="card w-100">
                  <div class="card-header bg-info">
                    Remarks
                  </div>
                  <div class="card-body fs-5">
                    <textarea name="remarks" id="remarks" class="form-control" disabled>{{$mrf->remarks}}</textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="row pt-4">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header bg-info">
                    Items
                  </div>
                  <div class="card-body">
                    <table class="table table-bordered data-table">
                      <tr>
                        <th>No.</th>
                        <th>Nea Code</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Existing Cost</th>
                        <th>Qty Req</th>
                        <th>Qty Used</th>
                      </tr>
                      <tbody id="show_data">
                        @include('power_house.warehousing.material_requisition_form_get_items')
                      </tbody>
                      </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="row pt-4">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header bg-info">
                    Action
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="date_acted" class="form-label mb-1">Date Acted *</label>
                          <input type="date" class="form-control" id="date_acted" name="date_acted" required>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="date_finished" class="form-label mb-1">Date Finished *</label>
                          <input type="date" class="form-control" id="date_finished" name="date_finished" required>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="lineman" class="form-label mb-1">Lineman/s *</label>
                          <input type="text" class="form-control" id="lineman" name="lineman" required>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="image_path" class="form-label mb-1">Upload Image (Maximum of 3 images only) *</label>
                          <input type="file" class="form-control" id="image_path" name="image_path[]" multiple required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-9">
                        <div class="mb-2">
                          <label for="remarks" class="form-label mb-1">Remarks</label>
                          <textarea class="form-control" name="remarks" id="exampleFormControlTextarea1" style="width: 100% !important;" >{{old('remarks')}}</textarea>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="mb-2">
                              <label for="mcrt_no" class="form-label mb-1">MCRT No</label>
                              <!-- <input type="text" class="form-control" id="mcrt_no" name="mcrt_no" required> -->
                              <select id="mcrt_no" class="form-control" name="mcrt_no">
                                <option value="">-------</option>
                                @foreach ($mcrts as $mcrt)          
                                  <option value="{{ $mcrt->mcrt_number }}" id="{{ $mcrt->mcrt_number }}">{{ $mcrt->mcrt_number }}</option>
                                @endforeach 
                              </select>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="mb-2">
                              <label for="mst_no" class="form-label mb-1">MST No</label>
                              <!-- <input type="text" class="form-control" id="mst_no" name="mst_no" required> -->
                              <select id="mst_no" class="form-control" name="mst_no">
                                <option value="">-------</option>
                                @foreach ($msts as $mst)          
                                  <option value="{{ $mst->mst_number }}" id="{{ $mst->mst_number }}">{{ $mst->mst_number }}</option>
                                @endforeach 
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row pt-4 d-none" id="mcrt_details">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header bg-info">
                    MCRT Details
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="mcrt_number" class="form-label mb-1">MCRT NO</label>
                          <input type="text" class="form-control" id="mcrt_number" name="mcrt_number" disabled>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="mcrt_date" class="form-label mb-1">MCRT Date</label>
                          <input type="text" class="form-control" id="mcrt_date" name="mcrt_date" disabled>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="mcrt_returned" class="form-label mb-1">Returned By:</label>
                          <input type="text" class="form-control" id="mcrt_returned" name="mcrt_returned" disabled>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="mcrt_note" class="form-label mb-1">Note</label>
                          <input type="text" class="form-control" id="mcrt_note" name="mcrt_note" disabled>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="mb-2">
                          <table class="table table-stripped" id="mcrt_table">
                            <thead>
                              <tr>
                                <th>Code No</th>
                                <th>Item Description</th>
                                <th>Quantity</th>
                              </tr>
                            </thead>
                            <tbody>

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row pt-4 d-none" id="mst_details">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header bg-info">
                    MST Details
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="mst_number" class="form-label mb-1">MST NO</label>
                          <input type="text" class="form-control" id="mst_number" name="mst_number" disabled>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="mst_date" class="form-label mb-1">MST Date</label>
                          <input type="text" class="form-control" id="mst_date" name="mst_date" disabled>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="mst_returned" class="form-label mb-1">Returned By:</label>
                          <input type="text" class="form-control" id="mst_returned" name="mst_returned" disabled>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="mst_note" class="form-label mb-1">Note</label>
                          <input type="text" class="form-control" id="mst_note" name="mst_note" disabled>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="mb-2">
                          <table class="table table-stripped" id="mst_table">
                            <thead>
                              <tr>
                                <th>Code No</th>
                                <th>Item Description</th>
                                <th>Quantity</th>
                              </tr>
                            </thead>
                            <tbody>

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- <div class="row pt-4">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header bg-info">
                    Liquidate
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="project_name" class="form-label mb-1">MRV No.</label>
                          {{-- <input type="text" class="form-control" id="project_name" name="project_name" required> --}}
                          <select class="form-control" id="mrvs" name="mrvs[]" style="width: 100%" required multiple="multiple">
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div class="mb-2">
                          <label for="project_name" class="form-label mb-1">SERIV No.</label>
                          {{-- <input type="text" class="form-control" id="project_name" name="project_name" required> --}}
                          <select class="form-control" id="serivs" name="serivs[]" style="width: 100%" required multiple="multiple">
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-2">
                        <div class="mb-2">
                          <label for="wo_no" class="form-label mb-1">WO No. </label>
                          <input type="text" class="form-control" id="wo_no" name="wo_no">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="row">
              <div class="col-lg-12 mt-2 text-end">
                <input type="submit" class="btn btn-primary btn-sm fa fa-trash" value="Save">
              {!! Form::close() !!} 
                <form method="POST" action="{{ route('material-requisition-form.destroy', $mrf->id) }}">
                  @method('DELETE')
                  @csrf
                  <!-- <button class="btn btn-danger btn-sm confirm-button" type="submit"><i class="fa fa-trash"></i> Delete</button> -->
                </form>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

  $( "#mrvs" ).select2({
    ajax: { 
      url: "{{route('fetchMrvs')}}",
      type: "get",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
            // _token: '{{csrf_token()}}',
            search: params.term, // search term
            page: params.page
        };
      },
      processResults:function (results, params){
        params.page = params.page||1;
        return{
          results:results.data,
          pagination:{
            more:results.last_page!=params.page
          },
        }
      },
      cache: true
    },
    // placeholder:'Search Account Number',
    templateResult: templateResult,
    templateSelection: templateSelection,
  });

  $( "#serivs" ).select2({
    ajax: { 
      url: "{{route('fetchSerivs')}}",
      type: "get",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
            // _token: '{{csrf_token()}}',
            search: params.term, // search term
            page: params.page
        };
      },
      processResults:function (results, params){
        params.page = params.page||1;
        return{
          results:results.data,
          pagination:{
            more:results.last_page!=params.page
          },
        }
      },
      cache: true
    },
    // placeholder:'Search Account Number',
    templateResult: templateResult,
    templateSelection: templateSelection,
  });
  
  function templateResult(data){
  if (data.loading){
    return data.text
  }
  return  data.id
  }

  function templateSelection(data){
    return data.id
  }

  $('#mcrt_no').on('change', function () {
      var mcrt_no = $(this).children(":selected").attr("id");
      // console.log(mcrt_no);
      $.ajax({
          url: "{{ url('api/fetch-mcrt') }}/" + mcrt_no,
          type: "GET",
          data: {
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (result) {
            if (result.length > 0) {
              $('#mcrt_details').removeClass('d-none');

              $('#mcrt_number').val(result[0].MCRTNo);
              $('#mcrt_date').val(result[0].MCRTDate);
              $('#mcrt_returned').val(result[0].ReturnedBy);
              $('#mcrt_note').val(result[0].Note);

              // Clear any existing rows
              $('#mcrt_table tbody').empty();

              // Populate the table with the returned data
              result.forEach(function(item) {
                  var row = '<tr>' +
                      '<td>' + item.CodeNo + '</td>' +
                      '<td>' + item.Description + '</td>' +
                      '<td>' + parseInt(item.MCRTQty) + '</td>' +
                      '</tr>';
                  $('#mcrt_table tbody').append(row);
              });
            }
          }
      });
  });

  $('#mst_no').on('change', function () {
      var mst_no = $(this).children(":selected").attr("id");
      // console.log(mcrt_no);
      $.ajax({
          url: "{{ url('api/fetch-mst') }}/" + mst_no,
          type: "GET",
          data: {
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (result) {
            if (result.length > 0) {
              $('#mst_details').removeClass('d-none');

              $('#mst_number').val(result[0].MSTNo);
              $('#mst_date').val(result[0].MSTDate);
              $('#mst_returned').val(result[0].ReturnedBy);
              $('#mst_note').val(result[0].Note);

              // Clear any existing rows
              $('#mst_table tbody').empty();

              // Populate the table with the returned data
              result.forEach(function(item) {
                  var row = '<tr>' +
                      '<td>' + item.CodeNo + '</td>' +
                      '<td>' + item.Description + '</td>' +
                      '<td>' + parseInt(item.MSTQty) + '</td>' +
                      '</tr>';
                  $('#mst_table tbody').append(row);
              });
            }
          }
      });
  });
</script>
@endsection
@section('style')
<style>
  .select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 36px;
    user-select: none;
    -webkit-user-select: none;
  }
</style>