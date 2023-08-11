@foreach ($lifeline_datas as $index => $data)                        
    <tr class="text-center">
        <th>{{ $data->control_no }}</th>
        <th>{{ $data->first_name }}</th>
        <th>{{ $data->middle_name }}</th>
        <th>{{ $data->last_name }}</th>
        <th>{{ $data->district->district_name }}, {{ $data->barangay->barangay_name }}, {{ $data->municipality->municipality_name }}</th>
        <th>{{ $data->date_of_birth }}</th>
        <th>{{ $data->account_no }}</th>
        <th>{{ $data->pppp_id ? "4Ps" : "Non 4Ps" }}</th>
        <th class="badge rounded-pill bg-{{ $data->application_status == 0 ? 'primary' : ($data->application_status == 1 ? 'success' : 'danger') }}"  >{{ $data->application_status == 0 ? "Pending" : ($data->application_status == 1 ? "Approved" : "DisApproved") }}</th>
        <th>
            @if($data->application_status == 0)
                <div class="row p-2">
                    <div class="col-lg-6 p-1 text-center">
                        <form method="POST" action="{{ route('lifeline.destroy', $data->id) }}">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"></i></button>
                        </form>
                    </div>
                    <div class="col-lg-6 p-1 text-center">
                        <a href="{{route('lifeline.edit', $data->id)}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                    </div>
                </div>
            @endif
        </th>
    </tr>
@endforeach