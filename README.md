¡Aquí tienes tu `README.md` completo y mejorado! Está listo para copiar, pegar y usar directamente en tu repositorio GitHub:

---

```md
# 📚 Biblioteca-IEP

Sistema web para gestión de biblioteca virtual, con control de libros, préstamos y reservas, desarrollado en PHP y Tailwind CSS.

---

## 🚀 Características principales

- ✅ Registro y login de usuarios
- 🔐 Roles: Bibliotecario y Lector
- 📚 CRUD de libros
- 📆 Préstamos con fecha de vencimiento
- 🔖 Reservas con cola y prioridad
- 🛎 Notificaciones automáticas
- 🎨 Interfaz moderna con Tailwind CSS
- 🧩 Patrón MVC con buenas prácticas
- 🗃 Control de versiones en Git y GitHub

---

## 🧱 Arquitectura del Proyecto

El proyecto sigue una estructura tipo MVC:

```

Biblioteca/
│
├── controllers/        # Controladores (LibroController, AuthController, etc.)
├── models/             # Modelos (Libro, Usuario, Reserva, Prestamo)
├── views/              # Vistas divididas por módulos (libros, prestamos, reservas)
├── index.php           # Punto de entrada del sistema
├── config.php          # Conexión a la base de datos
└── assets/             # Recursos estáticos (si aplica)

````

---

## 🛠 Requisitos Técnicos

- PHP 8.0+
- MySQL 5.7+
- Servidor local (XAMPP, Laragon, etc.)
- Navegador actualizado
- Git (para control de versiones)

---

## ⚙️ Instalación

1. Clona el repositorio:

```bash
git clone https://github.com/ysoto-m/Bliblioteca-IEP.git
````

2. Copia la carpeta a tu entorno local (por ejemplo: `C:/xampp/htdocs/Biblioteca`)
3. Crea una base de datos llamada `biblioteca` y **importa el archivo SQL** desde `/database/biblioteca.sql`
4. Abre en tu navegador:

```
http://localhost/Biblioteca
```

5. Listo ✅

---

## 📦 Versiones

| Versión | Descripción                               |
| ------- | ----------------------------------------- |
| v1.0    | Login, registro, CRUD de libros           |
| v1.1    | Préstamos y control de disponibilidad     |
| v1.2    | Reservas con prioridad                    |
| v1.3    | Notificaciones internas                   |
| v1.4    | Mejoras de interfaz, validaciones y roles |

---

## 👨‍💻 Autor

* GitHub: [ysoto-m](https://github.com/ysoto-m)

---
