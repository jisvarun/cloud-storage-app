<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  </head>
  <body>
    <div class="container py-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Student List</h1>
        <a href="{{ route('students.create') }}" class="btn btn-primary">Add Student</a>
      </div>

      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if ($students->isEmpty())
        <div class="alert alert-info">No students found. Add a student to see them here.</div>
      @else
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Photo</th>
                <th>Signature</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Created</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($students as $student)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td><img src="{{ $student->photo }}" alt="Photo" style="max-width: 100px; max-height: 100px; object-fit: cover;"></td>
                  <td><img src="{{ $student->sign }}" alt="Signature" style="max-width: 120px; max-height: 80px; object-fit: contain;"></td>
                  <td>{{ $student->full_name }}</td>
                  <td>{{ $student->email }}</td>
                  <td>{{ $student->mobile ?? '-' }}</td>
                  <td>{{ $student->created_at->format('Y-m-d H:i') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>
