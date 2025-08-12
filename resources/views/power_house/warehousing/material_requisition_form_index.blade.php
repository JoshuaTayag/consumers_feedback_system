@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if ($daysPassed >= 30)
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert">
                        Note: Requests older than 30 days must be liquidated before submitting a new request.<br>
                        @foreach ($old_unliquidated_mrf as $index => $old_mrf)
                            <strong>*
                                <span>{{ date('y', strtotime($old_mrf->created_at)) . '-' . str_pad($old_mrf->id, 5, '0', STR_PAD_LEFT) }}</span></strong><br>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <span class="mb-0 align-middle fs-3">Material/Equipment Requisition</span>
                            </div>
                            <div class="col-lg-6 text-end">
                                <a class="btn btn-success" href="{{ route('mrfLiquidationReport') }}" target="_blank"> <i
                                        class="fa fa-eye"></i> Liquidation Report </a>
                                @if ($unliquidated_mrf < 10 && $daysPassed <= 30)
                                    <a class="btn btn-success" href="{{ route('material-requisition-form.create') }}">
                                        Create New Request </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-5 d-flex align-items-stretch">
                                <div class="card mb-3 w-100">
                                    <div class="card-header"><span class="fw-bold">Search</span></div>
                                    <div class="card-body">
                                        <div>
                                            <input type="text" class="form-control" id="project_name"
                                                placeholder="Search Project Name">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 d-flex align-items-stretch">
                                <div class="card mb-3 w-100">
                                    <div class="card-header"><span class="fw-bold">Legend</span></div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="badge text-dark bg-secondary"
                                                style="text-align:center; white-space:normal; padding: 8px; font-size: 12px;">Pending</span>
                                            <span class="badge text-dark bg-success"
                                                style="text-align:center; white-space:normal; padding: 8px; font-size: 12px;">Approved</span>
                                            <span class="badge text-dark bg-info"
                                                style="text-align:center; white-space:normal; padding: 8px; font-size: 12px;">For
                                                Liquidation</span>
                                            <span class="badge text-dark bg-primary"
                                                style="text-align:center; white-space:normal; padding: 8px; font-size: 12px;">Liquidation
                                                (Pending for Approval)</span>
                                            <span class="badge text-dark bg-warning"
                                                style="text-align:center; white-space:normal; padding: 8px; font-size: 12px;">Liquidated</span>
                                            <span class="badge text-dark bg-danger"
                                                style="text-align:center; white-space:normal; padding: 8px; font-size: 12px;">DisApproved</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <tr class="text-center">
                                <th>MER #</th>
                                <th>Project Name</th>
                                <th>Address</th>
                                <th>Requested By</th>
                                <th>Requested Date</th>
                                <th>References</th>
                                <th>Status</th>
                                <th style="min-width: 60px;">Action</th>
                            </tr>
                            {{-- <tbody class="align-middle text-center">
                                
                            </tbody> --}}

                            <tbody id="show_data">
                              @include('power_house.warehousing.material_requisition_form_search')
                            </tbody>
                        </table>
                        <div id="pagination">{{ $mrfs->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
