@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
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
                        {{$mrf->barangay->barangay_name}} <br>
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
              <div class="col-lg-8 d-flex">
                <div class="card w-100">
                  <div class="card-header bg-info">
                    Remarks
                  </div>
                  <div class="card-body fs-5">
                    {{$mrf->remarks}}
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
                        <th>Nea Code</th>
                        <th>Description</th>
                        <th>Unit Cost</th>
                        <th>Quantity</th>
                        <th>Existing Cost</th>
                        <th width="20px">Action</th>
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
                          <label for="image_path" class="form-label mb-1">Upload Image</label>
                          <input type="file" class="form-control" id="image_path" name="image_path">
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
                              <input type="text" class="form-control" id="mcrt_no" name="mcrt_no" >
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="mb-2">
                              <label for="mst_no" class="form-label mb-1">MST No</label>
                              <input type="text" class="form-control" id="mst_no" name="mst_no">
                            </div>
                          </div>
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
              <div class="col-lg-6">
                <input type="submit" class="btn btn-primary btn-sm fa fa-trash" value="Save">
              {!! Form::close() !!} 
                <form method="POST" action="{{ route('material-requisition-form.destroy', $mrf->id) }}">
                  @method('DELETE')
                  @csrf
                  <button class="btn btn-danger btn-sm confirm-button" type="submit"><i class="fa fa-trash"></i> Delete</button>
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