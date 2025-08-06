# API Parkings CRUD

## Descripción

Este proyecto es una API REST desarrollada en Laravel 12 para gestionar parkings. Permite:

- Crear, leer, actualizar y eliminar parkings.
- Consultar el parking más cercano a una ubicación geográfica dada (latitud, longitud).
- Notificar si el parking más cercano está a más de 500 metros del punto consultado.
- Autenticación simple con Laravel Sanctum para proteger los endpoints.

---

## Requisitos

- Docker y Docker Compose
- PHP 8.1+

---

## Levantar el proyecto con Docker

    1. Clonar el repositorio:

        - git clone https://github.com/ramonsebastianluna/parkings-api
        - cd parkings-api

    2. Construir y levantar los contenedores:

        - docker compose up --build -d

    3. Ejecutar migraciones:

        - docker exec -it laravel-app php artisan migrate

    4. Cargar datos de prueba:

        - docker exec -it laravel-app php artisan db:seed

    5. Acceder a la app en: http://localhost:9000

    6. PhpMyAdmin disponible en: http://localhost:8080
       Usuario y contraseña configurados en docker-compose.

## Endpoints disponibles
#### Autenticación

| Método | Ruta           | Descripción           | Requiere token |
|--------|----------------|-----------------------|----------------|
| POST   | /api/register  | Registrar nuevo usuario| No             |
| POST   | /api/login     | Login y obtención token| No             |
| POST   | /api/logout    | Cerrar sesión         | Sí             |

#### Parkings (protegidos por Sanctum)
| Método  | Ruta                                      | Descripción                             |
|---------|-------------------------------------------|---------------------------------------|
| GET     | /api/parkings                            | Listar todos los parkings             |
| POST    | /api/parkings                            | Crear un nuevo parking                 |
| GET     | /api/parkings/{id}                       | Obtener un parking por ID              |
| PUT     | /api/parkings/{id}                       | Actualizar un parking por ID           |
| DELETE  | /api/parkings/{id}                       | Eliminar un parking por ID             |
| GET     | /api/parkings/nearest?latitud=&longitud=| Obtener parking más cercano a un punto geográfico |

## Ejemplo de payload para crear un parking
``` json
{
  "nombre": "parking-01",
  "direccion": "Calle Falsa 123",
  "latitud": -34.6037,
  "longitud": -58.3816
}
```

## Notas importantes

    La API usa tokens Bearer para autenticación después del login.

    Si la distancia al parking más cercano supera los 500 metros, se genera una notificación en la base de datos con la latitud y longitud consultadas.

    Las validaciones de datos están aplicadas en todos los endpoints.

    La documentación Swagger está disponible en /api/documentation.

## Documentación API con Swagger

    Para generar la documentación, se usa el paquete l5-swagger.
    Para regenerar la documentación:

    docker exec -it laravel-app php artisan l5-swagger:generate

## Uso de la colección Postman

    La colección Postman para probar la API está disponible en storage/postman/ParkingsAPI.postman_collection.json.

    Para usarla:

    Importa el archivo en Postman.

    Configura la variable base_url a http://localhost:9000/api.

    Ejecuta las peticiones según corresponda.

## Seeder para datos iniciales

    Se incluye un seeder para crear un usuario admin y parkings de prueba.

    Para ejecutar el seeder:

    docker exec -it laravel-app php artisan db:seed

## Consideraciones finales

    Para el desarrollo local se recomienda usar Docker para evitar problemas de configuración.

    Las rutas y lógica están pensadas para ser simples y cumplir con los requerimientos mínimos del challenge.

    Para mejoras futuras se pueden agregar paginación, manejo avanzado de errores y roles de usuario.

Autor

Seba Luna
(ramon.sebastian.luna@gmail.com)

Licencia

MIT License