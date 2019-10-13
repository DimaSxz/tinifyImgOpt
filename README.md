Инструкция по установке
---------------------------------

- Клонируем репозиторий
```
git clone https://github.com/DimaSxz/tinifyImgOpt.git
```

- Переходим в директорию проекта и устанавливаем зависимости
```
cd tinifyImgOpt
composer install
npm install
```

- Создаём БД
- Задаём переменные окружения в .env файле (все необходимые параметры указаны в файле .env.example)
```
cp .env.example .env
```
- Подробнее о ключевых параметрах:
```
# стандартные настройки подключения к базе данных
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=redis # в проекте используется очередь, основанная на redis

TINIFY_API_KEY= # ключ API, который необходимо получить на сайте https://tinypng.com/
TINIFY_IMG_LIMIT=499 # оставляем как есть

IMG_THUMB_WIDTH=300 # ширина желаемых превью-изображений
IMG_THUMB_HEIGHT=200 # высота желаемых превью-изображений
IMG_THUMB_METHOD=thumb # метод обрезки изображений (см. https://tinypng.com/developers/reference/php#resizing-images)
IMG_THUMB_PATH=/photos/optimised/thumbs/ # желаемая директория, где будут храниться оптимизированные и обрезанные изображения
IMG_OPTIMISED_PATH=/photos/optimised/ # желаемая директория, где будут храниться оптимизированные изображения в исходных размерах
```
Внимание! Перед запуском проекта, необходимо создать указанные директории примерно так:
```
mkdir public/${IMG_THUMB_PATH} public/${IMG_OPTIMISED_PATH}
``` 
- Создаём таблицы БД и их связи с помощью миграций 
```
php artisan migrate
```
- Собираем клиентскую часть приложения 
```
npm run prod
```
- Запуск приложения потребует открытия двух консолей (или же использования супервизоров)
```
php artisan serv // в первой консоли
php artisan queue:work // соответственно, во второй
```
