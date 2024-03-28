# marketplace-api

### Настройка среды

#### Требованием для запуска приложения является наличие Docker

#### Запуск приложения:

```shell
git clone https://github.com/VladislavKryzhanovskii/marketplace-api.git
cd marketplace-api
make build 
make up 
make migrate
make generate-keypair
make cache-warmup
```

После чего приложение будет доступно по ссылке: http://127.0.0.1:888

### Запуск unit-тестов:

```shell
make test
```

### Методы API:

#### POST /api/users -H "Content-Type: application/json"

| Описание            | Создание пользователя                             |
|---------------------|---------------------------------------------------|
| Входящий интерфейс  | [UserCredentials](src/DTO/User/CreateUserDTO.php) |
| Исходяший интерфейс | `{ulid: string}`                                  |

#### POST /api/auth/token/login -H "Content-Type: application/json"

| Описание            | Авторизация пользователя                          |
|---------------------|---------------------------------------------------|
| Входящий интерфейс  | [UserCredentials](src/DTO/User/CreateUserDTO.php) |
| Исходяший интерфейс | `{token: string, refreshToken: string}`           |

### POST /api/auth/token/refresh -H "Content-Type: application/json"

| Описание            | Обновление токена                       |
|---------------------|-----------------------------------------|
| Входящий интерфейс  | `{refreshToken: string}`                |
| Исходяший интерфейс | `{token: string, refreshToken: string}` |

### GET /api/users/me -H "Authorization: Bearer {token}"

| Описание            | Получение информации об авторизованном пользователе                                                                   |
|---------------------|-----------------------------------------------------------------------------------------------------------------------|
| Входящий интерфейс  | -                                                                                                                     |
| Исходяший интерфейс | `{ulid: string, email: string, posts: {ulid: string, title: string}[], images: {ulid: string, contentUrl: string}[]}` |

### DELETE /api/users/me -H "Authorization: Bearer {token}"

| Описание            | Удаление пользователя |
|---------------------|-----------------------|
| Входящий интерфейс  | -                     |
| Исходяший интерфейс | -                     |

### POST /api/images -H "Authorization: Bearer {token}" -H "Content-Type: multipart/form-data"

| Описание            | Сохранение изображения               |
|---------------------|--------------------------------------|
| Входящий интерфейс  | `{file: blob}`                       |
| Исходяший интерфейс | `{ulid: string, contentUrl: string}` |

### POST /api/post -H "Authorization: Bearer {token}" -H "Content-Type: application/json"

| Описание            | Сохранение поста                       |
|---------------------|----------------------------------------|
| Входящий интерфейс  | [Post](src/DTO/Post/CreatePostDTO.php) |
| Исходяший интерфейс | `{ulid: string}`                       |

### GET /api/post

| Описание            | Получение постов (пагинация + сортировка + фильтрация). При вызове с токеном авторизации (Authorization: Bearer {token}) добавляется проверка на принадлежность поста автору |
|---------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Пример запроса:     | (http://127.0.0.1:888/api/posts?page=1&limit=10&sort[createdAt]=asc&sort[cost]=desc&filter[cost][gte]=100&filter[cost][lte]=3000)                                            |
| Входящий интерфейс  | -                                                                                                                                                                            |
| Исходяший интерфейс | `{totalCount: int, pageCount: int, result: {ulid: string, title: string, cost: int, description: string, isOwner: bool, imageUrls: string[]}[]}`                             |

### DELETE /api/posts/{uuid} -H "Authorization: Bearer {token}"

| Описание            | Удаление поста |
|---------------------|----------------|
| Входящий интерфейс  | -              |
| Исходяший интерфейс | -              |

### PUT /api/posts/{uuid} -H "Authorization: Bearer {token}"

| Описание            | Редактирование поста                   |
|---------------------|----------------------------------------|
| Входящий интерфейс  | [Post](src/DTO/Post/CreatePostDTO.php) |
| Исходяший интерфейс | -                                      |
