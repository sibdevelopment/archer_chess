<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zoom Users</title>

  <link rel="stylesheet" href="/frontend/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/frontend/assets/plugins/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="/frontend/assets/css/feather.css">
  <link rel="stylesheet" href="/frontend/assets/plugins/boxicons/css/boxicons.min.css">
  <link rel="stylesheet" href="/frontend/assets/css/style.css">
  <style>
    body {
      background-color: #f4f6f9;
    }
    .card {
      border: none;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      border-radius: 12px;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .table-hover tbody tr:hover {
      background-color: #f1f1f1;
    }
    .table-responsive {
        overflow-x: auto;
        overflow-y: visible;
    }
  </style>
</head>

<body class="py-5" style="background-color: #f4f6f9;">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card p-4">
          <h4 class="mb-4 text-center">Zoom Users</h4>
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
              <thead class="thead-dark">
                <tr>
                  <th>Sr No.</th>
                  <th>Email</th>
                  <th>Zoom User ID</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($zoomUsers as $zoomUser)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $zoomUser['email'] }}</td>
                    <td>
    {{ $zoomUser['id'] }}
    <a href="javascript:void(0);" onclick="copyToClipboard('{{ $zoomUser['id'] }}')" class="ms-2 text-primary" title="Copy ID">
        <i class="fas fa-copy"></i>
    </a>
</td>

                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center">No Zoom users found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Copied to clipboard: ' + text);
        }, function(err) {
            alert('Failed to copy text');
        });
    }
</script>

</html>
