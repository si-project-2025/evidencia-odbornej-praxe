## Spustenie cez Docker

### Konfigurácia prostredia
Nastaviť v `.env` súbore backendu:

```
DB_HOST=mysql
DB_DATABASE=si_project_2025
DB_USERNAME=si_project_2025
DB_PASSWORD=si_project_2025
```

### Spustenie aplikácie
```
docker compose up --build
docker compose exec backend php artisan migrate:fresh --seed
```

---

## Spustenie lokálne

### Backend
```
cd si_project_2025_be
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### Frontend
```
cd si_project_2025_fe
npm install
npm run dev
```

### Databáza
- spustiť databázový server
- vytvoriť databázu s názvom _si_project_2025_
