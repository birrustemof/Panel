@extends('main.layout')
@section('body')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100">
            <div class="col-md-8 mx-auto">
                <!-- Xəbəri yeniləmək üçün form -->
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Xəbəri Yenilə</h3>
                    </div>
                    <form method="POST" action="{{ route('forms.general.update', $newsItem->id) }}" id="updateForm">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="title" class="form-label">Title <small class="text-muted">(ən az 15 xarakter)</small></label>
                                <input type="text" class="form-control" id="title" name="title"
                                       value="{{ old('title', $newsItem->title) }}"
                                       placeholder="Xəbərin başlığını daxil edin">
                                <div class="form-text">
                                    <span id="titleCount">{{ strlen($newsItem->title) }}</span>/255 xarakter (minimum 15)
                                </div>
                                <div class="invalid-feedback" id="titleError">
                                    Title ən az 15 xarakter olmalıdır
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="text" class="form-label">Text <small class="text-muted">(ən az 10 xarakter)</small></label>
                                <textarea class="form-control" id="text" name="text" rows="5"
                                          placeholder="Xəbərin mətnini daxil edin">{{ old('text', $newsItem->text) }}</textarea>
                                <div class="form-text">
                                    <span id="textCount">{{ strlen($newsItem->text) }}</span> xarakter (minimum 10)
                                </div>
                                <div class="invalid-feedback" id="textError">
                                    Text ən az 10 xarakter olmalıdır
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('xeber2') }}" class="btn btn-secondary">Geri</a>
                            <button type="submit" class="btn btn-danger" id="updateBtn">Xəbəri Yenilə</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
