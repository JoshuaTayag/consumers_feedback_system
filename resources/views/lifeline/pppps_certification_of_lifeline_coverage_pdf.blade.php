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
      top: 26px;
      right: 42%;
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

  {{-- <div class="div" style="margin-top: 30px;">
    <h4 class="text-center">Annex C - Certification of Lifeline Coverage </h4>
  </div> --}}
  
  <div class="div" style="margin-top: 30px;">
    <h3 class="text-center">
      CERTIFICATION OF LIFELINE COVERAGE 
    </h3>
    <h4 class="text-center">
      Certification No. {{$data->control_no}}
    </h4>
    <h4 class="text-center">
      (4Ps)
    </h4>
  </div>

  <div class="text-align">
    <p>
      This is to certify that, <span class="text-underline">{{$data->first_name}}  {{$data->middle_name}} {{$data->last_name}}</span>, of legal age, residing at 
      <span class="text-underline"> {{ $data->district->district_name }}, {{ $data->street ? $data->street.', ' : null  }} {{ $data->house_no_zone_purok_sitio ? $data->house_no_zone_purok_sitio.', ' : null  }} {{ $data->barangay ? "Brgy. ".$data->barangay->barangay_name.', ' : null }} {{ $data->municipality->municipality_name }}, Leyte, {{ $data->postal_code }}   </span>
      is found to be a qualified marginalized end-user under Rule 6. Section 1(a) of the Implementing Rules and Regulations of Republic Act No. 11552. 
      Therefore, Leyte V Electric Cooperative, Inc. (LEYECO V) is hereby granting a Lifeline Rate in favor of the above qualified marginalized end-user 
      subject to the consumption threshold as approved by the Energy Regulatory Commission (ERC). 
    </p>

    <p>
      In case of transfer of residence, the above qualified marginalized end­user shall inform Leyte V Electric Cooperative, Inc. (LEYECO V), for issuance of Certificate of Un-Tagging in case it will be served by a different Distribution Utility.  
    </p>

    <p>
      The validity of the grant of Lifeline Rate shall be from <span class="text-underline"> {{ date('F d, Y', strtotime($data->date_of_application)) }}</span> until delisted, without prejudice to the responsibility of the Leyte V Electric Cooperative, Inc. (LEYECO V) to conduct, at any time, a validation to ensure that the said qualified marginalized end-user is still eligible for the grant of lifeline rate as provided for under RA 11552 and its IRR.  
    </p>
  </div>

  <div class="div" style="padding-top: 70px; position: relative;">
    {{-- <img src="{{ public_path('images/analou_e_signature.png') }}" alt="" class="img-signature"> --}}
    <h4 class="text-center" style="position: relative;">
      Ana Maria Lourdes M. Pastor, MBM
    </h4>
    <p class="text-center" style="text-decoration: overline; padding: 0px; margin-top: -10px; position: relative;">
      Institutional Services Department Manager
    </p>
  </div>

  <div class="div" style="padding-top: 50px;">
    <h4 class="text-center">
      {{ date('F d, Y', strtotime($data->date_of_application)) }}
    </h4>
    <p class="text-center" style="text-decoration: overline;">
      Date of Issuance
    </p>
  </div>

  <div class="div" style="padding-top: 20px;">
    <h4 style="text-align: left !important">
      Conforme:
    </h4>
  </div>

  <div class="div">
    <p style="padding-left: 55px; text-transform: uppercase; ">{{$data->first_name}} {{$data->middle_name}} {{$data->last_name}}</p>
    <h4 style="text-decoration: overline; text-align: left !important">
      Name and Signature of the Qualified Marginalized End-User
    </h4>
  </div>

  <div class="div">
    <p style="text-align: left !important">
      Any person who will be found falsifying this document shall be held liable under existing laws.
    </p>
  </div>
</body>
</html>