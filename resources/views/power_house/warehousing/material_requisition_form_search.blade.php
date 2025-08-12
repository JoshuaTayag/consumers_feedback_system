@foreach ($mrfs as $index => $mrf)
    @php $liquidation = $liquidations ? $liquidations->where('material_requisition_form_id', $mrf->id)->count() : 0; @endphp
    <tr class="text-center">
        <th style="white-space: nowrap;">
            {{ date('y', strtotime($mrf->created_at)) . '-' . str_pad($mrf->id, 5, '0', STR_PAD_LEFT) }}
        </th>
        <th>{{ $mrf->project_name }}</th>
        <th>{{ $mrf->district->district_name }},
            {{ $mrf->barangay_id ? $mrf->barangay->barangay_name : '' }},
            {{ $mrf->municipality->municipality_name }}
        </th>
        <th>{{ $mrf->requested_name }}</th>
        <th>{{ date('F d, Y', strtotime($mrf->requested_by)) }}</th>
        <th style="white-space: nowrap;">
            @if (count($mrf->mrf_liquidations) != 0)
                @foreach ($mrf->mrf_liquidations as $index => $mrvs)
                    {{ $mrvs->type . '# ' . $mrvs->type_number }} <br>
                @endforeach
            @else
                None
            @endif

        </th>
        <th>
            <span
                class="badge text-dark 
                bg-{{ $mrf->status == 0
                ? 'secondary'
                : ($mrf->status == 1
                  ? 'success'
                  : ($mrf->status == 2
                      ? 'info'
                      : ($mrf->status == 3 || $mrf->status == 10
                          ? 'primary'
                          : ($mrf->status == 11
                              ? 'warning'
                              : 'danger')))) }}"
                                style="width:150px; word-break:break-word; text-align:center; white-space:normal; padding: 7px; font-size: 12px;">
                @switch($mrf->status)
                    @case(0)
                        Pending
                    @break

                    @case(1)
                        Approved
                    @break

                    @case(2)
                        For Liquidation
                    @break

                    @case(3)
                    @case(10)
                        Liquidation <br>(Pending Approval)
                    @break

                    @case(11)
                        Liquidated
                    @break

                    @default
                        DisApproved
                @endswitch
            </span>
        </th>
        <th>
            <div class="row p-1">
                <div class="col">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle"
                            type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            @if ($mrf->requested_id == auth()->user()->id && $mrf->status != 11)
                                <li><a href="{{ route('material-requisition-form.edit', $mrf->id) }}"
                                        class="dropdown-item"><i class="fa fa-eye"></i>
                                        View</a></li>
                            @elseif ($mrf->status == 11)
                                <li><a href="{{ route('mrfLiquidationApprovalView', $mrf->id) }}"
                                        class="dropdown-item"><i class="fa fa-eye"></i>
                                        View</a></li>
                            @endif
                            @if ($mrf->status == 0 && $mrf->requested_id == auth()->user()->id)
                                <li>
                                    <form method="POST"
                                        action="{{ route('material-requisition-form.destroy', $mrf->id) }}">
                                        @method('DELETE')
                                        @csrf
                                        <a class="confirm-button dropdown-item"
                                            type="submit" style="text-decoration: none;"
                                            href="#"><i class="fa fa-trash"></i>
                                            Delete</a>
                                    </form>
                                </li>
                            @endif
                            @if ($mrf->status == 1)
                                @if (Auth::user()->hasRole('CETD (Dexter)') && $mrf->req_type)
                                  <li><a href="{{ route('material-requisition-form.edit', $mrf->id) }}"
                                          class="dropdown-item"><i class="fa fa-gear"></i>
                                          Process</a>
                                  </li>
                                @elseif(Auth::user()->hasRole('CETD SPRC'))
                                  <li><a href="{{ route('material-requisition-form.edit', $mrf->id) }}"
                                    class="dropdown-item"><i class="fa fa-gear"></i>
                                    Process</a>
                                  </li>
                                @endif
                            @endif
                            @if ($mrf->status == 2 && $mrf->requested_id == auth()->user()->id)
                                <li><a href="{{ route('mrfLiquidate', $mrf->id) }}"
                                        target="_blank" class="dropdown-item"><i
                                            class="fa fa-pencil"></i> Liquidate</a></li>
                                <li><a href="{{ route('mrfPrintPdf', $mrf->id) }}"
                                        target="_blank" class="dropdown-item"><i
                                            class="fa fa-print"></i> Print MRF</a></li>
                            @endif

                        </ul>
                    </div>
                </div>
            </div>

        </th>
    </tr>
@endforeach

@section('script')
    <script>
        $(document).ready(function() {
            $('.confirm-button').click(function(event) {
                var form = $(this).closest("form");
                event.preventDefault();
                swal.fire({
                    title: `Are you sure you want to delete this row?`,
                    text: "It will gone forever",
                    icon: "warning",
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    } else {

                    }

                });
            });

            var timeout = null;
            $('#project_name').keyup(function() {
                var projectName = $(this).val();
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    $.ajax({
                    method: 'GET',
                    url: "{{route('fetchMaterialRequisitionRecords')}}",
                    data: {
                        project_name: projectName
                    },
                    success:function(response){
                        console.log(response)
                        $("#show_data").html(response);
                        $('#pagination').delay(500).fadeOut('fast');
                    }
                    });
                }, 500);
            });
            
        });
    </script>
@endsection