@foreach ($activities as $index => $activity)               
  <tr class="text-center">
    <th>{{ $activity->activity_name }}</th>
    <th>{{ $activity->facilited_by }}</th>
    <th>{{ \Carbon\Carbon::parse($activity->date_conducted_from)->format('m/d/Y') . ' to ' . \Carbon\Carbon::parse($activity->date_conducted_to)->format('m/d/Y') }}</th>
    <th>{{ \Carbon\Carbon::parse($activity->time_conducted_from)->format('h:i a') . ' - ' . \Carbon\Carbon::parse($activity->time_conducted_to)->format('h:i a') }}</th>
    <th>{{ $activity->venue}}</th>
    <th>
        <div class="row p-1">
            <div class="col-lg-6 p-1 text-center">
                <form method="POST" action="{{ route('electricianActivityDelete', $activity->id) }}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"></i></button>
                </form>
            </div>
            <div class="col-lg-6 p-1 text-center">
                <a href="{{route('electricianActivityEdit', $activity->id)}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
            </div>
        </div>
    </th>
  </tr>
@endforeach 