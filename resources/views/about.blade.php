@extends('layouts.app')

<link href="{{ asset('css/pages.css') }}" rel="stylesheet">

@section('content')
<div class="about-page">

    <div class="bg-light py-5 border-bottom" style="background: linear-gradient(to right, #e1ffe1, #f0fff5);">
        <div class="container text-center">
            <h1 class="display-5 fw-bold text-success">О нашем проекте</h1>
            <p class="lead text-muted">Узнайте больше о наших курсах и правилах сообщества</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                <!-- Курсы -->
                <section class="about-section mb-5">
                    <h2>Курсы выживания</h2>
                    <p>
                        Наш сайт предлагает уникальные курсы по выживанию в различных условиях:
                    </p>
                    <ul>
                        <li>Выживание в дикой природе</li>
                        <li>Подготовка к природным катаклизмам</li>
                        <li>Основы первой медицинской помощи</li>
                        <li>Психологическая подготовка</li>
                    </ul>
                    <p>
                        Все курсы разработаны профессиональными инструкторами с реальным опытом выживания в экстремальных условиях.
                    </p>
                </section>

                <!-- Правила сообщества -->
                <section class="about-section mb-5">
                    <h2>Правила сообщества</h2>
                    <ul class="list-group list-group-numbered">
                        <li>Уважайте других участников сообщества</li>
                        <li>Посещение куксов и походов строго от 16 лет</li>
                        <li>Запрещено распространение опасной или ложной информации</li>
                        <li>Контент должен соответствовать тематике выживания</li>
                        <li>Запрещена коммерческая деятельность без согласования</li>
                        <li>Администрация оставляет за собой право удалять любой контент</li>
                    </ul>
                </section>

                <!-- Правила аренды -->
                <section class="about-section mb-5">
                    <h2>Правила аренды снаряжения</h2>
                    <ul>
                        <li>Арендовать снаряжение могут только зарегистрированные пользователи</li>
                        <li>Снаряжение необходимо вернуть в срок и в надлежащем состоянии</li>
                        <li>Повреждённое или утерянное снаряжение оплачивается арендатором</li>
                        <li>Перед арендой администрация может запросить документ, удостоверяющий личность</li>
                    </ul>
                </section>

                <!-- Правила пользовательских походов -->
                <section class="about-section mb-5">
                    <h2>Правила проведения походов пользователями</h2>
                    <ul>
                        <li>Создавать походы могут пользователи с уровнем "Продвинутый"</li>
                        <li>Каждый поход должен быть подтверждён модератором</li>
                        <li>Организатор несёт ответственность за участников и маршрут</li>
                        <li>Все участники обязаны соблюдать технику безопасности</li>
                    </ul>
                </section>

                <!-- Уровни пользователей -->
                <section class="about-section mb-5">
                    <h2>Уровни пользователей</h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100 border-success">
                                <div class="card-body">
                                    <h5 class="card-title text-success">Новичок</h5>
                                    <p class="card-text">Может просматривать материалы и комментировать.</p>
                                    <p><strong>Получение:</strong> сразу после регистрации.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-success">
                                <div class="card-body">
                                    <h5 class="card-title text-success">Средний</h5>
                                    <p class="card-text">Доступна модерация контента и участие в обсуждениях.</p>
                                    <p><strong>Получение:</strong> после 5 успешных мероприятий.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-success">
                                <div class="card-body">
                                    <h5 class="card-title text-success">Продвинутый</h5>
                                    <p class="card-text">Может создавать походы, курсы и участвовать в закрытых мероприятиях.</p>
                                    <p><strong>Получение:</strong> после 8 успешных мероприятий и проверки администрацией.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mt-4">
                        <strong>Ваш текущий уровень:</strong>
                        {{ auth()->user()->laravel_level ?? 'Гость' }}
                    </div>
                </section>

                <!-- Тестирование -->
                <section class="about-section text-center mt-5">
                    <h2 class="text-success mb-4">Пройди тестирование и узнай, насколько ты готов к выживанию!</h2>
                    <a href="{{ route('survival.test') }}" class="btn btn-success btn-lg px-5">Пройти тест</a>
                </section>

            </div>
        </div>
    </div>
</div>
@endsection
