# Sistema de Tareo y Control

Sistema web para el **control de asistencia, tareo diario y cálculo de jornales** de trabajadores de campo organizados en cuadrillas. Reemplaza el cuaderno de tareo y las hojas de Excel: el supervisor registra quién asistió, en qué actividad trabajó y cuántas horas, y el sistema calcula cuánto se le debe pagar a cada uno.

Es la versión web del mismo problema que resuelve [TareoApp](https://github.com/DRXD13/TareoApp) (Android): aquí el registro lo hace la oficina o el supervisor desde el navegador.

[**Ver demo**](https://sistema-tareo-control.onrender.com)

> La demo corre en el plan gratuito de Render: la primera carga puede tardar cerca de un minuto. Todos los datos son ficticios.

![Panel de control analítico](https://raw.githubusercontent.com/DRXD13/portafolio/main/assets/tareo-panel.png)

---

## Qué resuelve

En el agro y en otras operaciones de campo, cada día hay que anotar qué trabajadores asistieron, en qué labor estuvieron y cuántas horas trabajaron. Al final del periodo, esas anotaciones se convierten en el pago por jornal. Hacerlo a mano genera duplicados, horas mal sumadas y pagos incorrectos.

## Funcionalidades

**Maestros**
- Áreas, cargos, cuadrillas y actividades, con activación y desactivación.
- Trabajadores con tipo y número de documento, área, cargo, cuadrilla, jornal diario y fecha de ingreso.

**Operación diaria**
- **Asistencia:** registro de ingreso y salida con estado (puntual, tardanza, falta justificada, descanso médico o permiso especial) y observaciones. Bloquea el registro duplicado del mismo trabajador en el mismo día.
- **Tareo diario masivo:** por cada trabajador, la actividad, el estado (presente, faltó o tardanza), las horas trabajadas y observaciones.
- **Actividades diarias:** descripción de lo realizado por cada trabajador.

**Pago y reportes**
- **Cálculo de jornales** por rango de fechas: `(jornal diario ÷ 8) × horas trabajadas`.
- **Cierre de la planilla de jornales** al historial contable, sin duplicar registros.
- Resumen de planilla y **exportación a Excel**.
- Panel analítico con la tendencia de asistencias y faltas de los últimos días y la distribución del personal.

**Seguridad y control**
- Inicio de sesión con contraseñas cifradas (`password_hash`).
- Cierre de sesión automático tras 30 minutos de inactividad.
- **Bitácora** de las acciones de cada usuario.

## Tecnologías

| Capa | Tecnología |
|------|------------|
| Backend | PHP 8.2, arquitectura MVC |
| Base de datos | MySQL con PDO (consultas preparadas, utf8mb4) |
| Frontend | Bootstrap 5, Bootstrap Icons, Chart.js, SweetAlert2 |
| Despliegue | Docker (php:8.2-apache) en Render, MySQL en la nube con SSL |

## Estructura

```
sistema-tareo-control/
├── config/conexion.php   Conexión PDO; lee variables de entorno en producción
├── models/               Acceso a datos: Trabajador, Asistencia, Tareo, Jornal, Reporte, Bitacora…
├── controllers/          Lógica de cada módulo
├── views/                Pantallas del sistema
├── assets/css/           Estilos
├── index.php             Punto de entrada y enrutamiento
├── Dockerfile            Imagen con Apache, PDO MySQL y zona horaria de Perú
└── render.yaml           Configuración de despliegue en Render
```

## Instalación local

**Requisitos:** PHP 8 y MySQL (por ejemplo, con XAMPP).

1. Clona el repositorio dentro de `htdocs`:
   ```bash
   git clone https://github.com/DRXD13/sistema-tareo-control.git
   ```
2. Crea la base de datos `sistema_tareo_control` en MySQL.
3. Configura la conexión. En local se usan los valores por defecto de XAMPP (`localhost`, `root`, sin contraseña). En producción se leen de las variables de entorno `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME` y `DB_SSL`.
4. Crea el usuario administrador abriendo `crear_admin.php` en el navegador. Este archivo se elimina automáticamente en la imagen de Docker.
5. Entra a `http://localhost/sistema-tareo-control`.

## Despliegue con Docker

```bash
docker build -t sistema-tareo-control .
docker run -p 8080:80 -e DB_HOST=... -e DB_USER=... -e DB_PASS=... -e DB_NAME=... sistema-tareo-control
```

## Autor

**Dangelo Retis Cabrera** · Estudiante de Ingeniería de Sistemas · Pisco, Perú

[Portafolio](https://portafolio-phi-roan-28.vercel.app) · [LinkedIn](https://www.linkedin.com/in/dangelo-retis-cabrera-a2b5a7370/) · [GitHub](https://github.com/DRXD13)
