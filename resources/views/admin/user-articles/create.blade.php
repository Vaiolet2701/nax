<form action="{{ route('user-articles.store') }}" method="POST">
    @csrf
    <div>
        <label for="title">Заголовок:</label>
        <input type="text" name="title" id="title" required>
    </div>
    <div>
        <label for="content">Содержание:</label>
        <textarea name="content" id="content" required></textarea>
    </div>
    <div>
        <label for="image_path">Изображение (опционально):</label>
        <input type="text" name="image_path" id="image_path">
    </div>
    <button type="submit">Отправить статью</button>
</form>