# Инструкция по установке приложения

* Создайте свой .env файл на основе .env.example

* Выполните команды:

- `composer install` - установка необходимых зависимостей;
- `php artisan key:generate` - генерация ключа приложения;
- `./vendor/bin/sail up -d` - запуск докер контейнеров;
- `./vendor/bin/sail artisan migrate` - выполнение миграций;
- `./vendor/bin/sail artisan db:seed` - заполнение бд случайными данными;
- `./vendor/bin/sail artisan test` - запуск автотестов;
- `./vendor/bin/sail artisan queue:work` - запуск обработки фоновых задач (в .env файле прописан OMDB_API_KEY).
