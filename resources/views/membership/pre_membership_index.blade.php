@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <span class="mb-0 align-middle fs-3">Pre-Membership Record</span>
                        </div>
                        <div class="col-lg-6 text-end">
                            <a class="btn btn-secondary btn-md text-end" href="{{ route('pre_membership_create') }}">
                                <i class="fa fa-save"></i> Insert Record
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row pb-2">
                        <div class="col-lg-4">
                            <input type="text" placeholder="Search by First Name" id="first_name" name="first_name" class="form-control">
                        </div>
                        <div class="col-lg-4">
                            <input type="text" placeholder="Search by Last Name" id="last_name" name="last_name" class="form-control">
                        </div>
                        <div class="col-lg-4">
                            <input type="text" placeholder="Search by PMS Conductor" id="pms_conductor" name="pms_conductor" class="form-control">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered border-primary">
                            <thead>
                                <tr>
                                <th scope="col">First Name</th>
                                <th scope="col">Middle Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Address (District, Barangay, Municipality, Sitio)</th>
                                <th scope="col">Date of Birth</th>
                                <th scope="col">Date & Time Conducted</th>
                                <th scope="col">PMS Facilitator</th>
                                <th><i class="fa fa-wrench"></i></th>
                                </tr>
                            </thead>
                            <tbody id="show_data">
                                @include('membership.pre_membership_search')
                            </tbody>
                        </table>
                        {{ $pre_members->links() }}
                    </div>                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        if($('#pms_conductor').val() || $('#first_name').val() || $('#last_name').val()){
            var getFirstName = $('#first_name').val();
            var getLastName = $('#last_name').val();
            var getPmsConductor = $('#pms_conductor').val();
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                $.ajax({
                method: 'GET',
                url: "{{route('fetchPreMembers')}}",
                data: {
                    fname:getFirstName,
                    lname:getLastName,
                    pms_conductor:getPmsConductor
                },
                success:function(response){
                    $("#show_data").html(response);
                    $('#pagination').delay(500).fadeOut('fast');
                }
                });
            }, 500);
        }

        var timeout = null;
        $('#pms_conductor').keyup(function() {
        var getFirstName = $('#first_name').val();
        var getLastName = $('#last_name').val();
        var getPmsConductor = $(this).val();
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            $.ajax({
            method: 'GET',
            url: "{{route('fetchPreMembers')}}",
            data: {
                fname:getFirstName,
                lname:getLastName,
                or_number:getPmsConductor
            },
            success:function(response){
                $("#show_data").html(response);
                $('#pagination').delay(500).fadeOut('fast');
            }
            });
        }, 500);
        });

        $('#first_name').keyup(function() {
        var getFirstName = $(this).val();
        var getLastName = $('#last_name').val();
        var getPmsConductor = $('#pms_conductor').val();
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            $.ajax({
            method: 'GET',
            url: "{{route('fetchPreMembers')}}",
            data: {
                fname:getFirstName,
                lname:getLastName,
                pms_conductor:getPmsConductor
            },
            success:function(response){
                console.log(response)
                $("#show_data").html(response);
                $('#pagination').delay(500).fadeOut('fast');
            }
            });
        }, 500);
        });

        $('#last_name').keyup(function() {
        var getFirstName = $('#first_name').val();
        var getLastName = $(this).val();
        var getPmsConductor = $('#pms_conductor').val();
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            $.ajax({
            method: 'GET',
            url: "{{route('fetchPreMembers')}}",
            data: {
                fname:getFirstName,
                lname:getLastName,
                pms_conductor:getPmsConductor
            },
            success:function(response){
                $("#show_data").html(response);
                $('#pagination').delay(500).fadeOut('fast');
            }
            });
        }, 500);
        });
    });
</script>
@endsection