@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Edit MRF</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('material-requisition-form.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          @if($mrf->status == 1 && !$liquidation->first() && Auth::user()->hasRole('CETD'))
            <div class="row mb-4">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Assign MRV</h4>
                  </div>
                  <div class="card-body">
                    {!! Form::open(array('route' => ['mrfLiquidationCreate', $mrf->id],'method'=>'PUT')) !!}
                      <input type="hidden" name="assigning" value="true">
                      <div class="row">
                        <div class="col-lg-4">
                          <div class="mb-2">
                            <label for="project_name" class="form-label mb-1">MRV No.</label>
                            <select class="form-control" id="mrvs" name="mrvs[]" multiple="multiple">
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="mb-2">
                            <label for="project_name" class="form-label mb-1">SERIV No.</label>
                            <select class="form-control" id="serivs" name="serivs[]" style="width: 100%" multiple="multiple">
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="mb-3">
                            <label for="wo_no" class="form-label mb-1">WO No. </label>
                            <input type="text" class="form-control" id="wo_no" name="wo_no" required>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <input type="submit" class="btn btn-primary btn-sm fa fa-trash" value="Assign">
                        </div>
                      </div>
                    {!! Form::close() !!} 
                  </div>
                </div>
              </div>
            </div>
          @endif
          {!! Form::open(array('route' => ['material-requisition-form.update', $mrf->id],'method'=>'PUT')) !!}
          <div class="row">
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header">
                  <h4>Project Information</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="mb-2">
                        <label for="project_name" class="form-label mb-1">Project Name *</label>
                        <input type="text" class="form-control" id="project_name" name="project_name" value="{{$mrf->project_name}}" required @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="mb-3">
                        <label for="requested_by" class="form-label mb-1">Requested By</label>
                          <select id="requested_by" class="form-control" name="requested_by" required @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>
                            @foreach ($users as $user)          
                              <option value="{{ $user->id }}" @selected( $mrf->requested_id == $user->id) id="">
                                {{ $user->name }} | 
                                @if(!empty($user->getRoleNames()))
                                  @foreach($user->getRoleNames() as $v)
                                      <label class="badge bg-secondary">{{ $v }}</label>
                                  @endforeach
                                @endif
                              </option>
                            @endforeach 
                          </select>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="mb-3">
                        <label for="structure" class="form-label mb-1">
                          {{$mrf->status == 0 ? 'Approved By: ': ($mrf->status == 1 || $mrf->status == 2 ? 'Approved Date: ' : 'Disapproved Date: ') }}
                          <span class="text-danger fw-bold">{{ $mrf->approved_by ? date('F d, Y  h:i A', strtotime($mrf->approved_by)) : null }}</span></label>
                          <select id="approved_by" class="form-control" name="approved_by" required @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>
                            @foreach ($users as $user)          
                              <option value="{{ $user->id }}" @selected( $mrf->approved_id == $user->id) id="">
                                {{ $user->name }} | 
                                @if(!empty($user->getRoleNames()))
                                  @foreach($user->getRoleNames() as $v)
                                      <label class="badge bg-secondary">{{ $v }}</label>
                                  @endforeach
                                @endif
                              </option>
                            @endforeach 
                          </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header">
                  <h4>Add Item</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-8">
                      <div class="mb-2">
                        <label for="structure" class="form-label mb-1">Structure</label>
                          @if($mrf->status == 0)
                            <div class="input-group">
                              <select id="structure" class="form-control" name="structure" @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>
                                <option value="">Choose..</option>
                                @foreach ($structures as $structure)          
                                  <option value="{{ $structure['id'] }}" id="">{{ $structure['structure_code'] }}</option>
                                @endforeach 
                              </select>
                              <span class="input-group-addon"><a href="#" id="get_items" name="get_items" class="btn btn-success"><i class="fa fa-plus"></i></a></span>
                            </div>
                          @endif
                      </div>
                    </div>
                    
                    <div class="col-lg-11 pr-3">
                      <div class="mb-2">
                        <label for="project_name" class="form-label mb-1">Item</label>
                        @if($mrf->status == 0)
                          <select class="js-example-basic-multiple form-control" id="item" name="item" @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)></select>
                        @endif
                      </div>
                    </div>
                    <div class="col-lg-1 pl-0 align-bottom" style="margin-left: -14px;">
                      <div class="mb-2">
                        <label for="add_item" class="form-label mb-1">&nbsp;</label><br>
                        @if($mrf->status == 0)
                          <a href="#" id="add_item" name="add_item" class="btn btn-success" @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)><i class="fa fa-plus"></i></a>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h4>Address</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="mb-2">
                        <label for="area" class="form-label mb-1">Area *</label>
                        <select id="area" class="form-control" name="area_id" required @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>
                          <option value="">Choose...</option>
                          @foreach (Config::get('constants.area_id') as $area)          
                            <option value="{{ $area['id'] }}" id="" @selected( $mrf->area_id == $area['id']) >{{ $area['name'] }}</option>
                          @endforeach 
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="mb-3">
                        <label for="district" class="form-label mb-1">District *</label>
                        <select id="district" class="form-control" name="district" required @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>
                            <option value="">Choose...</option>
                            @foreach ($districts as $district)                        
                                <option value="{{ $district->id }}" @selected( $mrf->district_id == $district->id) id="{{ $district->id }}">{{$district->district_name}}</option>
                            @endforeach 
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="mb-3">
                        <label for="municipality" class="form-label mb-1">Municipality *</label>
                        <select id="municipality" class="form-control" name="municipality" @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>
                          <option value="{{ $mrf->municipality_id }}" id="{{ $mrf->municipality_id }}">{{$mrf->municipality->municipality_name }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="mb-3">
                        <label for="barangay" class="form-label mb-1">Barangay</label>
                        <select id="barangay" class="form-control" name="barangay" @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>
                          <option value="{{ $mrf->barangay_id }}" id="{{ $mrf->barangay_id }}">{{$mrf->barangay->barangay_name }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="mb-3">
                        <label for="sitio" class="form-label mb-1">Sitio</label>
                        <input type="text" class="form-control" id="sitio" name="sitio" value="{{$mrf->sitio }}" required @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h4>Items</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="mb-2">
                        <table class="table table-bordered data-table">
                          <tr>
                            <th>Nea Code</th>
                            <th>Description</th>
                            <th>Unit Cost</th>
                            <th>Quantity</th>
                            @if($mrf->status == 2)
                              <th>Liqudated Quantity</th>
                            @endif
                            <th>Existing Cost</th>
                            <th width="20px">Action</th>
                          </tr>
                          <tbody id="show_data">
                            @include('power_house.warehousing.material_requisition_form_get_items')
                          </tbody>
                          </table>
                      </div>
                    </div>
                  

                    <div class="col-lg-12">
                      <div class="mb-2">
                        <label for="remarks" class="form-label mb-1">Remarks </label>
                        <textarea class="form-control" name="remarks" id="exampleFormControlTextarea1" @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>{{$mrf->remarks }}</textarea>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-lg-6">
              <table>
                <tr>
                  <td>
                    <input type="submit" class="btn btn-primary btn-sm fa fa-trash" value="Save" @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>
                    {!! Form::close() !!} 
                  </td>
                  <td>
                    <form method="POST" action="{{ route('material-requisition-form.destroy', $mrf->id) }}">
                      @method('DELETE')
                      @csrf
                      <button class="btn btn-danger btn-sm confirm-button" type="submit" @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)><i class="fa fa-trash"></i> Delete</button>
                    </form>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>$.fn.poshytip={defaults:null}</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js"></script>
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

  $.fn.editable.defaults.mode = 'inline';
  
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': '{{csrf_token()}}'
      }
  }); 

  $('.updateCode').editable({
         url: "{{ route('mrfUpdateItemCode') }}",
         type: 'text',
         pk: 1,
         name: 100,
         title: 'Enter code'
  });

  $('.updateQuantity').editable({
         url: "{{ route('mrfUpdateItemQuantity') }}",
         type: 'text',
         pk: 1,
         name: 100,
         title: 'Enter quantity'
  });

  $('.updateCost').editable({
         url: "{{ route('mrfUpdateItemCost') }}",
         type: 'text',
         pk: 1,
         name: 100,
         title: 'Enter cost'
  });

$(document).ready(function () {

  $('.js-example-basic-multiple').select2();

  $( "#item" ).select2({
      ajax: { 
        url: "{{route('fetchItems')}}",
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
    return data.ItemCode + " | " + data.Description
    }

    function templateSelection(data){
    return data.ItemCode + " | " + data.Description
    }

  $('#get_items').click(function() {
      var structure_id = $('#structure').val();
      // console.log(structure_id);
      $.ajax({
      method: 'POST',
      url: "{{route('mrfUpdateItems')}}",
      data: {
        structure_id: structure_id,
        mrf_id: '{{$mrf->id}}',
        _token: '{{csrf_token()}}'
      },
      success:function(response){
          $("#show_data").html(response);
      }
      });
  });

  $('#add_item').click(function() {
      var item_id = $('#item').val();
      $.ajax({
      method: 'POST',
      url: "{{route('mrfUpdateItem')}}",
      data: {
        item_id: item_id,
        mrf_id: '{{$mrf->id}}',
        _token: '{{csrf_token()}}'
      },
      success:function(response){
          $("#show_data").html(response);
      }
      });
  });
  

  /*------------------------------------------
  --------------------------------------------
  District Dropdown Change Event
  --------------------------------------------
  --------------------------------------------*/
  $('#district').on('change', function () {
      var id = $(this).children(":selected").attr("id");
      $("#municipality").html('');
      $.ajax({
          url: "{{url('api/fetch-municipalities')}}",
          type: "POST",
          data: {
              district_id: id,
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (result) {
              $('#municipality').html('<option value="">-- Select Municipality --</option>');
              
              $.each(result.municipalities, function (key, value) {
                    $("#municipality").append('<option value="' + value
                        .id + '" id="'+ value.id +'">' + value.municipality_name + '</option>');
                });
              $('#barangay').html('<option value="">-- Select Barangay --</option>');
          }
      });
  });

  /*------------------------------------------
  --------------------------------------------
  Municipality Dropdown Change Event
  --------------------------------------------
  --------------------------------------------*/
  $('#municipality').on('change', function () {
      var id = $(this).children(":selected").attr("id");
      $("#barangay").html('');
      console.log(id);
      $.ajax({
          url: "{{url('api/fetch-barangays')}}",
          type: "POST",
          data: {
              municipality_id: id,
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (res) {
              $('#barangay').html('<option value="">-- Select Barangay --</option>');
              $.each(res.barangays, function (key, value) {
                    $("#barangay").append('<option value="' + value
                        .id + '" id="'+ value.id +'">' + value.barangay_name + '</option>');
              });
          }
      });
  });


});

function removeItem(val) {
  $(document).ready(function () {
    $.ajax({
      method: 'POST',
      url: "{{route('mrfDeleteItem')}}",
      data: {
        item_id: val,
        _method:'DELETE',
        mrf_id: '{{$mrf->id}}',
        _token: '{{csrf_token()}}'
      },
      success:function(response){
          $("#show_data").html(response);
      }
    });
  });
}



</script>
@endsection
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css" rel="stylesheet"/>
<style>
  .select2-container .select2-selection--single {
      box-sizing: border-box;
      cursor: pointer;
      display: block;
      height: 37px;
      user-select: none;
      -webkit-user-select: none;
  }

  .input-mini {
    display: block;
    width: 100%;
    height: calc(2.25rem + 2px);
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
  }
  .editable-submit{
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
  }

  .editable-click, a.editable-click, a.editable-click:hover {
    text-decoration: none;
    border-bottom: solid 1px #00cc0a;
  }

</style>
@endsection