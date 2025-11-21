@extends('main.layout')
@section('body')

    <!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müəssisə Qeydiyyat Formu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }



        .form-container {




            width: 100%;
            max-width: 450px;
            margin-left: 550px;
            margin-top: 250px;

        }

        .form-group {
            margin-bottom: 20px;
            width: 100%;

        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 16px;
            transition: border-color 0.3s;
            border-radius: 20px;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
            border-radius: 20px;
        }

        .btn:hover {
            background: #5a6fd8;
        }
    </style>
</head>
<body>
<div class="form-container">

    <!-- muellif-ad.blade.php -->
    <form action="{{ route('muellif.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <input type="text" name="companyName" placeholder="Müəssisə Adı" required>
        </div>

        <div class="form-group">
            <input type="email" name="email" placeholder="E-poçt Ünvanı" required>
        </div>

        <div class="form-group">
            <input type="text" name="website" placeholder="Vebsayt">
        </div>

        <button type="submit" class="btn">Qeydiyyatdan Keç</button>

    </form>


</div>
</body>
</html>

@endsection
