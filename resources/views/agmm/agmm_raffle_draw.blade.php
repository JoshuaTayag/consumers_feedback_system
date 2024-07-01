<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGMM Raffle Draw</title>
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

    <!-- Include SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Include Lottie JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.4/lottie.min.js"></script>

</head>
<body>
<div class="m-5 pt-5">
  <div class="row">
    <div class="col-lg-7">
      <div class="card mx-auto">
        <form method="GET" action="{{ route('agmmRaffle') }}">
          <div class="card-body">
            <div class="container">
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <a href="{{route('home')}}" class="pr-3">
                    <img src="{{asset('images/logo.png')}}" style="width: 70px" alt="...">
                </a>
                <h1 class="my-4" style="margin: 0; padding-left: 15px;">43rd AGMM Raffle Draw</h1>
            </div>

              
              <div class="row">
                <div class="col">
                  <div class="row justify-content-center align-items-center g-2">

                    <div class="col">
                      <div class="mb-2">
                          <label for="reg_type" class="form-label mb-1">Registration Type</label>
                          <select id="reg_type" name="reg_type" class="form-control" required>
                            <option value="" @selected(request('reg_type') == '')></option>
                            <option value="1" @selected(request('reg_type') == 1)>ONLINE REGISTRATION</option>
                            <option value="2" @selected(request('reg_type') == 2)>VERIFIED REGISTRATION</option>
                          </select>
                      </div>
                    </div>

                    <div class="col">
                      <div class="mb-2">
                          <label for="municipality" class="form-label mb-1">Municipality</label>
                          <select id="municipality" name="municipality" class="form-control">
                            <option value="" @selected(request('municipality') == '')>ALL</option>
                              @foreach($ref_areas as $ref_area)
                                  <option value="{{ $ref_area->area_code }}" @selected( $ref_area->area_code == request('municipality') ) >{{ $ref_area->area }} ({{ $ref_area->area_code }})</option>
                              @endforeach
                          </select>
                      </div>
                    </div>

                    <div class="col">
                      <div class="mb-2">
                        <label for="winners_count" class="form-label mb-1">Number of Winners</label>
                        <input type="number" name="winners_count" id="winners_count" value="{{ request('winners_count') }}" class="form-control" required min="1">
                      </div>
                    </div>

                    <div class="col">
                      <button type="submit" id="submit_button" name="submit_button" class="btn btn-primary mt-3">Draw Winner's</button>
                      <a href="{{ route('agmmRaffle') }}" class="btn btn-warning mt-3"><i class="fa fa-eraser"></i> Clear</a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col p-0">
                      <div class="container">
                        <h1 class="my-5 py-2 text-center bg-dark text-white">Winners</h1>

                        @if($winners->isEmpty())
                          <p>No participants found for the selected municipality.</p>
                        @else
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Account No.</th>
                                <th>Account Name</th>
                                <th>Contact No.</th>
                                <th>Membership OR</th>
                                <th>Address</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($winners as $winner)
                              <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ substr_replace(substr_replace($winner->account_no, '-', 2, 0), '-', 7, 0) }}</td>
                                <td>{{ $winner->name }}</td>
                                <td>{{ $winner->contact_no ? $winner->contact_no : 'NONE'}}</td>
                                <td>{{ $winner->membership_or }}</td>
                                <td>{{ $winner->Address }}</td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col">
      <div class="card">
        <div class="card-body">
          <h3 class="text-center">Onsite Raffle Winners</h3>
          <hr>
          <table class="table table-striped">
            <thead>
              <tr class="text-center">
                <th>Account #</th>
                <th>Registered Name</th>
                <th>Mem OR</th>
                <th>Municipality</th>
                <th><i class="fa fa-gear"></i></th>
              </tr>
            </thead>
            <tbody>
              @foreach($all_winners as $all_winner)
                <tr class="text-center">
                  <td>{{ substr_replace(substr_replace($all_winner->account_no, '-', 2, 0), '-', 7, 0) }}</td>
                  <td>{{ $all_winner->name }}</td>
                  <td>{{ $all_winner->membership_or }}</td>
                  <td>{{ $all_winner->area }}</td>
                  <td>
                    <a href="{{ route('agmmRaffleRemove', $all_winner->id) }}" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="card mt-4">
        <div class="card-body">
          <h3 class="text-center">Online Viewer Raffle Winners</h3>
          <hr>
          <table class="table table-striped">
            <thead>
              <tr class="text-center">
                <th>Account #</th>
                <th>Registered Name</th>
                <th>Mem OR</th>
                <th>Municipality</th>
                <th><i class="fa fa-gear"></i></th>
              </tr>
            </thead>
            <tbody>
              @foreach($all_online_winners as $all_online_winner)
                <tr class="text-center">
                  <td>{{ substr_replace(substr_replace($all_online_winner->account_no, '-', 2, 0), '-', 7, 0) }}</td>
                  <td>{{ $all_online_winner->last_name.', '.$all_online_winner->first_name.' '.$all_online_winner->middle_name }}</td>
                  <td>{{ $all_online_winner->membership_or }}</td>
                  <td>{{ $all_online_winner->area }}</td>
                  <td>
                    <a href="{{ route('agmmOnlineRaffleRemove', $all_online_winner->id) }}" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  
</div>
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form[action="{{ route('agmmRaffle') }}"]');
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the form from submitting immediately

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to draw the winners?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, draw winners!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // If confirmed, submit the form
                }
            });
        });

        @if(isset($winners) && !$winners->isEmpty())
            Swal.fire({
                title: 'Congratulations!',
                text: 'Winners have been drawn successfully.',
                iconHtml: '<div id="success-animation" style="width: 1000px; height: 1000px;"></div>',
                showConfirmButton: true,
                didOpen: () => {
                    const animation = lottie.loadAnimation({
                        container: document.getElementById('success-animation'),
                        renderer: 'svg',
                        loop: true,
                        autoplay: true,
                        path: '{{ asset('congratulation.json') }}' // Replace with your Lottie animation JSON file path
                    });
                }
            });
        @endif
    });
</script>
</body>
</html>