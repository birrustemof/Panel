@extends('main.layout')
@section('body')
    <div class="col-md-6 container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Bordered Table</h3>
            </div>
            <div class="card-body">

                <!-- Success mesajı -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Xəbərin adı</th>
                        <th>Əməliyyatlar</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($news as $item)
                        <tr class="align-middle">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('forms.general', ['id' => $item->id]) }}">
                                    {{ $item->title }}
                                </a>
                            </td>
                            <td>
                                {{ $item->text }}
                            </td>
                        </tr>
                    @endforeach

                    @if($news->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Heç bir xəbər tapılmadı.
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
