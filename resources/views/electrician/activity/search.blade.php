@foreach ($activities as $index => $activity)               
  <tr class="text-center">
    <th>{{ $activity->activity_name }}</th>
    <th>{{ $activity->facilited_by }}</th>
    <th>{{ $activity->date_conducted }}</th>
    <th>{{ $activity->time_conducted}}</th>
    <th>{{ $activity->venue}}</th>
    <th>
        <div class="row p-1">
            <div class="col-lg-6 p-1 text-center">
                <form method="POST" action="{{ route('electricianComplaintDelete', $activity->id) }}">
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