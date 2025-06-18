@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Управление записями на курсы</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2 class="mt-4">Новые заявки (ожидают рассмотрения)</h2>

    <div class="admin-table-responsive">
        @if($pendingEnrollments->isEmpty())
            <div class="alert alert-info">Нет новых заявок</div>
        @else
            <table class="table admin-table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Курс</th>
                        <th>Цена</th>
                        <th>Телефон</th>
                        <th>Возраст</th>
                        <th>Сообщение</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingEnrollments as $enrollment)
                    <tr>
                        <td data-label="ID">{{ $enrollment->pivot_id }}</td>
                        <td data-label="Пользователь">{{ $enrollment->name }} ({{ $enrollment->email }})</td>
                        <td data-label="Курс">{{ $enrollment->title }}</td>
                        <td data-label="Цена">
                            @php
                                $original = $enrollment->original_price ?? $enrollment->price ?? 0;
                                $discounted = $enrollment->discounted_price ?? $original;
                            @endphp
                            @if($discounted < $original)
                                <span class="text-muted text-decoration-line-through">
                                    {{ number_format($original, 2) }} руб.
                                </span>
                                &nbsp;
                                <strong>{{ number_format($discounted, 2) }} руб.</strong>
                            @else
                                {{ number_format($original, 2) }} руб.
                            @endif
                        </td>
                        <td data-label="Телефон">{{ $enrollment->phone }}</td>
                        <td data-label="Возраст">{{ $enrollment->age }}</td>
                        <td data-label="Сообщение">{{ $enrollment->message ?? '-' }}</td>
                        <td data-label="Дата">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('d.m.Y H:i') }}</td>
                        <td data-label="Действия">
                            <div class="btn-group">
                                <button class="btn btn-success btn-sm" onclick="approveEnrollment({{ $enrollment->pivot_id }})">Принять</button>
                                <button class="btn btn-danger btn-sm" onclick="event.preventDefault(); if(confirm('Отклонить заявку?')) loadRejectForm('{{ $enrollment->pivot_id }}');">Отклонить</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

<!-- Кнопка и блок с остальными заявками -->
<button class="btn btn-secondary mb-3" id="toggleEnrollmentsBtn" onclick="toggleAllEnrollments()">Показать все заявки</button>

<div id="allEnrollmentsBlock" style="display: none;">
    <table class="table admin-table table-striped table-hover align-middle">
        <thead>
        <tr>
            <th>ID</th>
            <th>Пользователь</th>
            <th>Курс</th>
            <th>Цена</th>
            <th>Статус</th>
            <th>Телефон</th>
            <th>Возраст</th>
            <th>Дата</th>
            <th>Причина отказа</th>
        </tr>
    </thead>
    <tbody>
        @foreach($allEnrollments as $enrollment)
        <tr class="enrollment-row">
            <td data-label="ID">{{ $enrollment->pivot_id }}</td>
            <td data-label="Пользователь">{{ $enrollment->name }} ({{ $enrollment->email }})</td>
            <td data-label="Курс">{{ $enrollment->title }}</td>
            <td data-label="Цена">
                @php
                    $original = $enrollment->original_price ?? $enrollment->price ?? 0;
                    $discounted = $enrollment->discounted_price ?? $original;
                @endphp
                @if($discounted < $original)
                    <span class="text-muted text-decoration-line-through">
                        {{ number_format($original, 2) }} руб.
                    </span>
                    &nbsp;
                    <strong>{{ number_format($discounted, 2) }} руб.</strong>
                @else
                    {{ number_format($original, 2) }} руб.
                @endif
            </td>
            <td data-label="Статус">
                @if($enrollment->status == 'pending')
                    <span class="badge bg-warning text-dark">Ожидает</span>
                @elseif($enrollment->status == 'in_progress')
                    <span class="badge bg-primary">Принята</span>
                @elseif($enrollment->status == 'rejected')
                    <span class="badge bg-secondary">Отказано</span>
                @else
                    <span class="badge bg-light text-dark">Завершена</span>
                @endif
            </td>
            <td data-label="Телефон">{{ $enrollment->phone }}</td>
            <td data-label="Возраст">{{ $enrollment->age }}</td>
            <td data-label="Дата">{{ \Carbon\Carbon::parse($enrollment->created_at)->format('d.m.Y H:i') }}</td>
            <td data-label="Причина отказа">{{ $enrollment->rejection_reason ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
        </div>
    </div>
</div>

<!-- Единое модальное окно для отклонения -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="rejectModalContent"></div> <!-- Сюда будет загружена форма -->
    </div>
</div>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
        background-color: #1e1e1e;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #444;
        width: 80%;
        max-width: 600px;
        border-radius: 5px;
        color: #fff;
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover {
        color: #fff;
    }
    

</style>
@endsection

@push('scripts')
<script>
// Переключение отображения всех заявок и изменение текста кнопки
function toggleAllEnrollments() {
    const block = document.getElementById('allEnrollmentsBlock');
    const button = document.getElementById('toggleEnrollmentsBtn');
    
    if (!block || !button) {
        console.error('Элементы не найдены!');
        return;
    }

    if (block.style.display === 'none' || block.style.display === '') {
        block.style.display = 'block';
        button.textContent = 'Скрыть все заявки';
    } else {
        block.style.display = 'none';
        button.textContent = 'Показать все заявки';
    }
}

// Отправка запроса на одобрение заявки по AJAX
function approveEnrollment(enrollmentId) {
    if (!confirm('Вы уверены, что хотите одобрить заявку?')) return;

    const url = @json(route('admin.enrollments.approve', ['enrollmentId' => '__ID__'])).replace('__ID__', enrollmentId);

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Ошибка при одобрении заявки');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при отправке запроса');
    });
}

// Функция для загрузки формы отклонения через AJAX
function loadRejectForm(enrollmentId) {
    const url = `/admin/enrollments/${enrollmentId}/reject-form`;
    const modalContent = document.getElementById('rejectModalContent');
    modalContent.innerHTML = '<div class="text-center p-4">Загрузка формы...</div>';
    document.getElementById('rejectModal').style.display = 'block';

    fetch(url, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Ошибка сервера: ${response.status}`);
        }
        return response.text();
    })
    .then(data => {
        modalContent.innerHTML = data;
        initRejectForm();
    })
    .catch(error => {
        console.error('Ошибка:', error);
        modalContent.innerHTML = `
            <div class="alert alert-danger">
                <h4>Ошибка!</h4>
                <p>${error.message}</p>
                <button onclick="closeModal()" class="btn btn-secondary">Закрыть</button>
            </div>
        `;
    });
}

function initRejectForm() {
    const form = document.getElementById('rejectForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        submitButton.disabled = true;
        submitButton.textContent = 'Отправка...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModal();
                window.location.reload();
            } else {
                alert(data.message || 'Произошла ошибка');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при отправке формы');
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        });
    });
}

// Закрытие модального окна
function closeModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

// Закрытие при клике вне модального окна
window.onclick = function(event) {
    const modal = document.getElementById('rejectModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>
@endpush