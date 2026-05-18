# SWO2 Demo (Laravel 11)

Eén lange Laravel-pagina die de voortgang van het hostingplatform demonstreert..

## Installeren

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Open daarna http://localhost:8000.

## Proxmox screenshot

Plaats je screenshot als `public/afbeeldingen/proxmox-omgeving.jpg`. De welcome
view laadt deze afbeelding automatisch.

## Deploy

Wijs je webserver (Apache/Nginx) document root naar de `public/` map.
