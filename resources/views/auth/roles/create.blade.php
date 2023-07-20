@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Create New Role</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
          <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                  <div class="form-group">
                      <strong>Name:</strong>
                      {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Permission:</strong>
                      <br/>
                      <div class="row">
                        <div class="col-lg-3">
                          <input type="checkbox" id="checkAll" > Check All
                        </div>
                        <div class="col-lg-12 py-0 my-0">
                          <hr>
                        </div>
                      </div>
                      <div class="row">
                        @foreach($permission as $value)
                          <div class="col-lg-3">
                            <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                              {{ $value->name }}</label>
                          </div>   
                          <br/>
                          @if ($loop->iteration % 4 == 0) 
                            <div class="col-lg-12 py-0 my-0">
                              <hr>
                            </div>
                          @endif
                        @endforeach
                      </div>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
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
<script>
$('#checkAll').click(function () {    
  $('input:checkbox').prop('checked', this.checked);    
});
</script>
@endsection