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
    <th>
        <div class="row p-1">
            <div class="col-lg-6 p-1 text-center">
                <form method="POST" action="{{ route('preMembershipDestroy', $electrician->id) }}">
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
@endforeach 