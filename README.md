# Prueba Técnica - Módulo de Pagos

Este repositorio contiene la implementación de un sistema de creación y procesamiento de pagos, desarrollado como parte de una prueba técnica.

## 🧩 Funcionalidades

- Creación de clientes (`Customer`)
- Creación de transacciones (`Transaction`)
- Cálculo de comisiones por tipo de método de pago (`PaymentMethod`)
- Procesamiento de pagos (`generatePayment`)
- Validación robusta con Form Requests
- Control de errores con logs y transacciones


## 🧪 Rutas principales

- `POST /api/create-payment` → Crea una transacción pendiente
- `POST /api/generate-payment` → Completa el pago

## 📂 Nuevos directorios

- `app/Models/Customer.php`
- `app/Models/Transaction.php`
- `app/Models/PaymentMethod.php`
- `app/Http/Requests/CreatePaymentRequest.php`
- `app/Http/Requests/GeneratePaymentRequest.php`
- `app/Http/Requests/GetTransactionRequest.php`
- `app/Helpers/ResponseHelper.php`

## 📂 Estructura importante

- `app/Models/Customer.php`
- `app/Models/Transaction.php`
- `app/Http/Requests/CreatePaymentRequest.php`
- `app/Http/Requests/GeneratePaymentRequest.php`

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
- Se crearon los modelos de cada migración para definir la logica del negocio y sus relaciones

---
