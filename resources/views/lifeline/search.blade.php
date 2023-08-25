@foreach ($lifeline_datas as $index => $data)                        
    <tr class="text-center">
        <th>{{ $data->control_no }}</th>
        <th>{{ $data->first_name }}</th>
        <th>{{ $data->middle_name }}</th>
        <th>{{ $data->last_name }}</th>
        <th>{{ $data->district->district_name }}, {{ $data->barangay ? $data->barangay->barangay_name : null }}, {{ $data->municipality->municipality_name }}</th>
        <th>{{ $data->date_of_birth }}</th>
        <th>{{ $data->account_no }}</th>
        <th>{{ $data->pppp_id ? "4Ps" : "Non 4Ps" }}</th>
        <th><div class="row m-2"><p class="badge rounded-pill bg-{{ $data->application_status == 0 ? 'primary' : ($data->application_status == 1 ? 'success' : 'danger') }} p-2"  >{{ $data->application_status == 0 ? "Pending" : ($data->application_status == 1 ? "Approved" : "Untag") }}</p></div></th>
        <th>
            
                <div class="row p-2">
                    @if($data->application_status == 0)
                        <div class="col-lg-4 p-1 text-center">
                            <a href="{{route('lifeline.edit', $data->id)}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                        </div>
                        <div class="col-lg-4 p-1 text-center">
                            <form method="POST" action="{{ route('LifelineUpdate', $data->id) }}">
                                @method('PUT')
                                @csrf
                                {!! Form::hidden('account_no', $data->account_no )!!}
                                {!! Form::hidden('id', $data->id )!!}
                                <button class="btn btn-{{ $data->application_status == 0 ? 'warning' : 'success' }} btn-sm confirm-print" type="submit" ><i class="fa fa-print"></i></button>
                            </form>
                            {{-- <a href="{{ route('lifelineCoverageCertificate', $data->id) }}" target="_blank" class="btn btn-{{ $data->application_status == 0 ? 'warning' : 'success' }} btn-sm"><i class="fa fa-print"></i></a> --}}
                        </div>
                    @elseif($data->application_status == 1)
                        <div class="col-lg-4 p-1 text-center">
                            <a href="{{ route('lifelineCoverageCertificate', $data->id) }}" target="_blank" class="btn btn-{{ $data->application_status == 0 ? 'warning' : 'success' }} btn-sm"><i class="fa fa-print"></i></a>
                        </div>
                        <div class="col-lg-4 p-1 text-center">
                            <form method="POST" action="{{ route('LifelineUpdate', $data->id) }}">
                                @method('PUT')
                                @csrf
                                {!! Form::hidden('account_no', $data->account_no )!!}
                                {!! Form::hidden('un_tag')!!}
                                <button class="btn btn-warning btn-sm confirm-untag" type="submit" ><i class="fa fa-chain-broken"></i></button>
                            </form>
                            {{-- <a href="{{ route('lifelineCoverageCertificate', $data->id) }}" target="_blank" class="btn btn-{{ $data->application_status == 0 ? 'warning' : 'success' }} btn-sm"><i class="fa fa-print"></i></a> --}}
                        </div>
                    @elseif($data->application_status == 2)
                        <div class="col-lg-4 p-1 text-center">
                            <a href="{{ route('lifelineCoverageCertificate', $data->id) }}" target="_blank" class="btn btn-{{ $data->application_status == 0 ? 'warning' : 'success' }} btn-sm"><i class="fa fa-print"></i></a>
                        </div>
                    @endif
                    {{-- <a id="print_certification" href="{{ route('lifelineCoverageCertificate', $data->id) }}" target="_blank" class="btn btn-{{ $data->application_status == 0 ? 'warning' : 'success' }} btn-sm"><i class="fa fa-print"></i></a> --}}
                    @if($data->application_status == 0 || $data->application_status == 2)
                        <div class="col-lg-4 p-1 text-center">
                            <form method="POST" action="{{ route('lifeline.destroy', $data->id) }}">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-danger btn-sm confirm-button" type="submit"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    @endif
                </div>
            
        </th>
    </tr>
@endforeach
@section('script')
<script type="text/javascript">
$(document).ready(function () {

    $('.confirm-print').click(function(event) {
        var form =  $(this).closest("form");
        var value =  form.serializeArray();
        const id = value[3].value;
        // var value =  form.fieldValues();
        
        event.preventDefault();
        swal.fire({
            title: `Are you sure you want to print Certification?`,
            icon: "warning",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed){
                form.submit();
            }
            else{

            }
                
        });
    });

    $('.confirm-button').click(function(event) {
        var form =  $(this).closest("form");
        event.preventDefault();
        swal.fire({
            title: `Are you sure you want to delete this row?`,
            text: "It will gone forever",
            icon: "warning",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed){
                form.submit();
            }
            else{

            }
                
        });
    });

    $('.confirm-untag').click(function(event) {
        var form =  $(this).closest("form");
        event.preventDefault();
        swal.fire({
            title: `Are you sure you want to un-tag this account?`,
            icon: "warning",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed){
                form.submit();
            }
            else{

            }
                
        });
    });
})
</script>
@endsection