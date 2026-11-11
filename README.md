# Resort Digital · Rutas y control de acceso con middlewares

Práctica de Laravel: un "portero de discoteca" virtual que protege las zonas de un resort según el estado de sesión, el rol y la edad del usuario.

## Zonas y protección

| Ruta        | Vista                 | Middlewares              | Si no cumple                                   |
|-------------|-----------------------|--------------------------|------------------------------------------------|
| `/`         | `home`                | —                        | —                                              |
| `/login`    | `login`               | —                        | —                                              |
| `/register` | `register`            | —                        | —                                              |
| `/panel`    | `panel-usuario`       | `CheckAuth`              | Redirige a `/login`                            |
| `/casino`   | `casino`              | `CheckAuth`, `CheckAge`  | Redirige a `/panel` con "Acceso denegado: Debes ser mayor de edad" |
| `/admin`    | `panel-admin`         | `CheckAuth`, `CheckAdmin`| Error 403                                      |

- Middlewares: `app/Http/Middleware/CheckAuth.php`, `CheckAdmin.php`, `CheckAge.php`
- Rutas: `routes/web.php`
- Migración de `users` con `is_admin` (boolean, por defecto `false`) y `age` (entero): `database/migrations/0001_01_01_000000_create_users_table.php`

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

## Usuarios de prueba (contraseña: `password`)

| Email                | Edad | Admin |
|----------------------|------|-------|
| `admin@resort.test`  | 40   | Sí    |
| `adulto@resort.test` | 25   | No    |
| `menor@resort.test`  | 15   | No    |

## Tests

```bash
php artisan test
```
