@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-5">
                      <span class="mb-0 align-middle fs-3">Electrician Details</span>
                  </div>
                  <div class="col-lg-7">
                      <span class="mb-0 align-middle fs-3">Electrician Complaints</span>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row pb-2">
                <div class="col-lg-5">
                  <div class="card">
                    <div class="card-header p-1 bg-info"></div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col fs-4 border ms-2 rounded">Application No. :</div>
                        <div class="col fs-4 border me-2">{{$electrician->control_number}}</div>
                      </div>
                      <div class="row">
                        <div class="col fs-4 border ms-2">Full Name:</div>
                        <div class="col fs-4 border me-2">{{$electrician->last_name.', '.$electrician->first_name.', '. $electrician->middle_name}}</div>
                      </div>
                      <div class="row">
                        <div class="col fs-4 border ms-2">Address:</div>
                        <div class="col fs-4 border me-2">
                          @php
                            if(count($electrician->electrician_addresses) != 0 ){
                              echo($electrician->electrician_addresses[0]->district->district_name.', ');
                              echo($electrician->electrician_addresses[0]->municipality->municipality_name.', ');
                              echo($electrician->electrician_addresses[0]->barangay->barangay_name);
                            }
                          @endphp
                        </div>
                      </div>
                      <div class="row">
                        <div class="col fs-4 border ms-2">Date of Birth:</div>
                        <div class="col fs-4 border me-2">{{ date('M d, Y', strtotime($electrician->date_of_birth)) }}</div>
                      </div>
                      <div class="row">
                        <div class="col fs-4 border ms-2">Contact Number's:</div>
                        <div class="col fs-4 border me-2">
                          @foreach ($electrician->electrician_contact_numbers as $index => $contact) 
                            {{$contact->contact_no}} <br>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="row">
                    @foreach($electrician->electrician_complaints as $index => $electrician_complaint)
                      <div class="col-lg-12 pb-4">
                        <div class="card">
                          <div class="card-header p-1 bg-{{ $electrician_complaint->act_of_misconduct == 1 ? 'primary' : ($electrician_complaint->act_of_misconduct == 2 ? 'warning' : 'danger')  }}"></div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col fs-5 border ms-2">Complaint No. :</div>
                              <div class="col fs-5 border me-2">{{$electrician_complaint->id}}</div>
                            </div>
                            <div class="row">
                              <div class="col fs-5 border ms-2">Complainant Name:</div>
                              <div class="col fs-5 border me-2">{{$electrician_complaint->complainant_name}}</div>
                            </div>
                            <div class="row">
                              <div class="col fs-5 border ms-2">Date of Complaint:</div>
                              <div class="col fs-5 border me-2">{{ date('M d, Y', strtotime($electrician_complaint->date)) }}</div>
                            </div>
                            <div class="row">
                              <div class="col fs-5 border ms-2">Nature Of Complaint:</div>
                              <div class="col fs-5 border me-2">{{Config::get('constants.nature_of_complaint_barangay_electrician.'.($electrician_complaint->nature_of_complaint-1).'.name')}}</div>
                            </div>
                            <div class="row">
                              <div class="col fs-5 border ms-2">Act of misconduct:</div>
                              <div class="col fs-5 border me-2">
                              <p class="badge rounded-pill bg-{{ $electrician_complaint->act_of_misconduct == 1 ? 'primary' : ($electrician_complaint->act_of_misconduct == 2 ? 'warning' : 'danger')  }} p-2"  >{{ Config::get('constants.act_of_misconduct.'.($electrician_complaint->act_of_misconduct - 1).'.name') }}</p>
                                </div>
                            </div>
                            <div class="row">
                              <div class="col fs-5 border ms-2">Attached File:</div>
                              <div class="col fs-5 border me-2">
                                @if($electrician_complaint->file_path)
                                  <a href="{{ asset($electrician_complaint->file_path) }}" target="_blank" style="text-decoration: none"><i class="fa fa-search"></i> View PDF File</a>
                                @else
                                  None
                                @endif
                              
                              </div>
                            </div>
                            <div class="row">
                              <div class="col fs-5 border ms-2">Remarks:</div>
                              <div class="col fs-5 border me-2">{{$electrician_complaint->remarks}}</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
                
              </div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection
@section('style')
<style>
  .badge{
    line-height: 0.3;
  }
</style>
@endsection