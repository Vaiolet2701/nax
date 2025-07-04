<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Школа выживания</title>
    
    <!-- Подключение CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto+Condensed:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/cources.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/form-styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" /> 
    @if (Str::startsWith(Request::path(), 'admin'))
        <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @endif


</head>
<body>
    <!-- Главная навигация -->
    <nav class="nature__nav">
    <div class="nature__container">
        <button class="burger" id="burgerToggle" aria-label="Меню">
            <i class="fas fa-bars"></i>
        </button>
            <div class="nav-links">
                <a href="{{ url('/') }}" class="nav-link">
                    <i class="fas fa-home"></i>
                    Главная
                </a>
                <a href="{{ route('courses.index') }}" class="nav-link">
                    <i class="fas fa-book-open"></i>
                    Курсы
                </a>
                    <a  href="{{ route('about') }}" class="nav-link">
                    <i class="fas fa-about"></i>
                    О нас
                </a>
                <a  href="{{ route('content.index') }}" class="nav-link">
                    <i class="fas fa-content"></i>
                    Полезное
                </a>
                <a  href="{{ route('trips.index') }}" class="nav-link">
                    <i class="fas fa-trips"></i>
                    Походы
                </a>
                <a href="{{ route('equipments.index') }}"class="nav-link">
                    <i class="fas fa-equ"></i>
                    Список снаряжения</a>

                @auth
                    @if(auth()->user()->laravel_level === 'Продвинутый')
                        <a class="nav-link" href="{{ route('trips.create') }}">Создать поход</a>
                    @endif
                @endauth

         <!-- Правая часть навигации (авторизация) -->
            <div class="auth-nav">
                @guest
                    <a href="{{ route('login') }}" class="auth-link">
                        <i class="fas fa-sign-in-alt"></i>
                        Вход
                    </a>
                    <a href="{{ route('register') }}" class="auth-link auth-link--primary">
                        <i class="fas fa-user-plus"></i>
                        Регистрация
                    </a>
                @else
                    <div class="dropdown">
                        <button class="dropdown-toggle auth-link" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i>
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user"></i> Профиль</a></li>

                            @if(Auth::user()->role === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Пользователи</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.enrollments.index') }}">Заявки</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.courses.index') }}">Курсы</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.articles.index') }}">Статьи</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.videos.index') }}">Видео</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.user-articles.index') }}">Статьи пользователей</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.reviews.index') }}">Отзывы</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.teachers.index') }}">Преподаватели</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Пользователи</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.equipment.index') }}">Снаряжение</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.rentals.index') }}">Аренды</a></li>
                            @endif

                            @if(Auth::user()->role === 'teacher')
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Преподаватель</h6></li>
                                <li><a class="dropdown-item" href="{{ route('teachers.my-courses') }}">Мои курсы</a></li>
                                @endif

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Выйти
                                </a>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
         </div>
    </nav>

    <!-- Контент для главной страницы -->
    @if (request()->is('/'))
        <div class="nature">
            <div class="nature__image">
                <img src="{{ asset('img/image 39.png') }}" alt="Курсы выживания" class="nature__img">
            </div>
            
            <div class="nature__container">
                <div class="nature__text">
                    <h1 class="nature__title">Научись выживать с нами!</h1>
                    
<div class="features-grid">
    <div class="feature-item">
        <div class="feature-icon">
            <img src="{{ asset('img/вода.svg') }}" alt="Добыча воды">
        </div>
        <p class="feature-text">Добыча воды и пищи</p>
    </div>
    
    <div class="feature-item">
        <div class="feature-icon">
            <img src="{{ asset('img/укрытия.svg') }}" alt="Постройка укрытий">
        </div>
        <p class="feature-text">Постройка укрытий</p>
    </div>
    
    <div class="feature-item">
        <div class="feature-icon">
            <img src="{{ asset('img/помощь.svg') }}" alt="Первая помощь">
        </div>
        <p class="feature-text">Оказание первой помощи</p>
    </div>
    
    <div class="feature-item">
        <div class="feature-icon">
            <img src="{{ asset('img/ориентировка.svg') }}" alt="Ориентирование">
        </div>
        <p class="feature-text">Ориентирование без гаджетов</p>
    </div>
</div>
                    
                    <div class="nature__notice">
                        <h3>Снаряжение включено!</h3>
                        <p>Аварийные наборы, GPS-трекеры, спецодежда</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Основное содержимое страницы -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Форма выхода -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
<script>
  document.getElementById('burgerToggle').addEventListener('click', function () {
    document.querySelector('.nav-links').classList.toggle('active');
    document.querySelector('.auth-nav').classList.toggle('active');
  });
</script>

    <!-- Подключение JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
<footer class="survival-footer">
  <div class="footer-container">
    <div class="footer-section">
      <h3>Школа выживания</h3>
      <p>Обучение навыкам выживания в дикой природе с 2023 года</p>
    </div>

    <div class="footer-section">
      <h3>Разделы</h3>
      <ul>
        <li><a href="/content">Полезное</a></li>
        <li><a href="/courses">Курсы</a></li>
        <li><a href="/about">О нас</a></li>
        <li><a href="/trips">Походы</a></li>
      </ul>
    </div>

    <div class="footer-section">
      <h3>Контакты</h3>
      <ul class="contact-info">
        <li><i class="fas fa-phone"></i> +7 (986) 720-65-99</li>
        <li><i class="fas fa-envelope"></i> info@survival-school.ru</li>
        <li><i class="fas fa-map-marker-alt"></i> г. Казань</li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; 2023 Школа выживания в дикой природе. Все права защищены.</p>
    <div class="legal-links">
      <a href="/privacy">Политика конфиденциальности</a>
      <a href="/terms">Условия использования</a>
    </div>
  </div>
</footer>

<style>
  .survival-footer {
    background-color: #1c2a23;
    color: #f5f5e9;
    padding: 40px 0 20px;
    font-family: 'Arial', sans-serif;
  }

  .footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
  }

  .footer-section {
    flex: 1;
    min-width: 250px;
    margin-bottom: 30px;
    padding: 0 15px;
  }

  .footer-section h3 {
    color: #4fd1c5;
    margin-bottom: 20px;
    font-size: 18px;
  }

  .footer-section ul {
    list-style: none;
    padding: 0;
  }

  .footer-section ul li {
    margin-bottom: 10px;
  }

  .footer-section a {
    color: #c8d5d1;
    text-decoration: none;
    transition: color 0.3s;
  }

  .footer-section a:hover {
    color: #4fd1c5;
  }

  .social-links {
    margin-top: 20px;
  }

  .social-links a {
    display: inline-block;
    margin-right: 15px;
    font-size: 20px;
    color: #8b9e8b;
  }

  .contact-info i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
    color: #4fd1c5;
  }

  .footer-bottom {
    border-top: 1px solid #2d3e36;
    padding-top: 20px;
    text-align: center;
    margin-top: 30px;
    font-size: 14px;
  }

  .legal-links {
    margin-top: 15px;
  }

  .legal-links a {
    margin: 0 10px;
    color: #8b9e8b;
    font-size: 13px;
  }

  @media (max-width: 768px) {
    .footer-section {
      flex: 100%;
      text-align: center;
    }
    
    .social-links {
      justify-content: center;
    }
  }
</style>

<!-- Подключение иконок Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</html>