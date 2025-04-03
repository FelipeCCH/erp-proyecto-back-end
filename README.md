# 🏢 ERP SupplyNet – Backend

Este repositorio contiene el backend de un sistema ERP para la gestión de suministros, desarrollado en **Laravel**. El sistema permite a medianas y grandes empresas gestionar proveedores, usuarios, productos, pedidos y facturación a través de una API RESTful robusta, facilitando la integración con el frontend.

---

## 🚀 Tecnologías utilizadas

- **Laravel** (Framework PHP)
- **PHP 8.x**
- **MySQL** (o cualquier base de datos compatible, configurada en el archivo `.env`)
- **Composer** (gestión de dependencias)
- Funcionalidades nativas de Laravel (migraciones, colas, jobs, etc.)

---

## 📦 Requisitos previos

- PHP 8.x instalado
- Composer
- MySQL (u otra base de datos compatible)
- (Opcional) Laragon para un ambiente de desarrollo integrado

---

## 🛠️ Instalación

1. **Clonar el repositorio:**

   ```bash
   git clone https://github.com/TuUsuario/erp-proyecto-back-end.git
   ```
Instalar las dependencias:

Navega al directorio del proyecto y ejecuta:

bash

Copiar

cd erp-proyecto-back-end

composer install

Configurar el entorno:

Copia el archivo .env.example a .env:

bash

Copiar

cp .env.example .env

Edita el archivo .env para configurar la conexión a la base de datos y otras variables necesarias.

Generar la clave de la aplicación:

bash

Copiar

php artisan key:generate

Ejecutar las migraciones:

Esto creará las tablas en la base de datos según la estructura definida en las migraciones.

bash

Copiar

php artisan migrate

Iniciar el servidor de desarrollo:

bash

Copiar

php artisan serve




