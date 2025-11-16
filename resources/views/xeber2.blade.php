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
                        <form method="POST" action="{{ route('news.store') }}?page={{ $news->currentPage() }}" id="newsForm" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                           placeholder="Xəbərin başlığını daxil edin">
                                    <div class="form-text">
                                        <span id="titleCount">0</span>/15 xarakter
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="text" class="form-label">Text</label>
                                    <textarea class="form-control" id="text" name="text" rows="5"
                                              placeholder="Xəbərin mətnini daxil edin"></textarea>
                                    <div class="form-text">
                                        <span id="textCount">0</span>/50 xarakter
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="author" class="form-label">Müəllif <small class="text-muted">(məcburi)</small></label>
                                    <input type="text" class="form-control" id="author" name="author"
                                           placeholder="Müəllifin adını daxil edin"
                                           value="{{ old('author') }}" required>
                                    <div class="invalid-feedback" id="authorError">
                                        Müəllif adı daxil edilməlidir
                                    </div>
                                </div>


                                <!-- Şəkil yükləmə hissəsi -->
                                <div class="mb-3">
                                    <label for="image" class="form-label">Şəkil</label>
                                    <input type="file" class="form-control" id="image" name="image"
                                           accept="image/jpeg,image/png,image/jpg,image/gif">
                                    <div class="form-text">
                                        JPG, PNG, GIF formatları. Maksimum ölçü: 2MB
                                    </div>
                                    <div class="invalid-feedback" id="imageError">
                                        Şəkil formatı və ya ölçüsü düzgün deyil
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
                                        <th>Şəkil</th>
                                        <th>Xəbərin adı</th>
                                        <th>Mətn</th>
                                        <th style="width: 150px">Əməliyyatlar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($news) && $news->count() > 0)
                                        @php
                                            $totalNews = $news->total();
                                            $currentPage = $news->currentPage();
                                            $perPage = $news->perPage();
                                            $startNumber = ($currentPage - 1) * $perPage + 1;
                                        @endphp
                                        @foreach($news as $index => $item)
                                            <tr class="align-middle" id="news-row-{{ $item->id }}">
                                                <td>{{ $startNumber + $index }}</td>
                                                <td>
                                                    @if($item->image)
                                                        <img src="{{ asset('storage/' . $item->image) }}"
                                                             alt="{{ $item->title }}"
                                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                    @else
                                                        <span class="text-muted">Şəkil yoxdur</span>
                                                    @endif
                                                </td>
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
                                            <td colspan="5" class="text-center text-muted">
                                                Heç bir xəbər tapılmadı.
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>

                            <!-- Pagination -->
                            @if(isset($news) && $news->hasPages())
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        Göstərilir {{ $news->firstItem() }} - {{ $news->lastItem() }} / Cəmi: {{ $news->total() }} xəbər
                                    </div>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination pagination-sm mb-0">
                                            <!-- İlk səhifə -->
                                            <li class="page-item {{ $news->onFirstPage() ? 'disabled' : '' }}">
                                                <a class="page-link" href="{{ $news->url(1) }}" title="İlk səhifə">
                                                    &laquo;&laquo;
                                                </a>
                                            </li>

                                            <!-- Əvvəlki səhifə -->
                                            @if ($news->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">&laquo;</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $news->previousPageUrl() }}" rel="prev">&laquo;</a>
                                                </li>
                                            @endif

                                            <!-- Səhifə nömrələri -->
                                            @php
                                                $start = max(1, $news->currentPage() - 2);
                                                $end = min($news->lastPage(), $news->currentPage() + 2);
                                            @endphp

                                            @if($start > 1)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif

                                            @for ($page = $start; $page <= $end; $page++)
                                                @if ($page == $news->currentPage())
                                                    <li class="page-item active">
                                                        <span class="page-link">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $news->url($page) }}">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endfor

                                            @if($end < $news->lastPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif

                                            <!-- Sonrakı səhifə -->
                                            @if ($news->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $news->nextPageUrl() }}" rel="next">&raquo;</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link">&raquo;</span>
                                                </li>
                                            @endif

                                            <!-- Son səhifə -->
                                            <li class="page-item {{ !$news->hasMorePages() ? 'disabled' : '' }}">
                                                <a class="page-link" href="{{ $news->url($news->lastPage()) }}" title="Son səhifə">
                                                    &raquo;&raquo;
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            @endif
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
                const form = document.getElementById('newsForm');
                const submitBtn = document.getElementById('submitBtn');

                // Title xarakter sayını sadəcə göstər
                titleInput.addEventListener('input', function() {
                    const length = this.value.length;
                    titleCount.textContent = length;
                });

                // Text xarakter sayını sadəcə göstər
                textInput.addEventListener('input', function() {
                    const length = this.value.length;
                    textCount.textContent = length;
                });

                // Form göndərilmədən əvvəl YALNIZ "Edumediya/Edumedia" yoxla
// Form göndərilmədən əvvəl bütün "Edumedia/Edumediya" variantlarını yoxla
                form.addEventListener('submit', function(e) {
                    const titleValue = titleInput.value;

                    // BÜTÜN VERSİYALARI YOXLA
                    if (titleValue.includes('edumedia') ||
                        titleValue.includes('edumediya') ||
                        titleValue.includes('Edumedia') ||
                        titleValue.includes('Edumediya')) {
                        e.preventDefault();
                        titleInput.classList.add('is-invalid');

                        // Xüsusi xəta mesajı yarat
                        const specialError = document.createElement('div');
                        specialError.className = 'invalid-feedback';
                        specialError.id = 'specialError';
                        specialError.style.display = 'block';
                        specialError.textContent = 'Xəbər adında "Edumedia" və ya "Edumediya" sözləri ola bilməz!';

                        // Əgər artıq xüsusi xəta yoxdursa, əlavə et
                        if (!document.getElementById('specialError')) {
                            titleInput.parentNode.appendChild(specialError);
                        }

                        titleInput.focus();
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
                                const rows = tbody.querySelectorAll('tr:not(.text-muted)');
                                if (rows.length === 0) {
                                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Heç bir xəbər tapılmadı.
                            </td>
                        </tr>
                    `;
                                }

                                // Sıra nömrələrini yenilə
                                updateRowNumbers();

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

                // Sıra nömrələrini yenilə
                function updateRowNumbers() {
                    const rows = document.querySelectorAll('tbody tr:not(.text-muted)');
                    const currentPage = {{ $news->currentPage() ?? 1 }};
                    const perPage = {{ $news->perPage() ?? 10 }};
                    const startNumber = (currentPage - 1) * perPage + 1;

                    rows.forEach((row, index) => {
                        row.querySelector('td:first-child').textContent = startNumber + index;
                    });
                }
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
            .pagination {
                margin-bottom: 0;
            }
            .page-link {
                color: #dc3545;
            }
            .page-item.active .page-link {
                background-color: #dc3545;
                border-color: #dc3545;
            }
        </style>
    @endsection
