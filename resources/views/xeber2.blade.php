@extends('main.layout')
@section('body')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100">
            <div class="col-md-8 mx-auto">

                <!-- Form üçün kart -->
                <div class="card card-danger card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Yeni Xəbər Əlavə Et</h3>
                    </div>
                    <form method="POST" action="{{ route('news.store') }}">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Xəbərin başlığını daxil edin" required>
                            </div>
                            <div class="mb-3">
                                <label for="text" class="form-label">Text</label>
                                <textarea class="form-control" id="text" name="text" rows="5" placeholder="Xəbərin mətnini daxil edin" required></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-danger">Xəbəri Əlavə Et</button>
                        </div>
                    </form>
                </div>

                <!-- Xəbərlər siyahısı -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Xəbərlər Siyahısı</h3>
                    </div>
                    <div class="card-body">
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
                            @if(isset($news) && $news->count() > 0)
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
                            @else
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
        </div>
    </div>
@endsection
