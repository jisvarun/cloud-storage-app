<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
        @vite('resources/css/app.css')
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

        <form action="{{ route('students.index') }}" method="GET" class="row g-2 align-items-end mb-4">
            <div class="col-md-8 col-lg-6">
                <label for="search" class="form-label">Search students</label>
                <input type="search" class="form-control" id="search" name="search"
                    value="{{ $search }}" placeholder="Search by name, email, or mobile">
            </div>
            <div class="col-md-4 col-lg-3">
                <label for="sort" class="form-label">Sort by</label>
                <select class="form-select" id="sort" name="sort">
                    <option value="created_at" @selected($sort === 'created_at')>Created</option>
                    <option value="full_name" @selected($sort === 'full_name')>Full Name</option>
                    <option value="email" @selected($sort === 'email')>Email</option>
                    <option value="mobile" @selected($sort === 'mobile')>Mobile</option>
                </select>
            </div>
            <div class="col-md-4 col-lg-2">
                <label for="direction" class="form-label">Direction</label>
                <select class="form-select" id="direction" name="direction">
                    <option value="asc" @selected($direction === 'asc')>Ascending</option>
                    <option value="desc" @selected($direction === 'desc')>Descending</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Apply</button>
            </div>
            @if ($search !== '' || $sort !== 'created_at' || $direction !== 'desc')
                <div class="col-auto">
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            @endif
        </form>

        @if ($students->isEmpty())
            <div class="alert alert-info">
                @if ($search !== '')
                    No students matched "{{ $search }}".
                @else
                    No students found. Add a student to see them here.
                @endif
            </div>
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
                                <td>{{ $students->firstItem() + $loop->index }}</td>
                                <td><img src="{{ $student->photo }}" alt="Photo"
                                        style="max-width: 100px; max-height: 100px; object-fit: cover;"></td>
                                <td><img src="{{ $student->sign }}" alt="Signature"
                                        style="max-width: 120px; max-height: 80px; object-fit: contain;"></td>
                                <td>{{ $student->full_name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->mobile ?? '-' }}</td>
                                <td>{{ $student->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div>
                {{ $students->links() }}
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>

</html>
