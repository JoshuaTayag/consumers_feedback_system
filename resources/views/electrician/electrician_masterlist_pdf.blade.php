<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    @php use Carbon\Carbon; @endphp
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://fonts.cdnfonts.com/css/franklin-gothic" rel="stylesheet">
    <title>TSD Form</title>
    <style>
        header {
            font-family: 'Franklin Gothic', sans-serif;
            position: relative;
        }

        .heading {
            text-align: center;
            margin: 10px;
        }

        .sub-heading {
            text-align: center;
            font-size: 15px;
        }

        hr,
        .blue {
            padding: 0x;
            margin: 0px;
            border: 2px solid blue;
        }

        hr,
        .yellow {
            padding: 0x;
            margin: 0px;
            border: 2px solid yellow;
        }

        hr,
        .black {
            padding: 0x;
            margin: 0px;
            border: 1px solid black;
        }

        .img-logo {
            position: absolute;
            top: 25px;
            left: 20px;
            height: 80px;
            width: 80px;
        }

        .img-iso {
            position: absolute;
            top: 25px;
            right: 10px;
            height: 80px;
            width: 100px;
        }

        .img-signature {
            height: 70px;
            width: 150px;
            margin-bottom: -15px;
        }

        .text-center {
            text-align: center;
        }

        .text-center,
        h3,
        h4 {
            text-align: center;
            /* line-height: 0.3; */
        }

        .text-underline {
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

        .styled-table,
        th,
        td {
            border: 1px solid rgb(0, 0, 0);
        }
    </style>
</head>

<body>
    <header>
        <img src="{{ public_path('images/logo.png') }}" alt="" class="img-logo">
        <img src="{{ public_path('images/iso_2025.JPg') }}" alt="" class="img-iso">
        <h2 class="heading">LEYTE V ELECTRIC COOPERATIVE, INC.</h2>
        <p class="sub-heading">
            (LEYECO V)<br>
            Brgy. San Pablo, Ormoc City, Leyte
        </p>
        @php
            $date_of_application_from = request('date_of_application_from');
            $date_of_application_to = request('date_of_application_to');
        @endphp
        <h3 class="text-center">LIST OF ACCREDITED BARANGAY ELECTRICIANS</h3>
        @if (!empty($date_of_application_from))
            <p class="text-center" style="font-size: 14px;">
                From {{ Carbon::parse($date_of_application_from)->format('M d, Y') }} -
                {{ Carbon::parse($date_of_application_to)->format('M d, Y') }}
            </p>
        @else
            <p class="text-center" style="font-size: 14px;">
                as of {{ Carbon::parse($date_of_application_from)->format('M d, Y') }}
            </p>
        @endif
    </header>
    {{-- <hr class="blue">
  <hr class="yellow"> --}}

    <div class="div" style="margin-top: 10px; margin-bottom: 0px; padding-bottom: 0px;">
        {{-- {{dd($electricians)}} --}}
        <table class="styled-table" style="font-size: 14px; width: 100%; padding-top: 15px; padding-bottom: 0px;">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Contact Number</th>
                    <th>Date of Application</th>
                    <th>Date of Expiration</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($electricians as $electrician)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            {{ $electrician->last_name }},
                            {{ $electrician->first_name }}
                            @if (!empty($electrician->middle_name))
                                {{ strtoupper(substr($electrician->middle_name, 0, 1)) }}.
                            @endif
                        </td>
                        <td>
                            @php
                                if (count($electrician->electrician_addresses) != 0) {
                                    echo $electrician->electrician_addresses[0]->district->district_name . ', ';
                                    echo $electrician->electrician_addresses[0]->municipality->municipality_name . ', ';
                                    echo $electrician->electrician_addresses[0]->barangay->barangay_name;
                                }
                            @endphp
                        </td>
                        <td>{{ $electrician->primary_contact_no }}</td>
                        <td>{{ Carbon::parse($electrician->date_of_application)->format('m/d/Y') }}</td>
                        <td>
                            @php
                                $expiration = Carbon::parse($electrician->date_of_application)->addYear();
                            @endphp
                            {{ $expiration->format('m/d/Y') }}{{ $expiration->lt(now()) ? ' - Expired' : '' }}
                        </td>
                        <td>
                            @if ($electrician->application_status == 1)
                                Pending
                            @elseif ($electrician->application_status == 2)
                                Approved
                            @elseif ($electrician->application_status == 3)
                                Disapproved
                            @else
                                Unknown
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="div" style="margin-top: 50px; margin-bottom: 0px; padding-bottom: 0px;">
        {{-- <h2 class="text-center" style="padding-bottom: 0px; margin-bottom: 10px;">
    REQUEST FOR CHANGE METER
    </h2> --}}


        <table style="font-size: 12px; width: 100%; margin-bottom: 40px; table-layout: fixed; border: none;">
            <tbody>
                <tr>
                    <td class="text-center" style="font-size: 14px; width: 33%; border: none;">Prepared By:</td>
                    <td class="text-center" style="font-size: 14px; width: 33%; border: none;">Noted By:</td>
                    <td class="text-center" style="font-size: 14px; width: 34%; border: none;">Approved By:</td>
                </tr>
            </tbody>
        </table>
        <table style="font-size: 12px; width: 100%;">
            <tbody>
                <tr>
                    <td class="text-center" style="text-decoration: underline; font-size: 14px; width: 33%; border: none;">NESTOR A. RANOCO JR.</td>
                    <td class="text-center" style="text-decoration: underline; font-size: 14px; width: 33%; border: none;">GHANDA R.
                        BERNANDINO</td>
                    <td class="text-center" style="text-decoration: underline; font-size: 14px; width: 33%; border: none;">ANA MARIA
                        LOURDES M. PASTOR, MBM</td>
                </tr>
                <tr>
                    <td class="text-center" style="font-size: 14px; width: 33%; border: none;">HW&M SPECIALIST</td>
                    <td class="text-center" style="font-size: 14px; width: 33%; border: none;">MSD CHIEF</td>
                    <td class="text-center" style="font-size: 14px; width: 33%; border: none;">ISD MANAGER</td>
                </tr>
            </tbody>
        </table>
    </div>
    <footer style="position: fixed; bottom: 20px; left: 0; width: 100%;">
      <p style="text-align:right; margin-right: 40px; font-size: 12px;">
      Date and Time Generated: {{ date('m/d/Y h:i:a') }}
      </p>
    </footer>
</body>

</html>
