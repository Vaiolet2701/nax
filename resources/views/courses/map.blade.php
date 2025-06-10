@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-success mb-4">Карта курсов</h1>
    <div id="map" style="height: 80vh; border-radius: 12px;"></div>
</div>
@endsection

@push('scripts')
    {{-- Подключаем Leaflet перед нашим скриптом --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-o9N1jG8/2iHTR7rQqR+AaU0iNkA+NK+GZs8jF4WpqUI="
            crossorigin=""></script>

    <script>
        const courses = @json($courses);

        document.addEventListener('DOMContentLoaded', () => {
            const map = L.map('map').setView([55.751244, 37.618423], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            courses.forEach(course => {
                if (course.latitude && course.longitude) {
                    const marker = L.marker([course.latitude, course.longitude]).addTo(map);

                    const popupContent = `
                        <div style="max-width: 250px;">
                            <h5 style="color:#43d177;">${course.title}</h5>
                            <p style="font-size: 0.9rem; color: #bbb;">${course.description.substring(0, 100)}...</p>
                            <p><strong>Цена:</strong> ${course.price ?? 'Бесплатно'}₽</p>
                            <a href="/courses/${course.id}" class="btn btn-sm btn-outline-success">Подробнее</a>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                }
            });
        });
    </script>
@endpush
