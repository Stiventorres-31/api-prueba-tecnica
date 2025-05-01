# Prueba Técnica - Módulo de Pagos

Este repositorio contiene la implementación de un sistema de creación y procesamiento de pagos, desarrollado como parte de una prueba técnica.


## Antes de iniciar el proyecto, correr las migraciones y los seeder

## La rama principal de este repositorio es `development`

## 🧩 Funcionalidades

- Creación de clientes (`Customer`)
- Creación de transacciones (`Transaction`)
- Cálculo de comisiones por tipo de método de pago (`PaymentMethod`)
- Procesamiento de pagos (`generatePayment`)
- Validación robusta con Form Requests
- Control de errores con logs y transacciones


## 📁 Arquitectura usada
- Arquitectura en capas 

## Patrones usado
-Repository Pattern: separa la lógica de negocio de la lógica de acceso a datos.
-Service Layer: extrae la lógica del controlador para mantenerlo delgado y limpio.
-Middleware personalizado: protección contra XSS y saneamiento de entrada.


## 📁 Middleware

- Se establecio un `middleware` global para las rutas del archivo `api.php`. El middleware analiza cada input que entra para no recibir codigo malicioso.

## 🧬 Migraciones refactorizada

- `create_customers_table`
- `create_transactions_table`
- `create_payment_methods_table`

## 🧾 Modificaciones a la base de datos

- Se creo relaciones en `transactions` con `customers`
- Se añadió `payment_method_id` a la tabla `transactions` como clave foránea.
- Campo `preferences` en `customers` para almacenar datos dinámicos del cliente.

## 💾 Scripts y Backup

- Todas las migraciones están disponibles en `database/migrations/`.
- Seeder con métodos de pago: `PaymentMethodsTableSeeder`.

## 🧠 Decisiones técnicas

- Se usó Eloquent para mejorar mantenibilidad, casting automático y relaciones.
- Se encapsuló validación en `FormRequest` para control limpio de errores 422.
- Se usaron transacciones para asegurar integridad entre cliente y transacción.
- `metadata` y `preferences` permiten flexibilidad y trazabilidad.
- Se crearon los modelos de cada migración para definir la logica del negocio y sus relaciones.
- se realizaron test para revisar le funcionamiento de las rutas y services
---
