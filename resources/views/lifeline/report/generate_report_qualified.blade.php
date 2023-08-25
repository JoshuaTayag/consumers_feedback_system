<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link href="https://fonts.cdnfonts.com/css/franklin-gothic" rel="stylesheet">
  <title>Certification</title>
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
      font-size: 10px;
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
      top: 10px;
      right: 42%;
      height: 60px;
      width: 100px;
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
        padding-top: 70px;
        padding-bottom: 120px;
    }
    .styled-table thead tr {
        background-color: #00ddb1;
        color: #ffffff;
        text-align: left;
    }
    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
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
  </style>
</head>
<body>
  <header>
    <img src="{{ public_path('images/logo.png') }}" alt="" class="img-logo">
    <img src="{{ public_path('images/iso.png') }}" alt="" class="img-iso">
    <h2 class="heading">LEYTE V ELECTRIC COOPERATIVE, INC.</h2>
    <p class="sub-heading">
      Brgy. San Pablo, Ormoc City, Leyte<br>
      Telephone Nos.: PLDT: (053) 839-3920 to 3921 / Globe: (053) 561-4466<br>
      Cellular Phone Nos. Calls Only: Smart: 0998-964-3804; Globe: 0917-836-3895
    </p>
  </header>
  <hr class="blue">
  <hr class="yellow">
  
  <div class="div" style="margin-top: 30px;">
    @if($requests['status_type'] == null)
      <h3 class="text-center">
        Number of Registered and Delisted Marginalized End-Users
      </h3>

    @elseif($requests['status_type'] == 1)
      <h3 class="text-center">
        Number of Registered Marginalized End-Users
      </h3>

    @elseif($requests['status_type'] == 2)
      <h3 class="text-center">
        Number of Delisted End-Users
      </h3>
    @endif
  </div>
  <div class="div" style="margin-top: 10px;">
    @if($requests['district'] && $requests['municipality'])
      <h3 class="text-center">
        For {{$district_name[0]}} municipality of {{$municipality_name[0]}}
      </h3>
    @elseif($requests['district'] && !$requests['municipality'])
      <h3 class="text-center">
        For {{$district_name[0]}}
      </h3>
    @endif
  </div>

  <div class="div">
    <table class="styled-table">
      <thead>
        <tr>
          <td>Period</td>
          <td>4ps</td>
          <td>Non 4ps</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>From: {{ date('M d, Y', strtotime($requests['date_from'])) }} To: {{ date('M d, Y', strtotime($requests['date_to'])) }}</td>
          <td>{{$four_ps_count}}</td>
          <td>{{$non_four_ps_count}}</td>
          
        </tr>
        <tr>
          <td>Grand Total</td>
          <td colspan="2">{{$four_ps_count + $non_four_ps_count}}</td>
          
        </tr>
      </tbody>
    </table>

  </div>

  <div class="div" style="padding-top: 40px; position: relative;">
    {{-- <img src="{{ public_path('images/analou_e_signature.png') }}" alt="" class="img-signature"> --}}
    <h4 class="text-center" style="position: relative;">
      Ana Maria Lourdes M. Pastor, MBM
    </h4>
    <p class="text-center" style="text-decoration: overline; padding: 0px; margin-top: -10px; position: relative;">
      Institutional Services Department Manager
    </p>
  </div>

  <div class="div" style="padding-top: 50px;">
    <h4 class="text-center" style="text-decoration: underline;">
      {{ date("F d, Y") }}
    </h4>
    <p class="text-center">
      Date Generated
    </p>
  </div>

  {{-- <div class="div" style="padding-top: 20px;">
    <h4 style="text-align: left !important">
      Conforme:
    </h4>
  </div>

  <div class="div">
    <h4 style="text-decoration: overline; text-align: left !important">
      Name and Signature of the Qualified Marginalized End-User
    </h4>
  </div>

  <div class="div">
    <p style="text-align: left !important">
      Any person who will be found falsifying this document shall be held liable under existing laws.
    </p>
  </div> --}}

</body>
</html>