# Music API на Yii2

## Описание
Этот проект представляет собой RESTful API для музыкального сервиса, разработанное на Yii2. API предоставляет следующие возможности:

- Аутентификация пользователей.
- Создание, редактирование и удаление музыкальных постов (с аудиофайлами).
- Прикрепление и фильтрация по тегам.
- Авторизация через токен.

---

## Требования

- PHP 8.1 или выше
- Composer
- MySQL 5.7 или выше
- Установленный Yii2 Framework
- Настроенный веб-сервер

---

## Установка и развертывание

### Шаг 1: Клонирование репозитория

```bash
git clone <URL_репозитория>
cd <директория_проекта>
```

### Шаг 2: Установка зависимостей

```bash
composer install
```

### Шаг 3: Настройка конфигурации

1. Скопируйте файл конфигурации:

   ```bash
   cp config/db.php.example config/db.php
   ```

2. Откройте файл `config/db.php` и укажите параметры подключения к базе данных:

   ```php
   return [
       'class' => 'yii\db\Connection',
       'dsn' => 'mysql:host=localhost;dbname=music_api',
       'username' => 'your_username',
       'password' => 'your_password',
       'charset' => 'utf8mb4',
   ];
   ```

### Шаг 4: Применение миграций

Создайте базу данных и выполните миграции:

```bash
php yii migrate
```

### Шаг 5: Настройка прав для загрузки файлов

Создайте папку для загрузки аудиофайлов и установите права доступа:

```bash
mkdir uploads
chmod 775 uploads
```

### Шаг 6: Настройка веб-сервера

Настройка веб-сервера

## Использование

### Авторизация пользователя

**POST** `/users/login`

Параметры:

- `email` (string)
- `password` (string)

Пример ответа:

```json
{
    "auth_key": "your-auth-token"
}
```

### Создание поста

**POST** `/posts/create`

Заголовок:

- `Authorization: Bearer <auth_key>`

Параметры:

- `title` (string)
- `description` (string)
- `audio_file` (file, mp3)
- `tags` (array of strings)

Пример:

```json
{
    "title": "My Song",
    "description": "This is my new song",
    "tags": ["pop", "rock"]
}
```

### Получение списка постов

**GET** `/posts`

Параметры:

- `tags` (string, optional, через запятую)

Пример:

`/posts?tags=pop,rock`