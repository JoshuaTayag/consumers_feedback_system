@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Lifeline </span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('lifeline.create') }}"> Apply Lifeline </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>Control No</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Last Name</th>
                  <th>Address</th>
                  <th>Date Of Birth</th>
                  <th>Account No</th>
                  <th width="280px">Action</th>
                </tr>
                <tbody id="show_data">
                  @include('lifeline.search')
                </tbody>
               </table>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection