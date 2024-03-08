@foreach ($data as $index => $electrician)               
  <tr class="text-center">
  <th>{{ $electrician->control_number }}</th>
    <th>{{ $electrician->last_name }}, {{$electrician->first_name}}</th>
    <th>
      @php
        if(count($electrician->electrician_addresses) != 0 ){
          echo($electrician->electrician_addresses[0]->district->district_name.', ');
          echo($electrician->electrician_addresses[0]->municipality->municipality_name.', ');
          echo($electrician->electrician_addresses[0]->barangay->barangay_name);
        }
      @endphp
      
      </th>
    <th>{{ $electrician->date_of_birth }}</th>
    <th>{{ $electrician->fb_account ? $electrician->fb_account : 'NONE' }}</th>
    <th>
      @foreach ($electrician->electrician_contact_numbers as $index => $contact) 
        {{$contact->contact_no}} <br>
      @endforeach
      
    </th>
    
    <th>
    <p class="badge rounded-pill bg-{{ $electrician->application_status == 1 ? 'primary' : ($electrician->application_status == 2 ? 'success' : 'danger')  }} p-2"  >{{ $electrician->application_status == 1 ? 'Pending' : ($electrician->application_status == 2 ? 'Approved' : 'Disapproved')  }}</p>
    </th>
    <!-- <th>{{ $electrician->electrician_complaints ? $electrician->electrician_complaints : 'NONE' }}</th> -->
    <th>
      <!-- @if(count($electrician->electrician_complaints) != 0)
        @foreach($electrician->electrician_complaints as $index => $complaint)
        <button class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#viewModal" data-bs-name="{{$complaint}}" data-bs-act="{{Config::get('constants.act_of_misconduct.'.($complaint->act_of_misconduct-1).'.name')}}" data-bs-noc="{{Config::get('constants.nature_of_complaint_barangay_electrician.'.($complaint->nature_of_complaint-1).'.name')}}"><i class="fa fa-eye"></i></button>
        <br>
        @endforeach
      @else
        None
      @endif -->
      @if(count($electrician->electrician_complaints) != 0)
        <a href="{{ route('electricianComplaintView', $electrician->id) }}" target="_blank">{{$electrician->electrician_complaints->count()}}</a>
      @else
        None
      @endif
      
    </th>
    <th>
        <div class="row p-1">
            <div class="col-lg-6 p-1 text-center">
                <form method="POST" action="{{ route('electrician.destroy', $electrician->id) }}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"></i></button>
                </form>
            </div>
            <div class="col-lg-6 p-1 text-center">
                <a href="{{route('electrician.edit', $electrician->id)}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
            </div>
        </div>
    </th>
  </tr>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="complaints" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="complaints"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-header bg-info fs-5">
                        Complaints
                      </div>
                      <div class="card-body text-center">
                        <div class="container text-start">
                          <div class="row">
                            <div class="col">
                              <h4>Complaint No</h4>
                            </div>
                            <div class="col">
                              <h4><span id="complaint_no"></span></h4>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <h4>Name of Complainant</h4>
                            </div>
                            <div class="col">
                              <h4><span id="complainant_name"></span></h4>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <h4>Date of Complaint</h4>
                            </div>
                            <div class="col">
                              <h4><span id="date_of_complaint"></span></h4>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <h4>Nature of Complaint</h4>
                            </div>
                            <div class="col">
                              <h4><span id="nature_of_complaint"></span></h4>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <h4>Act of misconduct</h4>
                            </div>
                            <div class="col">
                              <h4><span id="act_of_misconduct"></span></h4>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <h4>Remarks</h4>
                            </div>
                            <div class="col">
                              <h4><span id="remarks"></span></h4>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div> -->
@endforeach 
@section('script')
<script>
// var viewModal = document.getElementById('viewModal')
//     viewModal.addEventListener('show.bs.modal', function (event) {
//     // Button that triggered the modal
//     var button = event.relatedTarget
//     // Extract info from data-bs-* attributes
//     var data = button.getAttribute('data-bs-name')
//     var act = button.getAttribute('data-bs-act')
//     var noc = button.getAttribute('data-bs-noc')
//     // var wattage = button.getAttribute('data-bs-wattage')
//     // var rate = button.getAttribute('data-bs-rate')
//       var encoded_data = JSON.parse(data);
//       console.log(encoded_data);
//       document.getElementById("complaint_no").innerHTML = encoded_data.control_number;
//       document.getElementById("complainant_name").innerHTML = encoded_data.complainant_name;
//       document.getElementById("date_of_complaint").innerHTML = encoded_data.date;
//       document.getElementById("nature_of_complaint").innerHTML = noc;
//       document.getElementById("act_of_misconduct").innerHTML = act;
//       document.getElementById("remarks").innerHTML = encoded_data.remarks;
//   })
</script>
@endsection
