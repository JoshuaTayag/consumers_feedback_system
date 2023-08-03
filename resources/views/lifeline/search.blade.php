@foreach ($lifeline_data as $index => $data)                        
    <tr>
        <th>{{ $data->control_no }}</th>
        <th>{{ $data->first_name }}</th>
        <th>{{ $data->middle_name }}</th>
        <th>{{ $data->last_name }}</th>
        <th>address</th>
        <th>{{ $data->date_of_birth }}</th>
        <th>{{ $data->account_no }}</th>
        <th>
            <div class="row">
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
        </th>
    </tr>
@endforeach