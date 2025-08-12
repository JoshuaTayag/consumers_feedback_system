@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <span class="mb-0 align-middle fs-3">Electricians </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'electricianMasterlistReportPdf', 'method' => 'GET', 'target' => '_blank']) !!}
                        <div class="row pb-2">
                            <div class="col-lg-12">
                                <label for="district" class="form-label">Electrician *</label>
                                <select id="district" class="form-control" name="district" required>
                                    <option value="">Choose...</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}" id="{{ $district->id }}">
                                            {{ $district->district_name }}</option>
                                    @endforeach
                                    <option value="all" id="">All</option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <label for="date_of_application" class="form-label">Date of Application </label>
                                <div class="d-flex gap-2">
                                    <input type="date" class="form-control" value="{{ old('date_of_application_from') }}"
                                        id="date_of_application_from" name="date_of_application_from">
                                    <span class="align-self-center">to</span>
                                    <input type="date" class="form-control" value="{{ old('date_of_application_to') }}"
                                        id="date_of_application_to" name="date_of_application_to">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label for="application_status" class="form-label mb-1">Application Status *</label>
                                <select name="application_status" id="application_status" class="form-control" required>
                                    <option value="1">Pending</option>
                                    <option value="2">Approved</option>
                                    <option value="3">Disapproved</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-end">
                                <button type="submit" class="btn btn-secondary btn-md text-end">
                                    <i class="fa fa-file-pdf"></i> Generate Report
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .badge {
            line-height: 0.3;
        }
    </style>
@endsection
