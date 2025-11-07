@foreach ($data as $index => $complaint)               
  <tr class="text-center">
  <th>{{ $complaint->control_number }}</th>
    <th>{{ $complaint->date }}</th>
    <th>{{ $complaint->complainant_name }}</th>
    <th>{{ $complaint->electrician->first_name. ', ' . $complaint->electrician->last_name}}</th>
    <th>{{ $complaint->nature_of_complaint ? Config::get('constants.nature_of_complaint_barangay_electrician.'.($complaint->nature_of_complaint - 1).'.name') : $complaint->other_nature_of_complaint }}</th>
    <th>
    <p class="badge rounded-pill bg-{{ $complaint->act_of_misconduct == 1 ? 'success' : ($complaint->act_of_misconduct == 2 ? 'warning' : 'danger')  }} p-2"  >{{ Config::get('constants.act_of_misconduct.'.($complaint->act_of_misconduct - 1).'.name') }}</p>
    </th>
    <th>
        <div class="row p-1">
            <div class="col-lg-6 p-1 text-center">
                <form method="POST" action="{{ route('electricianComplaintDelete', $complaint->id) }}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"></i></button>
                </form>
            </div>
            <div class="col-lg-6 p-1 text-center">
                <a href="{{route('electricianComplaintEdit', $complaint->id)}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
            </div>
        </div>
    </th>
  </tr>
@endforeach 