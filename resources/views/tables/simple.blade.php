@extends('main.layout')
@section('body')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100">
            <div class="col-md-10 mx-auto">
                <div class="card mb-4">

                    <div class="card-header">
                        <h3 class="card-title">Xəbərlər Cədvəli</h3>
                        <div class="card-tools">
                            <a href="{{ route('news.export') }}" class="btn btn-success btn-sm me-2">
                                <i class="fas fa-file-excel"></i> Excel Export
                            </a>
                            <a href="{{ route('news.deleted') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-trash"></i> Silinmiş Xəbərlər
                            </a>
                        </div>
                    </div>
                    <div class="card-body">

                        <!-- Axtarış Formu -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form action="{{ route('simple') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text"
                                               name="search"
                                               class="form-control"
                                               placeholder="Xəbər adı və ya mətnində axtar..."
                                               value="{{ $search ?? '' }}">
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="fas fa-search"></i> Axtar
                                        </button>
                                        @if(isset($search) && $search)
                                            <a href="{{ route('simple') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times"></i> Təmizlə
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Success mesajı -->
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

                        <!-- Axtarış nəticəsi info -->
                        @if(isset($search) && $search)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                "{{ $search }}" üçün axtarış nəticələri:
                                <strong>{{ $news->total() }}</strong> nəticə tapıldı
                            </div>
                        @endif

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="width: 80px">Şəkil</th>
                                <th>Xəbərin adı</th>
                                <th>Müəllif</th>
                                <th>Mətn</th>
                                <th style="width: 150px">Əməliyyatlar</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($news) && $news->count() > 0)
                                @php
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
                                                <span class="text-muted" style="font-size: 12px;">Şəkil yoxdur</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('forms.general', ['id' => $item->id]) }}">
                                                {{ $item->title }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $item->author ?? 'Müəllif yoxdur' }}
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
                                    <td colspan="6" class="text-center text-muted">
                                        @if(isset($search) && $search)
                                            "{{ $search }}" üçün heç bir xəbər tapılmadı.
                                        @else
                                            Heç bir xəbər tapılmadı.
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        <!-- Pagination - DÜZƏLDİLMİŞ HİSSƏ -->
                        @if(isset($news) && $news->hasPages())
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Göstərilir {{ $news->firstItem() }} - {{ $news->lastItem() }} / Cəmi: {{ $news->total() }} xəbər
                                </div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination pagination-sm mb-0">
                                        <!-- İlk səhifə -->
                                        <li class="page-item {{ $news->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $news->url(1) }}{{ isset($search) && $search ? '?search=' . urlencode($search) : '' }}" title="İlk səhifə">
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
                                                @php
                                                    $prevUrl = $news->previousPageUrl();
                                                    if (isset($search) && $search) {
                                                        $prevUrl .= (str_contains($prevUrl, '?') ? '&' : '?') . 'search=' . urlencode($search);
                                                    }
                                                @endphp
                                                <a class="page-link" href="{{ $prevUrl }}" rel="prev">&laquo;</a>
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
                                                @php
                                                    $pageUrl = $news->url($page);
                                                    if (isset($search) && $search) {
                                                        $pageUrl .= (str_contains($pageUrl, '?') ? '&' : '?') . 'search=' . urlencode($search);
                                                    }
                                                @endphp
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $pageUrl }}">{{ $page }}</a>
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
                                                @php
                                                    $nextUrl = $news->nextPageUrl();
                                                    if (isset($search) && $search) {
                                                        $nextUrl .= (str_contains($nextUrl, '?') ? '&' : '?') . 'search=' . urlencode($search);
                                                    }
                                                @endphp
                                                <a class="page-link" href="{{ $nextUrl }}" rel="next">&raquo;</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">&raquo;</span>
                                            </li>
                                        @endif

                                        <!-- Son səhifə -->
                                        <li class="page-item {{ !$news->hasMorePages() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $news->url($news->lastPage()) }}{{ isset($search) && $search ? '?search=' . urlencode($search) : '' }}" title="Son səhifə">
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
                                    <td colspan="6" class="text-center text-muted">
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
        .table img {
            border: 1px solid #dee2e6;
        }
        .card-tools {
            position: absolute;
            right: 1rem;
            top: 1rem;
        }
        .input-group .btn {
            border-radius: 0 0.375rem 0.375rem 0;
        }
        .card-tools {
            position: absolute;
            right: 1rem;
            top: 1rem;
        }
        .card-tools .btn {
            margin-left: 0.5rem;
        }
    </style>
@endsection
