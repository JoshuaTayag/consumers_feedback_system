@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Ledger</span>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row" id="show_data">
                <form action="{{ route('ledger.search') }}" method="GET">
                  <div class="row p-3">
                    <div class="col-lg-3">
                        <input type="text" placeholder="Search by Account No." id="search_account_no" name="account_no" class="form-control" value="{{ request('account_no') }}">
                    </div>
                    <!-- <div class="col-lg-3"> -->
                        <input type="hidden" placeholder="Search by Name" id="search_name" name="account_name" class="form-control" value="{{ request('account_name') }}">
                    <!-- </div> -->
                    <div class="col-lg-3">
                      <div class="mb-2">
                        <select class="form-control" id="electric_service_details" name="electric_service_details">
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" placeholder="Search by Meter No" id="search_serial_no" name="serial_no" class="form-control" value="{{ request('serial_no') }}">
                    </div>
                    <div class="col-lg-3">
                      <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Search</button>
                      <button type="button" class="btn btn-info" onclick="clearSearch()"> <i class="fa fa-eraser"></i> Clear</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="card mt-4">
            <div class="card-header fs-5">
              Account Details
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label class="form-label mb-1">Account No.  </label>
                    <input class="form-control form-control-sm" name="account_name" id="account_name" value="{{ isset($account) && $account !== null ? $account->{'Accnt No'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="mb-2">
                    <label class="form-label mb-1">Name </label>
                    <input class="form-control form-control-sm" name="account_name" id="account_name" value="{{ isset($account) && $account !== null ? $account->{'Name'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="mb-2">
                    <label class="form-label mb-1">Address </label>
                    <input class="form-control form-control-sm" name="address" id="address" value="{{ isset($account) && $account !== null ? $account->{'Address'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label class="form-label mb-1">Type </label>
                    <input class="form-control form-control-sm" name="type" id="type" value="{{ isset($account) && $account !== null ? $account->{'Cons Type'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label class="form-label mb-1">Present SR# </label>
                    <input class="form-control form-control-sm" name="serial_no" id="serial_no" value="{{ isset($account) && $account !== null ? $account->{'Serial No'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label class="form-label mb-1">Brand </label>
                    <input class="form-control form-control-sm" name="brand" id="brand" value="{{ isset($account) && $account !== null ? $account->{'Brand'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label class="form-label mb-1">NOMA </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account !== null ? $account->{'Noma'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col">
                  <div class="mb-2">
                    <label class="form-label mb-1">Latest Reading Date </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account !== null ? $account->{'LatestDateRdng'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col">
                  <div class="mb-2">
                    <label class="form-label mb-1">Sequence No. </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account !== null ? $account->{'Seq-No'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col">
                  <div class="mb-2">
                    <label class="form-label mb-1">Book No. </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account !== null ? $account->{'Book No'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col">
                  <div class="mb-2">
                    <label class="form-label mb-1">Date Of Entry </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account !== null ? $account->{'Date'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col">
                  <div class="mb-2">
                    <label class="form-label mb-1">Active </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account->{'Acct Stat'} !== null ? 'Yes' : 'No'}}" readonly>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="mb-2">
                    <label class="form-label mb-1">Remarks </label>
                    <textarea class="form-control" name="remarks" id="remarks" readonly>{{ isset($account) && $account->{'Remarks'} !== null ? $account->{'Remarks'} : ''}}</textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card mt-4">
            <div class="card-header fs-5">
              Account Details
            </div>
            <div class="card-body">
              <table class="table table-striped table-bordered border-primary">
                <thead>
                  <tr>
                    <td>Date</td>
                    <!-- <td>Account No</td> -->
                    <td>Previous Reading</td>
                    <td>Present Reading</td>
                    <td>KHW Used</td>
                    <td>Amount</td>
                    <td>Billed By</td>
                    <td>Due Date</td>
                    <td>Collection Outlet</td>
                  </tr>
                </thead>
                <tbody>
                  @if(isset($ledger_history))
                    @foreach ($ledger_history as $key => $history)
                      <tr>
                        <td>{{ date('F j, Y', strtotime($history->BillDate)) }}</td>
                        <!-- <td>{{ $history->{'Account No'} }}</td> -->
                        <td>{{ $history->HisPrev }}</td>
                        <td>{{ $history->{'Present Reading'} }}</td>
                        <td>{{ $history->{'KWH Used'} }}</td>
                        <td>{{ $history->{'BillAmt'} }}</td>
                        <td>{{ $history->{'Billed'} }}</td>
                        <td>{{ date('F j, Y', strtotime($history->DueDate)) }}</td>
                        <td></td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
          <div class="card mt-4">
            <div class="card-header fs-5">
              Kwh used
            </div>
            <div class="card-body">
              <div id="chartdiv"></div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>
  $(document).ready(function () {
    const selectedAccountName = "{{ request('account_name') }}";

    $('.js-example-basic-single').select2({
        theme: "classic"
    });

    $( "#electric_service_details" ).select2({
      ajax: { 
        url: "{{route('fetchAccounts')}}",
        type: "get",
        dataType: 'json',
        data: function (params) {
          return {
              // _token: '{{csrf_token()}}',
              search: params.term, // search term
              byName: '1',
              page: params.page
          };
        },
        processResults:function (results, params){
          params.page = params.page||1;

          return{
            results:results.data,
            pagination:{
              more:results.last_page!=params.page
            },
          }
        },
        cache: true
      },
      // placeholder:'Search Account Number',
      templateResult: templateResult,
      templateSelection: templateSelection,
    });

    function templateResult(data){
    if (data.loading){
      return data.text
    }
    return data.Name
    }

    function templateSelection(data){
      // Set the hidden input value to the selected name
      $('#search_name').val(data.Name);
      return data.Name
    }

    // Set the initial selection if `selectedAccountId` and `selectedAccountName` exist
    if (selectedAccountName) {
        const newOption = new Option(selectedAccountName, true);
        $('#electric_service_details').append(newOption).trigger('change');
    }
  })

  function clearSearch() {
    $('#search_account_no').val('');
    $('#search_name').val('');
    $('#search_serial_no').val('');
  }

  am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);

root.dateFormatter.setAll({
  dateFormat: "yyyy",
  dateFields: ["valueX"]
});

var data = {!! json_encode($ledger_history_kwh) !!};

data = data.map(item => ({
    date: item.date,
    value: parseInt(item.value, 10) // Convert value to integer
}));

// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  focusable: true,
  panX: true,
  panY: true,
  wheelX: "panX",
  wheelY: "zoomX",
  pinchZoomX:true,
  paddingLeft: 0
}));

var easing = am5.ease.linear;


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
  maxDeviation: 0.1,
  groupData: false,
  baseInterval: {
    timeUnit: "month",
    count: 1
  },
  renderer: am5xy.AxisRendererX.new(root, {
    minorGridEnabled: true,
    minGridDistance: 100
  }),
  tooltip: am5.Tooltip.new(root, {})
}));

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  maxDeviation: 0.2,
  renderer: am5xy.AxisRendererY.new(root, {})
}));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.LineSeries.new(root, {
  minBulletDistance: 10,
  connect: false,
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "value",
  valueXField: "date",
  tooltip: am5.Tooltip.new(root, {
    pointerOrientation: "horizontal",
    labelText: "{valueY} kWh"
  })
}));

series.fills.template.setAll({
  fillOpacity: 0.2,
  visible: true
});

series.strokes.template.setAll({
  strokeWidth: 2
});


// Set up data processor to parse string dates
// https://www.amcharts.com/docs/v5/concepts/data/#Pre_processing_data
series.data.processor = am5.DataProcessor.new(root, {
  dateFormat: "yyyy-MM-dd",
  dateFields: ["date"]
});

series.data.setAll(data);

series.bullets.push(function() {
  var circle = am5.Circle.new(root, {
    radius: 6,
    fill: root.interfaceColors.get("background"),
    stroke: series.get("fill"),
    strokeWidth: 4
  })

  return am5.Bullet.new(root, {
    sprite: circle
  })
});


// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
  xAxis: xAxis,
  behavior: "none"
}));
cursor.lineY.set("visible", false);

// add scrollbar
chart.set("scrollbarX", am5.Scrollbar.new(root, {
  orientation: "horizontal"
}));


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
chart.appear(1000, 100);

}); // end am5.ready()

</script>
@endsection
@section('style')
<style>
  .select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 36px;
    user-select: none;
    -webkit-user-select: none;
  }
  #chartdiv {
    width: 100%;
    height: 500px;
  }
</style>
@endsection