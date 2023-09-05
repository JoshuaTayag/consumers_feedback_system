<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link href="https://fonts.cdnfonts.com/css/franklin-gothic" rel="stylesheet">
  <title>TSD Form</title>
  <style>
    header{
      font-family: 'Franklin Gothic', sans-serif;
      position: relative;
    }
    .heading{
      text-align: center;
      margin: 10px;
    }
    .sub-heading{
      text-align: center;
      font-size: 15px;
    }
    hr, .blue{
      padding: 0x;
      margin: 0px;
      border: 2px solid blue;
    }
    hr, .yellow{
      padding: 0x;
      margin: 0px;
      border: 2px solid yellow;
    }
    .img-logo{
      position: absolute;
      top: 25px;
      left: 20px;
      height: 80px;
      width: 80px;
    }
    .img-iso{
      position: absolute;
      top: 25px;
      right: 10px;
      height: 80px;
      width: 100px;
    }
    .img-signature{
      position: absolute;
      top: 20px;
      right: 20%;
      height: 80px;
      width: 120px;
    }
    .text-center{
      text-align: center;
    }
    .text-center, h3, h4{
      text-align: center;
      line-height: 0.3;
    }
    .text-underline{
      text-decoration: underline;
      text-transform: uppercase;
    }
    .text-align {
      text-align: justify;
      text-justify: inter-word;
    }
    .styled-table {
        border-collapse: collapse;
        /* margin: 25px 0; */
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        margin-left: auto;
        margin-right: auto;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .styled-table thead tr {
        background-color: #00ddb1;
        color: #ffffff;
        text-align: left;
    }
    .styled-table th,
    .styled-table td {
        padding: 3px 4px;
    }
    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
        
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #00ddb1;
    }

    .styled-table, th, td {
      border: 1px solid rgb(0, 0, 0);
    }
  </style>
</head>
<body>
  <header>
    <img src="{{ public_path('images/logo.png') }}" alt="" class="img-logo">
    <img src="{{ public_path('images/iso.png') }}" alt="" class="img-iso">
    <h2 class="heading">LEYTE V ELECTRIC COOPERATIVE, INC.</h2>
    <p class="sub-heading">
      Brgy. San Pablo, Ormoc City, Leyte<br>
      TECHNICAL SERVICES DEPARTMENT
    </p>
  </header>
  <hr class="blue">
  <hr class="yellow">
  <p>TSD FORM No. 001</p>
  {{-- <div class="div" style="margin-top: 30px;">
    <h4 class="text-center">Annex C - Certification of Lifeline Coverage </h4>
  </div> --}}
  
  <div class="div" style="margin-top: 20px; margin-bottom: 0px; padding-bottom: 0px;">
    <h3 class="text-center" style="padding-bottom: 0px; margin-bottom: 0px;">
      MATERIAL/EQUIPMENT REQUISITION AND LIQUIDATION FORM
    </h3>
  </div>

  <div class="text-align" style="margin-top: 0px; padding-top: 0px;">
    <table class="styled-table" style="font-size: 12px; width: 100%; padding-bottom: 40px;" >
      <tbody>
        <tr>
          <th rowspan="3" style="text-align: left;">PROJECT NAME: <br><br>
            {{ $datas->project_name }}</th>
          <td colspan="2"></td>
          <td style="width: 80px;">Date</td>
          <td style="width: 70px;">Endorsed By</td>
          <td style="width: 67px;">Prepared By</td>
        </tr>
        <tr>
          <td style="width: 70px;">WO No.</td>
          <td style="width: 80px;"></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>MRV No.</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <th rowspan="3" style="text-align: left;">LOCATION: <br><br>
            {{ $datas->district->district_name }}, {{ $datas->barangay->barangay_name }}, {{ $datas->municipality->municipality_name }}</th>
          <td>SERIV No.</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>MCRT No.</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>MST No.</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>

    MWO No.
    <table class="styled-table" style="font-size: 12px; width: 100%; padding-top: 0px; padding-bottom: 0px;">
      <thead>
        <tr>
          <th>Nea Code</th>
          <th>Description</th>
          <th>Unit Cost</th>
          <th>Quantity</th>
          <th>Existing Cost</th>
          <th>Installed</th>
          <th>Variance</th>
        </tr>
      </thead>
      <tbody>
          @foreach($datas->items as $index => $data)
          <tr>
            <td>{{$data->nea_code}}</td>
            <td>{{$data->item->Description}}</td>
            <td>{{$data->unit_cost}}</td>
            <td>{{$data->quantity}}</td>
            <td>{{ number_format($data->item->AveragePrice, 2, ".", ",") }}</td>
            <td></td>
            <td></td>
          </tr>
          @endforeach
      </tbody>
    </table>
  </div>

  <table style="font-size: 15px; width: 100%; padding-top: 15px; padding-bottom: 30px;">
    <tbody>
      <tr>
        <td style="border: none; width:33%; position: relative;">
          <p class="text-center">Requested By:</p> <br>
          @if($datas->user_requested->employee)
            <img src="{{$datas->user_requested->employee->signature_path}}"  alt="" class="img-signature">
          @endif
          <h4 class="text-center" style="position: relative; text-decoration: underline;">
            &nbsp;&nbsp;&nbsp;&nbsp;{{$datas->requested_name}}&nbsp;&nbsp;&nbsp;&nbsp;
          </h4>
          <p class="text-center" style="padding: 0px; margin-top: -10px; position: relative;">
            {{$datas->user_requested->employee ? $datas->user_requested->employee->position : 'Pls Add employee data'}}
            {{-- {{$datas->user_req->employee->prefix . " " . $datas->user_req->employee->first_name . " " . substr($datas->user_req->employee->middle_name, 0, 1). "." . " " . $datas->user_req->employee->last_name . " " . $datas->user_req->employee->suffix}} --}}
          </p>
        </td>
        <td style="border: none; width:33%; position: relative;">
          <p class="text-center">Approved By:</p> <br>
          <img src="{{$datas->user_approved->employee->signature_path}}"  alt="" class="img-signature">
          <h4 class="text-center" style="position: relative; text-decoration: underline;">
            &nbsp;&nbsp;&nbsp;&nbsp;{{$datas->approved_name}}&nbsp;&nbsp;&nbsp;&nbsp;
          </h4>
          <p class="text-center" style="padding: 0px; margin-top: -10px; position: relative;">
            {{$datas->user_approved->employee->position}}
          </p>
        </td>
        <td style="border: none; width:34%;">
          <p class="text-center">Processed By:</p> <br>
          <h4 class="text-center" style="position: relative;">
            
          </h4>
          <p class="text-center" style="text-decoration: overline; padding: 0px; margin-top: -10px; position: relative;">
            &nbsp;&nbsp;&nbsp;&nbsp; CETD &nbsp;&nbsp;&nbsp;&nbsp;
          </p>
        </td>
      </tr>
    </tbody>
  </table>
  
  <table style="font-size: 15px; width: 100%;">
    <tbody>
      <tr>
        <td style="border: none;">
          <p class="text-center">Released By:</p> <br>
          <h4 class="text-center" style="position: relative;">
            
          </h4>
          <p class="text-center" style="text-decoration: overline; padding: 0px; margin-top: -10px; position: relative;">
            &nbsp;&nbsp;&nbsp;&nbsp; WAREHOUSE &nbsp;&nbsp;&nbsp;&nbsp;
          </p>
        </td>
        <td style="border: none;">
          <p class="text-center">Liquidated By:</p> <br>
          <h4 class="text-center" style="position: relative;">
            
          </h4>
          <p class="text-center" style="text-decoration: overline; padding: 0px; margin-top: -10px; position: relative;">
            &nbsp;&nbsp;&nbsp;&nbsp; AREA HEAD &nbsp;&nbsp;&nbsp;&nbsp;
          </p>
        </td>
      </tr>
    </tbody>
  </table>

</body>
</html>