@extends('main.layout')
@section('body')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row w-100">
            <div class="col-md-10 mx-auto">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Silinmiş Xəbərlər</h3>
                        <div class="card-tools">
                            <a href="{{ route('simple') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-left"></i> Geri
                            </a>
                        </div>
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
                                <th style="width: 80px">Şəkil</th>
                                <th>Xəbərin adı</th>
                                <th>Müəllif</th> <!-- MÜƏLLİF SÜTUNU ƏLAVƏ EDİN -->
                                <th>Mətn</th>
                                <th>Silinmə Tarixi</th>
                                <th style="width: 150px">Əməliyyatlar</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($deletedNews->count() > 0)
                                @foreach($deletedNews as $index => $item)
                                    <tr class="align-middle">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}"
                                                     alt="{{ $item->title }}"
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                            @else
                                                <span class="text-muted" style="font-size: 12px;">Şəkil yoxdur</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            {{ $item->author ?? 'Müəllif yoxdur' }} <!-- MÜƏLLİF ADI -->
                                        </td>
                                        <td>
                                            {{ Str::limit($item->text, 50) }}
                                        </td>
                                        <td>
                                            {{ $item->deleted_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Bərpa et button -->
                                                <!-- Bərpa et form -->
                                                <form action="{{ route('news.restore', $item->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <!-- @method('PATCH') SİLİN -->
                                                    <button type="submit" class="btn btn-success btn-sm" title="Bərpa et">
                                                        <i class="fas fa-undo"></i> Bərpa et
                                                    </button>
                                                </form>

                                                <!-- Həqiqətən sil button -->
                                                <button type="button"
                                                        class="btn btn-danger btn-sm force-delete-news"
                                                        data-id="{{ $item->id }}"
                                                        data-title="{{ $item->title }}"
                                                        title="Həqiqətən sil">
                                                    <i class="fas fa-trash"></i> Həqiqətən sil
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center text-muted"> <!-- COLSPAN 7 OLDU -->
                                        Heç bir silinmiş xəbər tapılmadı.
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

    <!-- Force Delete Confirmation Modal -->
    <div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-labelledby="forceDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forceDeleteModalLabel">Xəbəri Həqiqətən Sil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>"<span id="force-news-title"></span>" adlı xəbəri DATABASE-DƏN həqiqətən silmək istədiyinizə əminsiniz?</p>
                    <p class="text-danger"><strong>Bu əməliyyat geri alına bilməz və xəbər tamamilə silinəcək!</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ləğv et</button>
                    <form id="forceDeleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Həqiqətən Sil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Force Delete functionality
            const forceDeleteButtons = document.querySelectorAll('.force-delete-news');
            const forceDeleteModal = new bootstrap.Modal(document.getElementById('forceDeleteModal'));
            const forceDeleteForm = document.getElementById('forceDeleteForm');
            const forceNewsTitleSpan = document.getElementById('force-news-title');

            forceDeleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const newsId = this.getAttribute('data-id');
                    const newsTitle = this.getAttribute('data-title');

                    forceNewsTitleSpan.textContent = newsTitle;

                    // URL-i DÜZGÜN qururuq
                    const baseUrl = "{{ route('news.forceDelete', ['id' => 'PLACEHOLDER']) }}";
                    forceDeleteForm.action = baseUrl.replace('PLACEHOLDER', newsId);

                    console.log('Form action:', forceDeleteForm.action); // Debug üçün
                    forceDeleteModal.show();
                });
            });

            // AJAX force delete - YENİ VERSİYA
            forceDeleteForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const url = form.action;

                console.log('Sending request to:', url); // Debug üçün

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                })
                    .then(response => {
                        console.log('Response status:', response.status); // Debug üçün
                        if (response.ok) {
                            return response.json();
                        } else {
                            return response.json().then(errorData => {
                                throw new Error(errorData.message || 'Network response was not ok');
                            });
                        }
                    })
                    .then(data => {
                        console.log('Success data:', data); // Debug üçün
                        if (data.success) {
                            forceDeleteModal.hide();
                            alert('Xəbər uğurla silindi!');
                            location.reload();
                        } else {
                            throw new Error(data.message || 'Silinmə uğursuz oldu');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        forceDeleteModal.hide();
                        alert('Xəta baş verdi: ' + error.message);
                    });
            });
        });
    </script>

    <style>
        .btn-group .btn {
            margin: 0 2px;
        }
        .table img {
            border: 1px solid #dee2e6;
        }
    </style>
@endsection
