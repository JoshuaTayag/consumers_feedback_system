<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <!-- <link href="https://fonts.cdnfonts.com/css/franklin-gothic" rel="stylesheet"> -->
   <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>RECEIPT</title></title>
  <style>
    body{
      padding-right: 65px;
      padding-left: 7px;
      padding-top: 3px;
    }
    /* #heading{
      font-size: 10px;
      font-weight: bold;
    }
    #first_row td{
      border: 1px solid black;
      padding-left: 5% !important;
      font-size: 10px;
    }
    .pull-right{
      text-align: right;
      padding-right: 10% !important;
    }
    .w-25{
      width: 25%;
    }
    .w-50{
      width: 50%;
    }
    .border-1{
      border: 1px solid black;
    } */

    .row {
      display: flex;
      justify-content: space-between; /* Add space between the tables */
      font-size: 10px;
    }
    .col-left {
      flex: 0 0 70%; /* Left column takes 70% */
      /* border: 1px solid black; */
      padding: 5px;
    }
    .col-right {
      flex: 0 0 30%; /* Right column takes 30% */
      /* border: 1px solid black; */
      padding: 5px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    table, th, td {
      /* border: 1px solid black; */
    }
    th, td {
      padding: 2px;
      text-align: left;
    }
    .pull-right{
      text-align: right;
      padding-right: 10% !important;
    }
    .pe-20{
      padding-left: 20% !important;
    }
    .w-25{
      width: 25%;
    }
    .w-50{
      width: 50%;
    }
  </style>
</head>
<body>
  <br>
  
  <div class="row">
    <!-- First Table -->
    <div class="col-left">
      <table>
        <thead>
          <tr>
            <th class="pe-20" colspan="2" style="position: relative; top: -10px;">TELLER's</th>
            <!-- <th>Column 2</th> -->
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="2">{{ $result['Name'] }}</td>
            <!-- <td>Data 1</td> -->
          </tr>
          <tr>
            <td colspan="2">{{ $result['Address'] }}</td>
          </tr>
          <tr>
            <!-- <td>NP</td> -->
            <td>&nbsp;</td>
            <!-- <td class="w-50">R - Electric Bill</td> -->
            <td class="w-50">&nbsp;</td>
          </tr>
          <tr>
            <!-- <td colspan="2">01-0101-0101</td> -->
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">{{ strtoupper($convertedNumber['result'] ? $convertedNumber['result'] : '') }} ONLY</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td>{{ $result['Kwhm Deposit'] ? 'METER ACCE.' : '' }}</td>
            <td>{{ number_format($result['Kwhm Deposit'], 2) }}</td>
          </tr>
          <tr>
            <td>{{ $result['Calibration'] ? 'CALIBRATION' : '' }}</td>
            <td>{{ number_format($result['Calibration'], 2) }}</td>
          </tr>
          <tr>
            <td>{{ $result['MeterSeal'] ? 'METER SEAL' : '' }}</td>
            <td>{{ number_format($result['MeterSeal'], 2) }}</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" class="pull-right" style="padding-right: 25% !important;">{{ number_format($result['Total'], 2) }}</td>
          </tr>
          <tr>
            <td colspan="2">
              <span style="float: left;">Distribution</span>
              <span style="float: right; padding-right: 25% !important;">{{ number_format($result['Distribution'], 2) }}</span>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <span style="float: left;">Final VAT</span>
              <span style="float: right; padding-right: 25% !important;">{{ number_format($result['Final VAT'], 2) }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <!-- Second Table -->
    <div class="col-right">
      <table>
        <thead>
          <tr>
            <th class="pull-right" style="position: relative; top: -10px;">{{ date('m/d/Y h:i A') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="pull-right">{{ number_format($result['OverallTotal'], 2) }}</td>
          </tr>
          <tr>
            <td class="pull-right">&nbsp;</td>
          </tr>
          <tr>
            <td class="pull-right">{{ number_format($result['Vatable'], 2) }}</td>
          </tr>
          <tr>
            <td class="pull-right">{{ number_format($result['VExempt'], 2) }}</td>
          </tr>
          <tr>
            <td class="pull-right">&nbsp;</td>
          </tr>
          <tr>
            <td class="pull-right">{{ number_format($result['VATotal'], 2) }}</td>
          </tr>
          <tr>
            <td class="pull-right">{{ number_format($result['Vatable']+$result['VExempt']+$result['VATotal'], 2) }}</td>
          </tr>
          <tr>
            <td class="pull-right">{{ number_format($result['VATotal'], 2) }}</td>
          </tr>
          <tr>
            <td class="pull-right">{{ number_format(($result['Vatable']+$result['VExempt']+$result['VATotal'])-$result['VATotal'], 2) }}</td>
          </tr>
          <tr>
            <td class="pull-right">0</td>
          </tr>
          <tr>
            <th class="pull-right">{{ number_format(($result['Vatable']+$result['VExempt']+$result['VATotal'])-$result['VATotal'], 2) }}</th>
          </tr>
          <tr>
            <td class="pull-right">&nbsp;</td>
          </tr>
          <tr>
            <th class="pull-right">{{ number_format($result['OverallTotal'], 2) }}</th>
          </tr>
          <tr>
            <td class="pull-right">{{ number_format($result['AmtTender'], 2) }}</td>
          </tr>
          <tr>
            <td class="pull-right">{{ number_format($result['AmtTender']-$result['OverallTotal'], 2) }}</td>
          </tr>
          <tr>
            <td class="pull-right">&nbsp;</td>
          </tr>
          <tr>
            <td class="pull-right">&nbsp;</td>
          </tr>
          <tr>
            <td style="position: relative; left: -67px; top: -5px;">✔</td>
          </tr>
          <tr>
            <td style="position: relative; left: -67px; top: -5px;"></td>
          </tr>
          <tr>
            <td class="pull-right">&nbsp;</td>
          </tr>
          <tr>
            <td>{{ strtoupper(auth()->user()->name) }}</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td class="pull-right">{{ $result['SCONo'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>


  </div>

  <!-- <div class="row" style="padding: 5px; margin-top: -80px; ">
    <div class="col" style="flex: 0 0 70%;">
      <table>
        <tbody>
          <tr>
            <th class="pull-right check">asd</th>
          </tr>
          <tr>
            <th class="pull-right check">asd</th>
          </tr>
        </tbody>
      </table>
    </div>
  </div> -->
  






      <!-- <tr id="first_row">
        <td id="heading" style="padding-left: 10% !important;" colspan='2'>TELLER's</td>
        <td id="heading" class="pull-right">10/01/2024 03:48 PM</td>
      </tr>

      <tr id="first_row">
        <td class="w-50" colspan='2'>Tayag, Joshua J.</td>
        <td class="pull-right" colspan='2'>1000</td>
      </tr>

      <tr id="first_row">
        <td colspan="2">Brgy. San Antonio, Ormoc City</td>
      </tr>

      <tr id="first_row">
        <td>NP</td>
        <td class="w-25">R - Electric Bill</td>
        <td class="w-50 pull-right">R - Electric Bill</td>
      </tr>

      <tr id="first_row">
        <td>NP</td>
        <td class="w-25">R - Electric Bill</td>
        <td class="w-50 pull-right">R - Electric Bill</td>
      </tr>

      <tr id="first_row">
        <td>NP</td>
        <td class="w-25">R - Electric Bill</td>
        <td class="w-50 pull-right">R - Electric Bill</td>
      </tr> -->
    </tbody>
  </table>

  <script>
  window.onload = function() {
    window.print();  // This opens the print preview when the page loads
  };

  // Redirect after the print dialog is closed
  window.onafterprint = function() {
    if (typeof Swal !== 'undefined') {  // Check if Swal is loaded
      Swal.fire({
          icon: 'success',
          title: 'Print Complete!',
          text: 'You will now be redirected.',
          confirmButtonText: 'OK'
      }).then((result) => {
          if (result.isConfirmed) {
              // Perform the redirection after user confirms
              window.location.href = "{{ route('change-meter-request-transact.create') }}";
          }
      });
    } else {
      console.error('Swal is not defined. Make sure SweetAlert2 is loaded.');
    }
  };
</script>

</body>
</html>
