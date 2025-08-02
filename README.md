Инструкция по запуску приложения:
1. git clone https://github.com/RAdKuzur/MKK_Luna.git
2. в папке проекта в терминале: docker compose up
3. открыть ещё один терминал и написать: docker compose exec app composer install
4. для создания .env: docker compose exec app cp .env.example .env
5. для применения миграций и наполения данными написать: docker compose exec app php artisan migrate:fresh --seed
6. для получения токена перейти на http://localhost:8080/ указать любые username и password
7. для Swagger: docker compose exec app php artisan l5-swagger:generate и перейти на http://localhost:8080/api/documentation    
