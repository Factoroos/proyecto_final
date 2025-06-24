# 📁 Proyecto Final – Portafolio CRUD con API REST

Este proyecto es un sistema web de portafolio personal que permite gestionar proyectos mediante una interfaz responsiva y moderna utilizando **PHP + MySQL + Bootstrap**, implementando una **API REST** para la manipulación de datos. Ha sido desarrollado como evaluación final para la asignatura **Diseño y Desarrollo Web + IA**.

---

## 🌐 Enlace Público

🔗 Accede al sistema desplegado en el entorno institucional:  
**[https://teclab.uct.cl/~jorge.sepulveda/](https://teclab.uct.cl/~jorge.sepulveda/)**  
🔐 Login de usuario:  
**[https://teclab.uct.cl/~jorge.sepulveda/proyecto_final/login.php](https://teclab.uct.cl/~jorge.sepulveda/proyecto_final/login.php)**

---

## 🧰 Tecnologías Utilizadas

- **PHP 8.x** – Lenguaje de servidor para la lógica backend
- **MySQL** – Base de datos relacional
- **Bootstrap 5.3 (CDN)** – Framework CSS para diseño responsivo
- **cURL (PHP)** – Comunicación con la API REST
- **Apache2** – Servidor web utilizado en el entorno de despliegue
- **HTML5** – Estructura semántica
- **JavaScript mínimo** – Validaciones básicas

---

## 📦 Funcionalidades del Proyecto

- ✅ Autenticación con sesiones (`login.php`, `logout.php`)
- ✅ CRUD completo (Crear, Leer, Editar, Eliminar proyectos)
- ✅ Subida de imágenes con validación de tipo y tamaño
- ✅ API REST propia en PHP (`proyectos.php`)
- ✅ Uso de `_method=DELETE` y `_method=PATCH` para compatibilidad
- ✅ Diseño responsivo y semántico con Bootstrap
- ✅ Visualización amigable de los proyectos en tarjetas
- ✅ URLs externas (GitHub, Producción) por proyecto

---

## 🤖 Uso de Inteligencia Artificial

### Herramientas de IA utilizadas:

- **ChatGPT (OpenAI)**  
enlace historial chatgpt
**[https://chatgpt.com/share/685aee40-0e50-800d-a465-61bc5cfdd83f](https://chatgpt.com/share/685aee40-0e50-800d-a465-61bc5cfdd83f)**
- **GitHub Copilot (VSCode)**

### Aportes de la IA al desarrollo:

- ✨ **Generación inicial del esqueleto CRUD** (formularios, validaciones básicas, rutas)
- 🧠 **Depuración de errores HTTP 403 y 405** provocados por el servidor institucional
- 🔄 **Refactorización de métodos bloqueados (`DELETE`, `PATCH`)**, implementando `_method` en `POST`
- 🔒 **Mejora en validaciones de seguridad** en formularios y carga de archivos
- 💬 **Mensajes de error personalizados** con mejor UX
- 📜 **Redacción de este `README.md`** con formato profesional y completo

### Modificaciones realizadas sobre el contenido generado por IA:

- Se ajustaron las rutas API y lógica de control para adaptarlas al servidor real (`PATH_INFO` en `$_SERVER`)
- Se validó la lógica con pruebas reales y se corrigieron errores de conexión `curl` (timeouts)
- Se reemplazaron partes del código para que funcionaran con restricciones específicas del servidor institucional
- Se adaptaron los estilos generados por IA para cumplir estrictamente con el diseño Bootstrap 5.3 desde CDN

---

## 🗂️ Instrucciones de Despliegue (opcional)

1. Subir los archivos a un servidor con soporte PHP (como Apache)
2. Configurar el archivo `config.php` con los datos de conexión de la base de datos
3. Crear la base de datos con la siguiente estructura:

```sql
CREATE TABLE proyectos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255),
  descripcion TEXT,
  url_github VARCHAR(255),
  url_produccion VARCHAR(255),
  imagen VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
