








@extends('main.layout')
    @section('body')
        <div class="container">
            <h1>Müəllif Listi</h1>
            <table>
                <thead>
                <tr>
                    <th>Ad</th>
                    <th>Email</th>
                    <th>Vebsayt</th>
                </tr>
                </thead>
                <tbody>
                @foreach($authors as $author)
                    <tr>
                        <td>{{ $author->name }}</td>
                        <td>{{ $author->email }}</td>
                        <td>{{ $author->site }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endsection





