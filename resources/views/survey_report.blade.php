@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-middle">
                        <div class="col-lg-6 m-2">
                            <span class="mb-0 align-middle">SURVEY REPORT</span>
                        </div>
                    </div>
                </div>

                <div class="card-body m-2">
                    <div class="row pb-3">
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="datetimes" id="date_range">
                        </div>

                        <div class="col-lg-2">
                            <select name="type" id="type" class="form-control">
                                <option value="">ALL</option>
                                <option value="1">CUSTCARE & CASHIER</option>
                                <option value="0">BILLING & HOUSEWIRING</option>
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <button class="btn btn-primary" id="search" ><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    
                    <h5 id="period"></h5>

                    <h5 id="text_type"></h5>
                    {{-- <h5 id="satisfied"></h5>
                    <h5 id="fine"></h5>
                    <h5 id="un_satisfied"></h5> --}}


                    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>
<script>
    $(function() {
        $('input[name="datetimes"]').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
            format: 'YYYY/MM/DD H:mm:ss'
            }
        });

        $('#search').on('click', function () {
            var range_value = $('#date_range').val();
            var type = $('#type').val();
            var text_type = $( "#type option:selected" ).text();
            var arr = range_value.split('-');
            var from = new Date(arr[0]).toDateString();
            var to = new Date(arr[1]).toDateString();
            // console.log(from);
            $.ajax({
                url: "{{url('api/fetch-survey')}}",
                type: "POST",
                data: {
                    value: range_value,
                    value_type: type,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    $("#period").text("Period: "+from+" - " + to);
                    $("#text_type").text("Window: "+text_type);
                    var data_chart = [];
                    $.each(result.survey_result, function (key, value) {
                        // console.log(value.vote);
                        if(value.vote == 2){
                            $("#satisfied").text("Satisfied: "+value.total_vote);

                            data_chart.push({ label: "Satisfied", y: value.total_vote });
                        }
                        else if(value.vote == 1){
                            $("#fine").text("Fine: "+value.total_vote);

                            data_chart.push({ label: "Fine", y: value.total_vote });
                        }
                        else{
                            $("#un_satisfied").text("Unsatisfied: "+value.total_vote);

                            data_chart.push({ label: "Unsatisfied", y: value.total_vote });
                        }

                        
                    });
                    var options = {
                        title: {
                            text: "Consumer Feedback Survey"
                        },
                        data: [{
                                type: "pie",
                                startAngle: 45,
                                showInLegend: "true",
                                legendText: "{label}",
                                indexLabel: "{label} ({y})",
                                yValueFormatString:"#,##0.#"%"",
                                dataPoints: data_chart
                        }]
                    };
                    $("#chartContainer").CanvasJSChart(options);
                }
            });
        });
    });
</script>
@endsection
@section('style')
<style>
    thead tr th {
        background-color: #ffffff !important;
        color: rgb(0, 0, 0) !important;
      }
</style>
@endsection