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
    @if ($electrician->date_of_application)
      <p class="badge rounded-pill bg-{{ Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($electrician->date_of_application)) <= 330 ? 'success' : (Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($electrician->date_of_application)) >= 366 ? 'danger' : 'warning') }} p-2">
        {{ Carbon\Carbon::parse($electrician->date_of_application)->addYears(1)->format('M d, Y') }}
      </p>
    @else
      <p>Not available</p>
    @endif
    </th>
    <th>
    <p class="badge rounded-pill bg-{{ $electrician->application_status == 1 ? 'primary' : ($electrician->application_status == 2 ? 'success' : 'danger')  }} p-2"  >{{ $electrician->application_status == 1 ? 'Pending' : ($electrician->application_status == 2 ? 'Approved' : 'Disapproved')  }}</p>
    </th>
    <!-- <th>{{ $electrician->electrician_complaints ? $electrician->electrician_complaints : 'NONE' }}</th> -->
    <th>
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
@endforeach 
@section('script')
<script>
  $(document).ready(function () {
    if($('#control_number').val() || $('#first_name').val() || $('#last_name').val()){
        var getFirstName = $('#first_name').val();
        var getLastName = $('#last_name').val();
        var getControlNo = $('#control_number').val();
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            $.ajax({
            method: 'GET',
            url: "{{route('fetchElectricianApplication')}}",
            data: {
                fname:getFirstName,
                lname:getLastName,
                control_number:getControlNo
            },
            success:function(response){
                $("#show_data").html(response);
                $('#pagination').delay(500).fadeOut('fast');
            }
            });
        }, 500);
    }

    var timeout = null;
      $('#control_number').keyup(function() {
        var getFirstName = $('#first_name').val();
        var getLastName = $('#last_name').val();
        var getControlNo = $(this).val();
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            $.ajax({
            method: 'GET',
            url: "{{route('fetchElectricianApplication')}}",
            data: {
                fname:getFirstName,
                lname:getLastName,
                control_number:getControlNo
            },
            success:function(response){
                $("#show_data").html(response);
                $('#pagination').delay(500).fadeOut('fast');
            }
            });
        }, 500);
    });

    $('#first_name').keyup(function() {
        var getFirstName = $(this).val();
        var getLastName = $('#last_name').val();
        var getControlNo = $('#control_number').val();
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            $.ajax({
            method: 'GET',
            url: "{{route('fetchElectricianApplication')}}",
            data: {
                fname:getFirstName,
                lname:getLastName,
                control_number:getControlNo
            },
            success:function(response){
                console.log(response)
                $("#show_data").html(response);
                $('#pagination').delay(500).fadeOut('fast');
            }
            });
        }, 500);
    });

    $('#last_name').keyup(function() {
        var getFirstName = $('#first_name').val();
        var getLastName = $(this).val();
        var getControlNo = $('#control_number').val();
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            $.ajax({
            method: 'GET',
            url: "{{route('fetchElectricianApplication')}}",
            data: {
                fname:getFirstName,
                lname:getLastName,
                control_number:getControlNo
            },
            success:function(response){
                $("#show_data").html(response);
                $('#pagination').delay(500).fadeOut('fast');
            }
            });
        }, 500);
    });
  })
</script>
@endsection
