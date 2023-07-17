@foreach ($members as $index => $member)                        
    <tr>
        <th>{{ $member->{'OR No'} }}</th>
        <th>{{ $member->{'First Name'} }}</th>
        <th>{{ $member->{'Middle Name'} }}</th>
        <th>{{ $member->{'Last Name'} }}</th>
        <th>{{ $member->{'District'} }}, {{ $member->{'Brgy'} }}, {{ $member->{'Municipality'} }}, {{ $member->{'Sitio'} }}</th>
        <th>{{ $member->{'DateBirth'} }}</th>
        <th>
            <div class="row">
                <div class="col-lg-6 p-1 text-center">
                    <form method="POST" action="{{ route('membership.destroy', $member->id) }}">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"></i></button>
                    </form>
                </div>
                <div class="col-lg-6 p-1 text-center">
                    <a href="{{route('membership.edit', $member->id)}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                </div>
            </div>
        </th>
    </tr>
@endforeach