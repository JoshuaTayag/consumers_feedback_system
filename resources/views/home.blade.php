@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(Auth::user()->hasRole('ISD Manager'))
                <a href="{{ route('approvedLifelineIndex') }}"><span>lifeline applicants are pending</span></a>
            @elseif(Auth::user()->hasRole('TSD Manager'))
                <a href="{{ route('mrfApprovalIndex') }}" type="button" class="btn btn-outline-warning mb-4" ><span>Pending MER <i class="fa fa-arrow-right" > </i></span></a>
            @endif
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                @if(Auth::user()->hasRole('Consumer'))
                    @include('consumer.dashboard')
                @else
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-lg-3">
                            <a type="button" class="btn btn-outline-warning" style="border: 3px solid black;" href="{{ route('membership.index') }}">
                                <img src="{{asset('images/icons/membership-card.png')}}" class="img-fluid pt-2" style="max-width:40%;" alt="...">
                                <p class="fs-5 bg-secondary pt-1 mt-2 fw-bold">MEMBERSHIP</p>
                            </a>
                        </div>
                        <div class="col-lg-3">
                            <a type="button" class="btn btn-outline-warning" style="border: 3px solid black;" href="{{ route('pre_membership_index') }}">
                                <img src="{{asset('images/icons/membership-card.png')}}" class="img-fluid pt-2" style="max-width:40%;" alt="...">
                                <p class="fs-5 bg-secondary pt-1 mt-2 fw-bold">PRE-MEMBERSHIP</p>
                            </a>
                        </div>
                        <div class="col-lg-3">
                            <a type="button" class="btn btn-outline-warning" style="border: 3px solid black;" href="{{ route('lifeline.index') }}">
                                <img src="{{asset('images/icons/lifeline.png')}}" class="img-fluid pt-2" style="max-width:40%;" alt="...">
                                <p class="fs-5 bg-secondary pt-1 mt-2 fw-bold">LIFELINE</p>
                            </a>
                        </div>
                        @if(Auth::user()->hasRole('Admin'))
                        <div class="col-lg-3">
                            <a type="button" class="btn btn-outline-warning" style="border: 3px solid black;" href="{{ route('surveyReport') }}">
                                <img src="{{asset('images/icons/notes.png')}}" class="img-fluid pt-2" style="max-width:40%;" alt="...">
                                <p class="fs-5 bg-secondary pt-1 mt-2 fw-bold">CONSUMER SURVEY REPORT</p>
                            </a>
                        </div>
                        @endif
                        <!-- <div class="col-lg-3">
                            <button type="button" class="btn btn-outline-warning">
                                <img src="{{asset('images/icons/notes.png')}}" class="img-fluid" style="max-width:40%;" alt="...">
                                <p class="fs-5">PRE-MEMBERSHIP</p>
                            </button>
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-outline-warning">
                                <img src="{{asset('images/icons/notes.png')}}" class="img-fluid" style="max-width:40%;" alt="...">
                                <p class="fs-5">PRE-MEMBERSHIP</p>
                            </button>
                        </div> -->
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
