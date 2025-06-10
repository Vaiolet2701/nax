<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аккордеон</title>
    <style>
        /* Стили для аккордеона */
        .faq__item-content {
            display: none; /* Скрываем контент по умолчанию */
            padding: 10px;
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
        }

        .faq__item-content.open {
            display: block; /* Показываем контент, когда добавлен класс "open" */
        }

        .faq__item-btn.active {
            font-weight: bold; /* Пример стиля для активной кнопки */
        }
    </style>
</head>
<body>
    <!-- Секция "Частые вопросы" -->
    <section class="faq">
        <h2 class="faq__title">Частые вопросы</h2>
        <div class="faq__accordion">
            <div class="faq__item">
                <h3 class="faq__item-title">
                    <button class="faq__item-btn" type="button">
                        Что взять с собой?
                    </button>
                </h3>
                <div class="faq__item-content">
                    Только личные вещи. Все снаряжение предоставляем!
                </div>
            </div>
            <div class="faq__item">
                <h3 class="faq__item-title">
                    <button class="faq__item-btn" type="button">
                        Есть возрастные ограничения?
                    </button>
                </h3>
                <div class="faq__item-content">
                    Участие с 14 лет в сопровождении взрослых
                </div>
            </div>
        </div>
    </section>

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
</body>
</html>