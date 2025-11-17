@extends('main.layout')
@section('body')
    <!DOCTYPE html>
<html>
<head>
    <title>Yeni Kategoriya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Yeni Kategoriya Elave Et</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('category.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Kategoriya Adı:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Elave Et</button>
    </form>
</div>
</body>
</html>
@endsection
