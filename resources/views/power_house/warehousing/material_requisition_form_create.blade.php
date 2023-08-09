@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Create MRF</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('structure.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'material-requisition-form.store','method'=>'POST')) !!}
            <div class="row">
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="project_name" class="form-label mb-1">Project Name *</label>
                    <input type="text" class="form-control" id="project_name" name="project_name" required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-2">
                  <label for="project_name" class="form-label mb-1">Item</label>
                  <div class="input-group">
                    <select class="js-example-basic-multiple form-control" id="item" name="item"></select>
                    <span class="input-group-addon"><a href="#" id="add_item" name="add_item" class="btn btn-success"><i class="fa fa-plus"></i></a></span>
                  </div>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="structure" class="form-label mb-1">Structure</label>
                    <div class="input-group">
                      <select id="structure" class="form-control" name="structure" required>
                        @foreach ($structures as $structure)          
                          <option value="{{ $structure['id'] }}" id="">{{ $structure['structure_code'] }}</option>
                        @endforeach 
                      </select>
                      <span class="input-group-addon"><a href="#" id="get_items" name="get_items" class="btn btn-success"><i class="fa fa-plus"></i></a></span>
                    </div>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="mb-2">
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

              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="remarks" class="form-label mb-1">Remarks </label>
                  <textarea class="form-control" name="remarks" id="exampleFormControlTextarea1"></textarea>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-lg-12">
                <input type="submit" class="btn btn-primary" value="Submit">
              </div>
            </div>
          {!! Form::close() !!} 
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
  $.fn.editable.defaults.mode = 'inline';
  
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': '{{csrf_token()}}'
      }
  }); 

  $('.updateCode').editable({
         url: "{{ route('updateItemCode') }}",
         type: 'text',
         pk: 1,
         name: 100,
         title: 'Enter code'
  });

  $('.updateQuantity').editable({
         url: "{{ route('updateItemQuantity') }}",
         type: 'text',
         pk: 1,
         name: 100,
         title: 'Enter quantity'
  });

  $('.updateCost').editable({
         url: "{{ route('updateItemCost') }}",
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
      url: "{{route('updateItems')}}",
      data: {
        structure_id:structure_id,
        _token: '{{csrf_token()}}'
      },
      success:function(response){
          $("#show_data").html(response);
      }
      });
  });

  $('#add_item').click(function() {
      var item_id = $('#item').val();
      console.log(item_id);
      $.ajax({
      method: 'POST',
      url: "{{route('updateItem')}}",
      data: {
        item_id: item_id,
        _token: '{{csrf_token()}}'
      },
      success:function(response){
          $("#show_data").html(response);
      }
      });
  });
  

  // $('#get_items').click(function() {
  //     var structure_id = $('#structure').val();
  //     console.log(structure_id);
  //     $.ajax({
  //     method: 'GET',
  //     url: "{{route('getItems')}}",
  //     data: {
  //       structure_id:structure_id
  //     },
  //     success:function(response){
  //         $("#show_data").html(response);
  //     }
  //     });
  // });

});

function removeItem(val) {
  $(document).ready(function () {
    $.ajax({
      method: 'POST',
      url: "{{route('removeItem')}}",
      data: {
        item_id: val,
        _method:'DELETE',
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