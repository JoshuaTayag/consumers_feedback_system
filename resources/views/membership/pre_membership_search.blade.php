@foreach ($pre_members as $index => $pre_member)                        
    <tr>
        <th>{{ $pre_member->first_name }}</th>
        <th>{{ $pre_member->middle_name }}</th>
        <th>{{ $pre_member->last_name }}</th>
        <th>{{ $pre_member->district->district_name }}, {{ $pre_member->barangay->barangay_name }}, {{ $pre_member->municipality->municipality_name }}, {{ $pre_member->sitio }}</th>
        <th>{{ $pre_member->date_of_birth }}</th>
        <th>{{ $pre_member->date_and_time_conducted }}</th>
        <th>{{ $pre_member->pms_conductor }}</th>
        <th>
            <div class="row p-1">
                <div class="col-lg-6 p-1 text-center">
                    <form method="POST" action="{{ route('preMembershipDestroy', $pre_member->id) }}">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash"></i></button>
                    </form>
                </div>
                <div class="col-lg-6 p-1 text-center">
                    <a href="{{route('pre_membership_edit', $pre_member->id)}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                </div>
            </div>
        </th>
    </tr>
@endforeach 