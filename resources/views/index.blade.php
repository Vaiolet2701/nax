@extends('layouts.app')
@section('content')
<div class="container">
<!-- Секция "Почему наши курсы + Горящие предложения" -->
<section class="features-promotions section">
    <div class="container">
        <div class="features-promotions__wrapper">
            <!-- Левая часть: Почему наши курсы -->
            <div class="features-left">
                <h2 class="section-title">ПОЧЕМУ НАШИ КУРСЫ</h2>
                <div class="features-cards">
                    <div class="feature-card">
                        <h3>ОПЫТНЫЕ ИНСТРУКТОРЫ</h3>
                        <p>Проверенные с многолетним опытом</p>
                    </div>
                    <div class="feature-card">
                        <h3>ПРАКТИЧЕСКИЕ НАВЫКИ</h3>
                        <p>Учим тому, что действительно важно</p>
                    </div>
                    <div class="feature-card">
                        <h3>БЕЗОПАСНОСТЬ</h3>
                        <p>Сопровождение на каждом этапе</p>
                    </div>
                </div>
            </div>

            <!-- Правая часть: Скидка -->
            <div class="features-right">
                @if($promotions->isNotEmpty())
                    @php $promo = $promotions->first(); @endphp
                    <div class="promo-box" onclick="loadEnrollForm('{{ $promo->id }}', 'promotion')">
                        <h3 class="discount-label">СКИДКА</h3>
                        <div class="discount-value">{{ $promo->discount }}%</div>
                        <p class="discount-note">Не пропусти до конца месяца</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Модальное окно -->
<div class="modal fade" id="promotionModal" tabindex="-1" aria-labelledby="promotionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="promotionModalLabel">Запись по акции</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
        </div>
        <div class="modal-body" id="promotionModalBody">
          <!-- Здесь будет форма подгружена через AJAX -->
          <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Загрузка...</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</section>

<section class="courses section">
    <h2 class="section-title">НАШИ КУРСЫ</h2>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            @foreach($courses as $course)
                <div class="swiper-slide">
                    <div class="card">
                        <h3>{{ $course->title }}</h3>
                        @if($course->image_path)
                            <img src="{{ asset($course->image_path) }}" alt="{{ $course->title }}">
                        @endif
                        <p>С {{ $course->start_date }} по {{ $course->end_date }}</p>
                        <p>Группа: {{ $course->min_people }}-{{ $course->max_people }}</p>
                        <p>Преподаватель: {{ $course->teacher->name ?? '—' }}</p>
                        <p>Животные: {{ $course->animals }}</p>
                        <p>Цена: {{ $course->price ? number_format($course->price, 2, '.', ' ') . ' руб.' : 'Бесплатно' }}</p>
                        @auth
                            <button onclick="openEnrollModal({{ $course->id }})">Записаться</button>
                        @else
                            <a href="{{ route('login') }}">Войти для записи</a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
</section>

    <!-- Модальное окно -->
    <div id="enrollModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <div id="modalContent"></div>
        </div>
    </div>

<!-- Отдельный блок карты -->
<section class="map-section my-5">
    <h2 class="section-title text-success">КАРТА КУРСОВ</h2>
    <div id="map" style="height: 80vh; border-radius: 12px;"></div>
</section>

<!-- Секция "Частые вопросы" -->
<section class="faq">
    <h2 class="section-title">ЧАСТЫЕ ВОПРОСЫ</h2>
    <div class="faq__accordion">
        <!-- Основные вопросы -->
        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Что взять с собой на курс?
                </button>
            </h3>
            <div class="faq__item-content">
                <p>Основное снаряжение мы предоставляем. олный список вы можете посмотреть на карточках курсов. Рекомендуем взять:</p>
                <ul>
                    <li>Удобную непромокаемую одежду и обувь</li>
                    <li>Личные лекарства (если нужны)</li>
                    <li>Гигиенические принадлежности</li>
                    <li>Блокнот и ручку</li>
                    <li>Фонарик</li>
                </ul>
            </div>
        </div>

        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Есть ли возрастные ограничения?
                </button>
            </h3>
            <div class="faq__item-content">
                <p>Минимальный возраст - 14 лет (в сопровождении взрослых). Для подростков 14-16 лет есть специальные семейные программы.</p>
                <p>Верхнего возрастного ограничения нет, но требуется хорошая физическая форма.</p>
            </div>
        </div>

        <!-- Безопасность -->
        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Насколько это безопасно?
                </button>
            </h3>
            <div class="faq__item-content">
                <p>Все занятия проводят опытные инструкторы с медицинской подготовкой. Мы:</p>
                <ul>
                    <li>Используем только проверенное снаряжение</li>
                    <li>Имеем аптечки и средства связи</li>
                    <li>Работаем на подготовленных площадках</li>
                    <li>Соблюдаем все меры предосторожности</li>
                </ul>
            </div>
        </div>

        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Что делать, если у меня аллергия/особые состояния?
                </button>
            </h3>
            <div class="faq__item-content">
                <p>Обязательно сообщите нам при бронировании о:</p>
                <ul>
                    <li>Аллергиях (особенно на укусы насекомых)</li>
                    <li>Хронических заболеваниях</li>
                    <li>Особых диетических требованиях</li>
                </ul>
                <p>Мы адаптируем программу под ваши потребности.</p>
            </div>
        </div>

        <!-- Подготовка -->
        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Нужна ли специальная подготовка?
                </button>
            </h3>
            <div class="faq__item-content">
                <p>Для курсов подготовка не требуется.</p>
            </div>
        </div>

        <!-- Питание -->
        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Как организовано питание?
                </button>
            </h3>
            <div class="faq__item-content">
                <p>На всех курсах:</p>
                <ul>
                    <li>3-разовое сбалансированное питание</li>
                    <li>Возможность вегетарианского/веганского меню</li>
                    <li>Обучение приготовлению пищи в полевых условиях</li>
                </ul>
            </div>
        </div>

        <!-- Животные -->
        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Как защищаемся от диких животных?
                </button>
            </h3>
            <div class="faq__item-content">
                <p>Наши меры:</p>
                <ul>
                    <li>Работаем в местах с минимальной опасностью</li>
                    <li>Используем отпугиватели (фальшфейеры, спреи)</li>
                    <li>Обучаем правильному поведению при встрече</li>
                    <li>Храним еду в специальных контейнерах</li>
                </ul>
                <p>За 10 лет - ни одной опасной встречи!</p>
            </div>
        </div>

        <!-- Медицина -->
        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Есть ли медицинская помощь?
                </button>
            </h3>
            <div class="faq__item-content">
                <p>Да, на всех курсах:</p>
                <ul>
                    <li>Инструкторы с необходимой подготовкой</li>
                    <li>Аптечки с необходимыми медикаментами</li>
                    <li>Спутниковая связь для экстренных случаев</li>
                    <li>Маршруты проложены недалеко от дорог</li>
                </ul>
                <p>Для хронических заболеваний - берите свои лекарства.</p>
            </div>
        </div>

        <!-- Сертификаты -->
        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Выдаете ли вы сертификаты?
                </button>
            </h3>
            <div class="faq__item-content">
                <p>Да, по окончании курса вы получите:</p>
                <ul>
                    <li>Именной сертификат о прохождении</li>
                    <li>Список освоенных навыков</li>
                    <li>Рекомендации по дальнейшему обучению</li>
                </ul>
                <p>Для профессиональных гидов - сертификат международного образца.</p>
            </div>
        </div>

        <!-- Трансфер -->
        <div class="faq__item">
            <h3 class="faq__item-title">
                <button class="faq__item-btn" type="button">
                    Как добраться до места?
                </button>
            </h3>
            <div class="faq__item-content">
                <p>Варианты:</p>
                <ul>
                    <li><strong>Самостоятельно:</strong> координаты и схема проезда после оплаты</li>
                    <li><strong>Групповой трансфер:</strong> от ближайшего города (уточняйте для конкретного курса)</li>
                    <li><strong>Индивидуальный трансфер:</strong> за дополнительную плату</li>
                </ul>
            </div>
        </div>
    </div>
</section>

  <!-- Секция "Отзывы" с формой и слайдером -->
<section class="reviews-section">
    <h2 class="section-title">ОТЗЫВЫ</h2>

    <!-- Слайдер отзывов -->
    <div class="reviews__slider">
        <div class="swiper-container swiper-container-reviews">
            <div class="swiper-wrapper">
                @foreach($reviews as $review)
                    <div class="swiper-slide">
                        <div class="reviews__card">
                            <div class="reviews__item">
                                <h3 class="reviews__item-author">{{ $review->author_name }}</h3>
                                <p class="reviews__item-content">{{ $review->content }}</p>
                                <p class="reviews__item-rating">Рейтинг: {{ $review->rating }}/5</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Навигация -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>

        <form action="{{ route('reviews.store') }}" method="POST" class="reviews-form__form">
            @csrf
            <h3 class="reviews-form__title">ОСТАВИТЬ ОТЗЫВ</h3>
            <div class="form-group">
                <label for="content">Текст отзыва:</label>
                <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="rating">Рейтинг (от 1 до 5):</label>
                <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" required>
            </div>
            <button type="submit" class="btn btn-primary">Отправить отзыв</button>
        </form>
</section>

    
</div>

@push('scripts')
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
                    const greenIcon = L.divIcon({
                        className: '',
                        html: '<div class="custom-marker"></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });

                    const marker = L.marker([course.latitude, course.longitude], { icon: greenIcon }).addTo(map);

                    const popupContent = `
                        <h5>${course.title}</h5>
                        <p>${course.description.substring(0, 100)}...</p>
                        <p><strong>Цена:</strong> ${course.price ?? 'Бесплатно'}₽</p>
                        <a href="/courses/${course.id}">Подробнее</a>
                    `;

                    marker.bindPopup(popupContent);
                }
            });
        });
    </script>
@endpush




@endsection
    <script>
function loadEnrollForm(promotionId, type) {
    const modalBody = document.getElementById('promotionModalBody');
    modalBody.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>
        </div>
    `;

    // Показать модальное окно
    const modal = new bootstrap.Modal(document.getElementById('promotionModal'));
    modal.show();

    // Загружаем форму через AJAX
    fetch(`/promotions/enroll-form/${promotionId}`)
        .then(response => response.text())
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            modalBody.innerHTML = `<div class="alert alert-danger">Ошибка загрузки формы</div>`;
            console.error('Ошибка:', error);
        });
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiper = new Swiper('.swiper-container', {
            loop: true,
            slidesPerView: 3,
            centeredSlides: true,
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                }
            }
        });
    });
    </script>
   <script>
function openEnrollModal(courseId) {
    fetch(`/courses/${courseId}/enroll`)
        .then(response => {
            if (!response.ok) throw new Error('Не удалось загрузить форму');
            return response.text();
        })
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('enrollModal').style.display = 'block';
        })
        .catch(error => {
            alert('Ошибка при загрузке формы.');
            console.error(error);
        });
}

// ВНЕ функции openEnrollModal
function closeModal() {
    document.getElementById('enrollModal').style.display = 'none';
}

// Закрытие по клику вне окна
window.onclick = function(event) {
    const modal = document.getElementById('enrollModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};


</script>
 
<script>

const swiper2 = new Swiper('.swiper-container-2', {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 20,
    navigation: false,
    allowTouchMove: false, // ⛔ отключает свайп
    pagination: {
        el: '.swiper-pagination-2',
        clickable: true, // ✅ клики по кружкам
    },
});
</script>
<script>
const reviewSwiper = new Swiper('.swiper-container-reviews', {
  slidesPerView: 1,
  loop: true,
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  spaceBetween: 20, // ← расстояние между карточками (в пикселях)
  centeredSlides: true,
});



</script>
<script>
    
    // Создаем функцию-обёртку
    function addEventListener2(element, event, handler) {
        element.addEventListener(event, handler);
    }

    // Используем нашу функцию для аккордеона
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Скрипт аккордеона загружен'); // Проверка загрузки скрипта
        const faqButtons = document.querySelectorAll('.faq__item-btn');
        console.log(faqButtons); // Проверка, что кнопки найдены

        if (faqButtons.length === 0) {
            console.error('Элементы .faq__item-btn не найдены');
            return;
        }

        faqButtons.forEach(button => {
            addEventListener2(button, 'click', function () {
                console.log('Кнопка нажата'); // Проверка, что обработчик срабатывает
                const item = this.closest('.faq__item'); // Находим родительский элемент .faq__item
                const content = item.querySelector('.faq__item-content'); // Находим контент внутри .faq__item

                // Закрываем все открытые элементы
                faqButtons.forEach(btn => {
                    const otherItem = btn.closest('.faq__item');
                    const otherContent = otherItem.querySelector('.faq__item-content'); // Исправлено: .faq__item-content
                    if (otherItem !== item) {
                        btn.classList.remove('active');
                        otherContent.classList.remove('open');
                    }
                });

                // Открываем/закрываем текущий элемент
                this.classList.toggle('active');
                content.classList.toggle('open');
            });
        });
    });
</script>
