@extends('main.layout')
@section('body')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100">
            <div class="col-md-6 mx-auto">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <div class="card-title">Xəbər Məlumatları</div>
                    </div>

                    <!-- Success mesajı -->
                    @if(session('success'))
                        <div class="alert alert-success m-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('forms.general.update', $newsItem->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="newsId" class="form-label">Id</label>
                                <input type="text" class="form-control" id="newsId" value="{{ $newsItem->id }}" readonly>
                                <div class="form-text">Xəbərin ID nömrəsi</div>
                            </div>
                            <div class="mb-3">
                                <label for="newsTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" id="newsTitle" name="title" value="{{ $newsItem->title }}">
                            </div>
                            <div class="mb-4">
                                <label for="newsText" class="form-label">Text</label>
                                <textarea class="form-control" id="newsText" name="text" rows="4">{{ $newsItem->text }}</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-danger">Yenilə</button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Geri</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
