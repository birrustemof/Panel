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
                    <form method="POST" action="{{ route('forms.general.update', $newsItem->id) }}" id="updateForm" enctype="multipart/form-data">
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
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                           value="{{ old('title', $newsItem->title) }}"
                                           placeholder="Xəbərin başlığını daxil edin">
                                    <div class="form-text">
                                        <span id="titleCount">{{ strlen($newsItem->title) }}</span>/15 xarakter
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="text" class="form-label">Text</label>
                                    <textarea class="form-control" id="text" name="text" rows="5"
                                              placeholder="Xəbərin mətnini daxil edin">{{ old('text', $newsItem->text) }}</textarea>
                                    <div class="form-text">
                                        <span id="textCount">{{ strlen($newsItem->text) }}</span>/50 xarakter
                                    </div>
                                </div>

                            <div class="mb-3">
                                <label for="author" class="form-label">Müəllif <small class="text-muted">(məcburi)</small></label>
                                <input type="text" class="form-control" id="author" name="author"
                                       value="{{ old('author', $newsItem->author ?? '') }}"
                                       placeholder="Müəllifin adını daxil edin" required>
                                <div class="invalid-feedback">
                                    Müəllif adı daxil edilməlidir
                                </div>
                            </div>

                            <!-- ŞƏKİL REDAKTƏ HİSSƏSİ ƏLAVƏ EDİN -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Şəkil</label>

                                <!-- Cari şəkil -->
                                @if($newsItem->image)
                                    <div class="mb-2">
                                        <p class="mb-1">Cari şəkil:</p>
                                        <img src="{{ asset('storage/' . $newsItem->image) }}"
                                             alt="{{ $newsItem->title }}"
                                             style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 4px;">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                                            <label class="form-check-label text-danger" for="remove_image">
                                                Şəkili sil
                                            </label>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted">Şəkil yoxdur</p>
                                @endif

                                <!-- Yeni şəkil yükləmə -->
                                <input type="file" class="form-control mt-2" id="image" name="image"
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                <div class="form-text">
                                    JPG, PNG, GIF formatları. Maksimum ölçü: 2MB. Boş buraxılsa, cari şəkil qalacaq.
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

    <script>

            document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const textInput = document.getElementById('text');
            const titleCount = document.getElementById('titleCount');
            const textCount = document.getElementById('textCount');
            const removeImageCheckbox = document.getElementById('remove_image');
            const imageInput = document.getElementById('image');
            const updateForm = document.getElementById('updateForm');
            const updateBtn = document.getElementById('updateBtn');

            // Title xarakter sayını göstər
            titleInput.addEventListener('input', function() {
            titleCount.textContent = this.value.length;
        });

            // Text xarakter sayını göstər
            textInput.addEventListener('input', function() {
            textCount.textContent = this.value.length;
        });

            // Şəkil silmə checkbox'ı aktiv olduqda file inputunu disable et
            if (removeImageCheckbox && imageInput) {
            removeImageCheckbox.addEventListener('change', function() {
            if (this.checked) {
            imageInput.disabled = true;
            imageInput.value = '';
        } else {
            imageInput.disabled = false;
        }
        });
        }

            // File input dəyişdikdə remove checkbox'ını sıfırla
            if (imageInput) {
            imageInput.addEventListener('change', function() {
            if (removeImageCheckbox) {
            removeImageCheckbox.checked = false;
        }
        });
        }

            // YENİ: "Edumediya/Edumedia" validasiyası əlavə edin
            updateForm.addEventListener('submit', function(e) {
            const titleValue = titleInput.value;

            // BÜTÜN VERSİYALARI YOXLA
            if (titleValue.includes('edumedia') ||
            titleValue.includes('edumediya') ||
            titleValue.includes('Edumedia') ||
            titleValue.includes('Edumediya') ||
            titleValue.includes('EDUMEDIA') ||
            titleValue.includes('EDUMEDIYA')) {
            e.preventDefault();
            titleInput.classList.add('is-invalid');

            // Xüsusi xəta mesajı yarat
            let specialError = document.getElementById('specialError');
            if (!specialError) {
            specialError = document.createElement('div');
            specialError.className = 'invalid-feedback';
            specialError.id = 'specialError';
            specialError.style.display = 'block';
            specialError.textContent = 'Xəbər adında "Edumedia" və ya "Edumediya" sözləri ola bilməz!';
            titleInput.parentNode.appendChild(specialError);
        } else {
            specialError.style.display = 'block';
        }

            titleInput.focus();
            return false;
        }

            // Əgər hər şey yaxşıdırsa, düyməni disable et
            updateBtn.disabled = true;
            updateBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Yenilənir...';
        });

            // Input dəyişdikdə xəta mesajını gizlət
            titleInput.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
            this.classList.remove('is-invalid');
            const specialError = document.getElementById('specialError');
            if (specialError) {
            specialError.style.display = 'none';
        }
        }
        });
        });

    </script>
@endsection
