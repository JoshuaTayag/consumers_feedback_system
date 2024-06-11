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
    .text-end{
      text-align: left;
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
    #padding-10{
      padding-top: 10px;
      padding-bottom: 5px;
    }
    
  </style>
</head>
<body>
  <div class="text-align" style="margin-top: 0px; padding-top: 0px;">
    <table class="styled-table" style="font-size: 12px; width: 100%; padding-bottom: 40px;" >
      <tbody>
        <tr>
          <th colspan='7'><h3>LEYECO V 43rd Annual General Membership Meeting</h3></th>
        </tr>
        <tr>
          <th>No.</th>
          <th>Membership No.</th>
          <th>Account Name of MCO</th>
          <th>Account No.</th>
          <th>Date</th>
          <th>Venue</th>
          <th>Remarks</th>
        </tr>
        @foreach ($consumers as $index => $consumer)               
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $consumer->{'OR No'} }}</th>
            <td>{{ $consumer->Name }}</td>
            <td>{{ substr($consumer->{'Accnt No'}, 0, 2) }}-{{ substr($consumer->{'Accnt No'}, 2, 4) }}-{{ substr($consumer->{'Accnt No'}, 6, 4) }}</td>
            <td>07/13/2024</td>
            <td>Ormoc Super Dome</td>
            <td>ON-SITE</td>
          </tr>
        @endforeach 
      </tbody>
    </table>
  </div>

  <table style="font-size: 15px; width: 100%; padding-top: 15px; padding-bottom: 30px;">
    <tbody>
      <tr>
        <td style="border: none; width: 33%; position: relative;">
          <p>Prepared By:</p> <br>
          <h4 class="text-center" style="position: relative; text-decoration: underline;">
              Erwin C. Ebcas
          </h4>
          <p class="text-center" style=" padding: 0px; margin-top: -5px; position: relative;">
            ITCS Chief
          </p>
        </td>
        <td style="border: none; width: 33%; position: relative;">
          <p>Reviewed By:</p> <br>
          <h4 class="text-center" style="position: relative; text-decoration: underline;">
              Ana Maria Lourdes M. Pastor
          </h4>
          <p class="text-center" style=" padding: 0px; margin-top: -5px; position: relative;">
            ISD Manager
          </p>
        </td>
        <td style="border: none; width: 33%;">
          <p>Noted By:</p> <br>
          <h4 class="text-center" style="position: relative; text-decoration: underline;">
              Marlon H. Sanico, CPA, MBA
          </h4>
          <p class="text-center" style=" padding: 0px; margin-top: -5px; position: relative;">
            IAD Manager
          </p>
        </td>
      </tr>
    </tbody>
  </table>
  
  <table style="font-size: 15px; width: auto;">
    <tbody>
      <tr>
        <td style="border: 1px solid black;">
          <p>Noted By:</p> <br>
          <h4 class="text-center" style="position: relative; text-decoration: underline;">
              Atty. Jannie Ann J. Dayandayan, CPA
          </h4>
          <p class="text-center" style=" padding: 0px; margin-top: -5px; position: relative;">
            General Manager
          </p>
        </td>
      </tr>
    </tbody>
  </table>

  <p style="text-align:right; margin-top: 80px">Date and Time Generated: {{ date('m/d/Y h:i:a') }}</p>

</body>
</html>