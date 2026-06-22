# Lula Flowers

Lula Flowers — информационный сайт на Laravel со статьями о цветах, комнатных растениях, уходе, букетах и флористике. Проект выполнен как курсовая работа на тему «Разработка сайта для цветов».

## Технологии

- PHP 8.2+
- Laravel 12
- Laravel Breeze
- MySQL
- Blade
- Tailwind CSS и Alpine.js
- Vite

## Возможности

- регистрация, вход и управление профилем;
- публичная главная страница;
- список статей с поиском, фильтрацией по категории, сортировкой и пагинацией;
- детальная страница статьи;
- создание, редактирование и удаление статей авторизованными пользователями;
- загрузка изображений размером до 2 МБ;
- категории статей и Eloquent-связь `Category hasMany Post` / `Post belongsTo Category`;
- eager loading связанных данных;
- серверная валидация и flash-сообщения;
- 5 категорий и 15 демонстрационных статей через сидер.

## Локальный запуск

### 1. Установка зависимостей

```bash
composer install
npm install
```

### 2. Настройка окружения

Скопируйте файл окружения:

```bash
copy .env.example .env
```

Создайте базу данных MySQL `lula_flowers`, затем укажите подключение в `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lula_flowers
DB_USERNAME=root
DB_PASSWORD=
```

Сгенерируйте ключ приложения:

```bash
php artisan key:generate
```

### 3. База данных и изображения

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

Тестовая учётная запись:

```text
E-mail: author@lulaflowers.test
Пароль: password
```

### 4. Запуск

В двух терминалах выполните:

```bash
php artisan serve
```

```bash
npm run dev
```

При запуске через Laragon сайт будет доступен по адресу `http://lula-flowers.test`.

При запуске через `php artisan serve` используется адрес `http://127.0.0.1:8000`.

## Проверка

```bash
php artisan test
npm run build
```

## Оптимизация перед размещением

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
