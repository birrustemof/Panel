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
                    <form method="POST" action="{{ route('news.store') }}" id="newsForm">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <small class="text-muted">(ən az 15 xarakter)</small></label>
                                <input type="text" class="form-control" id="title" name="title"
                                       placeholder="Xəbərin başlığını daxil edin"
                                >
                                <div class="form-text">
                                    <span id="titleCount">0</span>/255 xarakter (minimum 15)
                                </div>
                                <div class="invalid-feedback" id="titleError">
                                    Title ən az 15 xarakter olmalıdır
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="text" class="form-label">Text <small class="text-muted">(ən az 10 xarakter)</small></label>
                                <textarea class="form-control" id="text" name="text" rows="5"
                                          placeholder="Xəbərin mətnini daxil edin"
                                          minlength="10" required></textarea>
                                <div class="form-text">
                                    <span id="textCount">0</span> xarakter (minimum 10)
                                </div>
                                <div class="invalid-feedback" id="textError">
                                    Text ən az 10 xarakter olmalıdır
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-danger" id="submitBtn">Xəbəri Əlavə Et</button>
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

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
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

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Xəbərin adı</th>
                                <th>Mətn</th>
                                <th style="width: 150px">Əməliyyatlar</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($news) && $news->count() > 0)
                                @foreach($news as $item)
                                    <tr class="align-middle" id="news-row-{{ $item->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('forms.general', ['id' => $item->id]) }}">
                                                {{ $item->title }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ Str::limit($item->text, 50) }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Edit button -->
                                                <a href="{{ route('forms.general', ['id' => $item->id]) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="Redaktə et">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Delete button -->
                                                <button type="button"
                                                        class="btn btn-danger btn-sm delete-news"
                                                        data-id="{{ $item->id }}"
                                                        data-title="{{ $item->title }}"
                                                        title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xəbəri Sil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>"<span id="news-title"></span>" adlı xəbəri silmək istədiyinizə əminsiniz?</p>
                    <p class="text-danger"><strong>Bu əməliyyat geri alına bilməz!</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ləğv et</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Sil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const textInput = document.getElementById('text');
            const titleCount = document.getElementById('titleCount');
            const textCount = document.getElementById('textCount');
            const titleError = document.getElementById('titleError');
            const textError = document.getElementById('textError');
            const form = document.getElementById('newsForm');
            const submitBtn = document.getElementById('submitBtn');

            // Title xarakter sayını göstər
            titleInput.addEventListener('input', function() {
                const length = this.value.length;
                titleCount.textContent = length;

                if (length < 15) {
                    this.classList.add('is-invalid');
                    titleError.style.display = 'block';
                } else {
                    this.classList.remove('is-invalid');
                    titleError.style.display = 'none';
                }
            });

            // Text xarakter sayını göstər
            textInput.addEventListener('input', function() {
                const length = this.value.length;
                textCount.textContent = length;

                if (length < 10) {
                    this.classList.add('is-invalid');
                    textError.style.display = 'block';
                } else {
                    this.classList.remove('is-invalid');
                    textError.style.display = 'none';
                }
            });

            // Form göndərilmədən əvvəl yoxla
            form.addEventListener('submit', function(e) {
                const titleLength = titleInput.value.length;
                const textLength = textInput.value.length;

                if (titleLength < 15) {
                    e.preventDefault();
                    titleInput.classList.add('is-invalid');
                    titleError.style.display = 'block';
                    titleInput.focus();
                    return false;
                }

                if (textLength < 10) {
                    e.preventDefault();
                    textInput.classList.add('is-invalid');
                    textError.style.display = 'block';
                    textInput.focus();
                    return false;
                }

                // Əgər hər şey yaxşıdırsa, düyməni disable et
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Göndərilir...';
            });

            // Delete functionality
            const deleteButtons = document.querySelectorAll('.delete-news');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const deleteForm = document.getElementById('deleteForm');
            const newsTitleSpan = document.getElementById('news-title');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const newsId = this.getAttribute('data-id');
                    const newsTitle = this.getAttribute('data-title');

                    newsTitleSpan.textContent = newsTitle;
                    deleteForm.action = `/news/${newsId}`;

                    deleteModal.show();
                });
            });

            // AJAX delete with page refresh
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const url = form.action;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                })
                    .then(response => {
                        if (response.ok) {
                            // Close modal
                            deleteModal.hide();

                            // Show success message
                            const successAlert = document.createElement('div');
                            successAlert.className = 'alert alert-success alert-dismissible fade show';
                            successAlert.innerHTML = `
                            <strong>Uğur!</strong> Xəbər uğurla silindi.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;

                            // Insert alert at the top of card body
                            const cardBody = document.querySelector('.card-body');
                            cardBody.insertBefore(successAlert, cardBody.firstChild);

                            // Remove the row from table
                            const newsId = url.split('/').pop();
                            const row = document.getElementById(`news-row-${newsId}`);
                            if (row) {
                                row.remove();
                            }

                            // If no news left, show message
                            const tbody = document.querySelector('tbody');
                            if (tbody.children.length === 1 && tbody.children[0].querySelector('.text-muted')) {
                                // Already showing no news message
                            } else if (tbody.children.length === 0) {
                                tbody.innerHTML = `
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Heç bir xəbər tapılmadı.
                                    </td>
                                </tr>
                            `;
                            }

                            // Auto remove success message after 3 seconds
                            setTimeout(() => {
                                if (successAlert.parentNode) {
                                    successAlert.remove();
                                }
                            }, 3000);

                        } else {
                            throw new Error('Silinmə əməliyyatı uğursuz oldu');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        deleteModal.hide();

                        // Show error message
                        const errorAlert = document.createElement('div');
                        errorAlert.className = 'alert alert-danger alert-dismissible fade show';
                        errorAlert.innerHTML = `
                        <strong>Xəta!</strong> Xəbər silinərkən xəta baş verdi.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;

                        const cardBody = document.querySelector('.card-body');
                        cardBody.insertBefore(errorAlert, cardBody.firstChild);

                        setTimeout(() => {
                            if (errorAlert.parentNode) {
                                errorAlert.remove();
                            }
                        }, 3000);
                    });
            });
        });
    </script>

    <style>
        .invalid-feedback {
            display: none;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .btn-group .btn {
            margin: 0 2px;
        }
    </style>
@endsection
