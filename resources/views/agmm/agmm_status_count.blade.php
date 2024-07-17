<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form Print</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        html {
            height: 100%;
            margin: 0;
        }

        body {
            background: -webkit-gradient(linear, left bottom, left top, from(#b9e0ff), to(#30a3ff));
            background: -webkit-linear-gradient(bottom, #b9e0ff 0%, #30a3ff 100%);
            background: -moz-linear-gradient(bottom, #b9e0ff 0%, #30a3ff 100%);
            background: -o-linear-gradient(bottom, #b9e0ff 0%, #30a3ff 100%);
            background: linear-gradient(to top, #b9e0ff 0%, #30a3ff 100%);
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
    </style>
</head>
<body>
<div class="container p-5" id="print-container">
    <div id="dataToDownload" >
        <div class="card col-lg-12 p-3 mx-auto" id="main-card">
            <div class="card-body">
              <div class="h2 mb-4 text-center" id="heading">LEYECO V 43rd AGMM MONITORING DASHBOARD</div>
              <div class="row my-3">
                <div class="col border mt-3">
                  <div class="row pt-2 bg-dark">
                    <div class="col text-center text-white">
                      <h4 class>Verifiers</h4>
                    </div>
                  </div>
                  <table class="table table-striped">
                    <thead>
                      <tr class="text-center">
                        <th>Name</th>
                        <th>Verified Consumers</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($total_verified_consumers_per_user as $index => $total_consumer)               
                        <tr class="text-center">
                          <td>{{ $total_consumer->name }}</td>
                          <td>{{ $total_consumer->count }}</td>
                        </tr>
                      @endforeach 
                      <tr class="text-center">
                        <th colspan="1" class="text-end">Total registered accounts:</th>
                        <th>{{$total_verified_accounts}}</th>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="col justify-content-center align-items-center border mt-3">
                  <div class="row pt-2 bg-dark">
                    <div class="col text-center text-white">
                      <h4 class>Disbursers</h4>
                    </div>
                  </div>
                  <table class="table table-striped">
                    <thead>
                      <tr class="text-center">
                        <th>Name</th>
                        <th>Counter</th>
                        <th>Total disbursed allowance</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($total_disbursed_allowances_per_user as $index => $total_allowance)               
                        <tr class="text-center">
                          <td>{{ $total_allowance->name }}</td>
                          <td>{{ $total_allowance->count }}</td>
                          <td>{{ number_format($total_allowance->total_allowance) }}</td>
                        </tr>
                      @endforeach 
                      <tr class="text-center">
                        <th>Total:</th>
                        <th>{{$total_allowance_count}}</th>
                        <th>₱{{ number_format($total_allowance_disbursed) }}</th>
                      </tr>
                    </tbody>
                  </table>
                    <h4> </h4>
                    <h4> </h4>
                    <h4> </h4>
                </div>
              </div>

              <div class="row my-3">
                <div class="col border mt-3">
                  <div class="row pt-2 bg-dark">
                    <div class="col text-center text-white">
                      <h4 class>Verified accounts per municipality</h4>
                    </div>
                  </div>
                  <table class="table table-striped">
                    <thead>
                      <tr class="text-center">
                        <th>Area Code</th>
                        <th>Area Name</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($registered_accounts_per_area as $index => $registered_account)               
                        <tr class="text-center">
                          <td>{{ $registered_account->area_code }}</td>
                          <td>{{ $registered_account->area }}</td>
                          <td>{{ $registered_account->count }}</td>
                        </tr>
                      @endforeach 
                      <tr class="text-center">
                        <th colspan="2" class="text-end">Total MCO:</th>
                        <th>{{$total_mco}}</th>
                      </tr>
                      <tr class="text-center">
                        <th colspan="2" class="text-end">Total Guest:</th>
                        <th>{{$total_guest}}</th>
                      </tr>
                      <tr class="text-center">
                        <th colspan="2" class="text-end">Total registered accounts:</th>
                        <th>{{$total_verified_accounts}}</th>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="col justify-content-center align-items-center border mt-3">
                  <div class="row pt-2 bg-dark">
                    <div class="col text-center text-white">
                      <h4 class>Pre-registered accounts</h4>
                    </div>
                  </div>
                  <table class="table table-striped">
                    <!-- <thead>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                    </thead> -->
                    <tbody>
                      <tr class="text-center">
                        <th>Pre-registered accounts (Online):</th>
                        <th>{{$total_pre_registered_accounts_online}}</th>
                      </tr>
                      <tr class="text-center">
                        <th>Pre-registered accounts (Offline):</th>
                        <th>{{$total_pre_registered_accounts_offline}}</th>
                      </tr>
                      <tr class="text-center">
                        <th>Total:</th>
                        <th>{{ $total_pre_registered_accounts }}</th>
                      </tr>
                    </tbody>
                  </table>
                    <h4> </h4>
                    <h4> </h4>
                    <h4> </h4>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
</body>
</html>


<style>
    
</style>