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
  
  <div class="div" style="margin-top: 20px; margin-bottom: 0px; padding-bottom: 0px;">
    <h3 class="text-center" style="padding-bottom: 0px; margin-bottom: 0px;">
      MRV & SERIV LIQUIDATION REPORT
    </h3>
  </div>

  <div class="div" style="margin-top: 5px; margin-bottom: 50px; padding-bottom: 0px;">
    <h3 class="text-center" style="padding-bottom: 0px; margin-bottom: 0px;">
      as of today
    </h3>
  </div>

  <div class="text-align" style="margin-top: 0px; padding-top: 0px;">
    <table class="styled-table" style="font-size: 12px; width: 100%; padding-top: 0px; padding-bottom: 0px;">
      <thead>
        <tr>
          <th rowspan="3">DIVISION</th>
          <th colspan="3">WITHOUT WORK ORDER NO.</th>
          <th rowspan="2">DISAPPROVED/CANCELLED</th>
        </tr>
        <tr>
          <th>TOTAL REQUESTS</th>
          <th>LIQUIDATED</th>
          <th>UNLIQUIDATED</th>
        </tr>
        <tr>
          <th>MRF / BOM</th>
          <th>MRF / BOM</th>
          <th>MRF / BOM</th>
          <th>MRF / BOM</th>
        </tr>
      </thead>
      <tbody>
          <tr>
            <td>asdasd</td>
            <td>asdasd</td>
            <td>asdasd</td>
            <td>asdasd</td>
            <td>asdasd</td>
          </tr>
      </tbody>
    </table>
  </div>

  <div class="text-align" style="margin-top: 70px; padding-top: 0px;">
    <table class="styled-table" style="font-size: 12px; width: 100%; padding-top: 0px; padding-bottom: 0px;">
      <thead>
        <tr>
          <th rowspan="3">DIVISION</th>
          <th colspan="3">WITH WORK ORDER NO.</th>
          <th rowspan="2">DISAPPROVED/CANCELLED</th>
        </tr>
        <tr>
          <th>TOTAL REQUESTS</th>
          <th>LIQUIDATED</th>
          <th>UNLIQUIDATED</th>
        </tr>
        <tr>
          <th>MRF / BOM</th>
          <th>MRF / BOM</th>
          <th>MRF / BOM</th>
          <th>MRF / BOM</th>
        </tr>
      </thead>
      <tbody>
          <tr>
            <td>asdasd</td>
            <td>asdasd</td>
            <td>asdasd</td>
            <td>asdasd</td>
            <td>asdasd</td>
          </tr>
      </tbody>
    </table>
  </div>

  <table style="font-size: 15px; width: 100%; margin-top: 35px; padding-bottom: 30px;">
    <tbody>
      <tr>
        <td style="border: none; width:33%; position: relative; padding-left: 20px; padding-right: 20px;">
          <p class="text-center" style="padding-top: 15px;">Checked By:</p> <br>
          @if($datas->user_requested->employee)
            <img src="{{$datas->user_requested->employee->signature_path}}"  alt="" style="padding-top: 30px;" class="img-signature">
          @endif
          <h4 class="text-center" style="position: relative; margin-top: 40px;">
            {{$datas->requested_name}}
          </h4>
          <div style="border-top: 1px solid black; position:relative; margin-top: -10px;" ></div>
          <p class="text-center" style="padding: 0px; position: relative;">
            {{$datas->user_requested->employee ? $datas->user_requested->employee->position : 'Pls Add employee data'}}
          </p>
        </td>
        <td style="border: none; width:33%; position: relative; padding-left: 20px; padding-right: 20px;">
          <p class="text-center">Checked By:</p> <br>
          <div style="border-top: 1px solid black; margin-top: 50px; margin-bottom: 15px;"></div>
        </td>
        <td style="border: none; width:33%; position: relative; padding-left: 20px; padding-right: 20px;">
          <p class="text-center">Noted By:</p> <br>
          <div style="border-top: 1px solid black; margin-top: 50px; margin-bottom: 15px;"></div>
        </td>
      </tr>
    </tbody>
  </table>

</body>
</html>