@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Create Structure</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('structure.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'structure.store','method'=>'POST')) !!}
            <div class="row">
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="structure_code" class="form-label mb-1">Structure Code *</label>
                    <input type="text" class="form-control" id="structure_code" name="structure_code" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="status" class="form-label mb-1">Status *</label>
                  <select id="status" class="form-control" name="status" required>
                    <option value="1" >Active</option>
                    <option value="0" >In Active</option>
                </select>
                </div>
              </div>
        
              {{-- <div class="col-lg-8">
                <div class="mb-2">
                  <label for="items" class="form-label mb-1">Items *</label>
                  <select class="js-example-basic-multiple form-control" id="items" name="items[]" required multiple="multiple">
                  </select>
                </div>
              </div> --}}

              <div class="col-lg-7">
                <div class="mb-2">
                  <label for="project_name" class="form-label mb-1">Item</label>
                  <select class="js-example-basic-multiple form-control" id="item" name="item"></select>
                </div>
              </div>
              <div class="col-lg-1 align-bottom">
                <div class="mb-2">
                  <label for="add_item" class="form-label mb-1">&nbsp;</label><br>
                  <a href="#" id="add_item" name="add_item" class="btn btn-success"><i class="fa fa-plus"></i></a>
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
                      @include('power_house.warehousing.data_management.structure_get_temp_items')
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

  $('.updateCost').editable({
        url: "{{ route('structureUpdateCost') }}",
        type: 'number',
        step: 'any',
        pk: 1,
        name: 100,
        title: 'Enter code'
  });

  $('.updateQuantity').editable({
        url: "{{ route('structureUpdateQuantity') }}",
        type: 'text',
        pk: 1,
        name: 100,
        title: 'Enter code'
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
      return data.id + " | " +data.ItemCode + " | " + data.Description
     }

     function templateSelection(data){
      return data.id + " | " +data.ItemCode + " | " + data.Description
     }

     $('#add_item').click(function() {
      var item_id = $('#item').val();
      console.log(item_id);
      $.ajax({
      method: 'POST',
      url: "{{route('updateStructureItem')}}",
      data: {
        item_id: item_id,
        _token: '{{csrf_token()}}'
      },
      success:function(response){
          $("#show_data").html(response);
      }
      });
  });

  $('#myTab a').on('click', function (e) {
    e.preventDefault()
    $(this).tab('show')

  });
})

function removeItem(val) {
  $(document).ready(function () {
    $.ajax({
      method: 'POST',
      url: "{{route('structureDeleteItem')}}",
      data: {
        item_id: val,
        type: 'create',
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
    /* width: 100%; */
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

  .editable-cancel{
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