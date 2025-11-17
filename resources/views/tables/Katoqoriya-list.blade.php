<!DOCTYPE html>
<html>
<head>
    <title>Kategoriya Listi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Kategoriya Listi</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- BU HİSSƏNI YOXLA -->
    @if(isset($categories) && $categories->count() > 0)
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Tarix</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-warning">Heç bir kategoriya tapılmadı.</div>
    @endif
</div>
</body>
</html>
