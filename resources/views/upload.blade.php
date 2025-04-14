<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload DOCX</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Upload File DOCX</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('processDocument') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="docx_file" class="form-label">Pilih File DOCX:</label>
            <input type="file" class="form-control" name="docx_file" accept=".docx" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload & Convert</button>
    </form>
</div>
</body>
</html>
