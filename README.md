# 🎓 Sistema de Gestión Universitaria

Un panel de administración moderno e intuitivo (estilo SaaS) desarrollado en Laravel para gestionar los procesos operativos centrales de una institución educativa. 

Este sistema permite la administración completa de usuarios, roles, alumnos, profesores, plan de estudios (materias), matriculaciones y control diario de asistencia con cálculo automático de porcentajes.

## 🚀 Características Principales

* **Seguridad y Roles:** Gestión de permisos y roles utilizando `spatie/laravel-permission`.
* **Módulo de Usuarios:** ABM completo para el personal administrativo y usuarios del sistema.
* **Módulo de Plan de Estudios (Materias):** Registro y administración de asignaturas.
* **Módulo de Alumnos:** ABM de estudiantes con asignación de número de legajo/matrícula.
* **Módulo de Profesores:** ABM del plantel docente con registro de título profesional.
* **Gestión de Asignaciones:** Interfaz dinámica mediante modales para asignar profesores a materias y matricular alumnos.
* **Control de Asistencia:** Planilla virtual interactiva para registrar la asistencia diaria (4 bloques horarios) con cálculo de porcentaje en tiempo real.
* **Interfaz Gráfica:** Diseño responsivo, limpio y corporativo construido con **Bootstrap 5**.

## 🛠️ Tecnologías Utilizadas

* **Framework:** [Laravel 13.x](https://laravel.com/)
* **Lenguaje:** PHP 8.3+
* **Base de Datos:** SQLite (Ideal para portabilidad y fácil instalación)
* **Frontend:** Blade, Bootstrap 5, JavaScript Vanilla (sin dependencias complejas)
* **Paquetes Destacados:** Spatie Laravel Permission

---

## ⚙️ Instalación Local (Paso a Paso)

Sigue estas instrucciones para ejecutar el proyecto en tu máquina local.

### 1. Requisitos Previos
Asegúrate de tener instalado en tu computadora:
* [PHP >= 8.2](https://www.php.net/downloads) (Se recomienda 8.3)
* [Composer](https://getcomposer.org/)
* [Node.js y NPM](https://nodejs.org/es/) (Para compilar assets base de Laravel)
* Git

### 2. Clonar el Repositorio
Abre tu terminal y ejecuta:
```bash
git clone [https://github.com/tu-usuario/sistema-universitario.git](https://github.com/tu-usuario/sistema-universitario.git)
cd sistema-universitario