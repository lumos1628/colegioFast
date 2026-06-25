# CONTEXTO.md — colegioFast

> Este archivo es la fuente de verdad del proyecto. Cualquier agente de IA
> (OpenCode u otro) que trabaje en este repo debe leer este archivo ANTES
> de generar código, y debe actualizarlo después de completar cada paso.

---

## 1. Resumen del proyecto

**Dolor del problema:** la gestión escolar en primaria (notas en Excel o
papel, asistencia manual, sin trazabilidad por competencias CNEB) le quita
tiempo al docente y no le da visibilidad real del progreso del hijo al
padre de familia.

**Solución:** colegioFast — sistema de gestión académica para educación
primaria en Perú, alineado al marco curricular Minedu/CNEB (evaluación
por competencias y capacidades).

**Stack:** Laravel + PostgreSQL + Blade (sin SPA, sin Vue/React — cada
tarjeta de la interfaz es una página/ruta real, no un panel manejado con
JS).

**Versión 2 (portafolio Speux, fuera del alcance de esta entrega):**
Next.js + TypeScript + Tailwind + Prisma, mismo modelo de datos.

---

## 2. Roles del sistema

`admin`, `director`, `docente`, `alumno`, `padre`, `psicologo`, `secretaria`

El rol Docente es la prioridad de UX del proyecto — es el flujo más
desarrollado y el que más debe sentirse simple e intuitivo.

---

## 3. Decisiones de arquitectura (no obvias mirando el código)

- **`asignaciones` es la tabla central.** Relaciona docente + curso +
  periodo_academico. Todo lo demás (matrículas, actividades, notas,
  asistencias) cuelga de una asignación específica, NUNCA directo de
  `cursos` o `docentes`.
- **`notas` vs `notas_bimestrales`:** `notas` es el registro granular por
  actividad. `notas_bimestrales` es una VIEW de PostgreSQL (no tabla
  física) que promedia `notas` por alumno/asignación/competencia/periodo.
  No generar triggers para mantenerla actualizada — se recalcula sola en
  cada consulta.
- **Privacidad:** `notas.visible_para_alumno` (boolean) controla si una
  nota de control pedagógico interno se oculta al alumno pero se muestra
  al padre. `bitacora_psicologica` es privada por completo — no debe ser
  accesible desde seeders/factories de otros roles ni desde ningún
  controlador fuera del ecosistema Psicólogo.
- **Auditoría:** trigger de PostgreSQL `trg_auditoria_notas` inserta en
  `auditoria_notas` cada vez que se hace UPDATE sobre `notas.calificacion`.
  Si una nota ya existe para (actividad_id, alumno_id), el controlador
  debe hacer UPDATE, nunca un INSERT duplicado — el UNIQUE constraint lo
  impide.
- **Notificaciones:** tabla física `notificaciones` (no calculada al
  vuelo) para persistir alertas del padre con estado leído/no leído.
- **Contraseñas:** bcrypt (`Hash::make`) vía Laravel. Nunca MD5, aunque
  la rúbrica del curso lo mencione (ver sección 7).
- **Convención de nombres:** todo en español, snake_case en BD
  (`periodos_academicos`, `incidencias_conducta`).
- **Principio de UI:** "cada tarjeta es también una página" — la
  NAVEGACIÓN (entrar a un curso, a una ficha de alumno, a crear actividad)
  siempre es una ruta Blade real, nunca un panel manejado con estado JS.
  Esto NO significa cero JavaScript: para interacciones puntuales que el
  propio CUS exige sean instantáneas (CUS-03: filtro de búsqueda al
  tipear) se usa JS vanilla mínimo sin framework, como progressive
  enhancement sobre el HTML ya renderizado — nunca un SPA ni manejo de
  rutas en el cliente. El switch de asistencia (CUS-04) cambia visualmente
  con JS local, pero el guardado real sigue siendo un solo POST en lote
  al tocar "Guardar", no una petición por cada switch.

---

## 4. Esquema de base de datos (resumen)

| Tabla | Propósito |
|---|---|
| `users` | Autenticación base, campo/relación `role` |
| `alumnos` | Datos del estudiante, 1-1 opcional con `users` |
| `docentes` | 1-1 con `users`, especialidad |
| `padres` | 1-1 con `users` |
| `alumno_padre` | Pivote N-N alumno-tutor |
| `periodos_academicos` | Bimestres/semestres |
| `cursos` | Nombre, grado |
| `asignaciones` | **Tabla central**: docente + curso + periodo |
| `matriculas` | Alumno matriculado en una asignación. UNIQUE(alumno_id, asignacion_id) |
| `competencias` | Marco CNEB |
| `capacidades` | Hijas de una competencia |
| `actividades` | De una asignación, con competencia + capacidad obligatorias |
| `notas` | Nota por actividad. UNIQUE(actividad_id, alumno_id) |
| `notas_bimestrales` | VIEW, promedio por competencia/periodo |
| `asistencias` | UNIQUE(alumno_id, asignacion_id, fecha) |
| `incidencias_conducta` | Faltas leves/graves/méritos |
| `bitacora_psicologica` | Privada, solo Psicólogo |
| `pagos` | Con `periodo_academico_id` |
| `auditoria_notas` | Histórico de cambios de calificación |
| `notificaciones` | Alertas persistidas para el padre |

---

## 5. Estado actual del proyecto

- [x] **Migraciones + seeders** — completo y verificado (19 migraciones,
      18 seeders, 64 tests pasando, trigger de auditoría verificado con
      UPDATE real, VIEW `notas_bimestrales` devuelve datos correctos)
- [x] **Modelos Eloquent** — completo (16 modelos, relaciones, $fillable,
      $casts)
- [x] **Rutas + controladores Docente (completo)** — Sesiones 1.1, 1.2, 1.3 y 1.4 completadas:
      dashboard con lista de cursos del día actual, detalle de curso con
      alumnos matriculados, ficha de alumno con notas/asistencias/incidencias,
      búsqueda dinámica de alumnos, CRUD de actividades por competencias,
      registro de calificaciones en lote con UNIQUE constraint,
      registro de asistencia diaria con guardado en lote, vista de horario
      semanal completo (64 tests pasando)
- [x] **Sistema de horarios** — asignaciones con día_semana, hora_inicio, hora_fin
- [x] **Matrícula automática por grado/sección** — MatriculaService implementado
- [x] **Navegación dinámica por rol** — portal-layout actualizado
- [x] **Frontend Docente mejorado** — Sistema de diseño con componentes reutilizables
      (card, button, badge, alert, breadcrumb), sidebar con navegación contextual,
      dashboard con estadísticas, tarjetas de curso mejoradas, vistas responsive
      con Tailwind CSS, iconos SVG, gradientes y animaciones sutiles. Barra lateral
      profesional con datos del docente (nombre, especialidad, email, teléfono) y
      lista de cursos agrupados por día de la semana, siempre visible en desktop
      y colapsable en mobile. Layout de dos columnas consistente en dashboard,
      vista de curso y ficha de alumno: contenido principal a la izquierda con
      scroll, estadísticas a la derecha en columna sticky (fija al hacer scroll).
      Tarjetas de curso completamente clickeables (toda la tarjeta es un enlace).
- [x] **Datos de prueba expandidos** — 96 cursos (6 grados × 2 secciones × 8 materias),
      108 asignaciones distribuidas equitativamente entre 6 docentes (18 cursos por
      docente, 3-5 cursos por día). Botones de cerrar sesión visibles en dashboard,
      sidebar y header mobile.
- [ ] **Rutas + controladores + vistas Alumno**
- [ ] **Rutas + controladores + vistas Padre**
- [ ] **Back-office (Secretaria, Director, Psicólogo, Admin)**
- [ ] **Reportes Excel formato Minedu**
- [ ] **Tests Pest de flujos críticos**

> Actualizar este checklist cada vez que se complete un paso.

---

## 6. Rutas definidas — ecosistema Docente

```
GET  /docente                              → dashboard con cursos del día actual
GET  /docente/horario                      → horario semanal completo
GET  /docente/cursos/{asignacion}          → detalle de curso con alumnos + búsqueda
GET  /docente/cursos/{asignacion}/alumnos/{alumno} → ficha de alumno con notas/asistencias/incidencias
GET  /docente/cursos/{asignacion}/actividades → lista de actividades del curso
GET  /docente/cursos/{asignacion}/actividades/crear → formulario crear actividad
POST /docente/cursos/{asignacion}/actividades → guardar nueva actividad
GET  /docente/cursos/{asignacion}/actividades/{actividad} → detalle de actividad + calificaciones
POST /docente/cursos/{asignacion}/actividades/{actividad}/notas → guardar calificaciones en lote
GET  /docente/cursos/{asignacion}/asistencia → registro de asistencia diaria
POST /docente/cursos/{asignacion}/asistencia → guardar asistencia en lote
```

Pendientes de implementar:
```
(Rutas de Alumno, Padre y Back-office: pendientes de definir cuando se
llegue a ese paso — no inventarlas anticipadamente.)
```

---

## 7. Requisitos de evaluación del curso (rúbrica del profesor)

| # | Requisito de la rúbrica | Cómo lo cumple colegioFast |
|---|---|---|
| 1 | Dolor del problema | Ver sección 1 |
| 1.1 | Roles | 7 roles (ver sección 2), más rico que el mínimo exigido |
| 2 | Solución | Ver sección 1 |
| 3 | MER (tablas, relaciones uno-muchos) | Ver sección 4 |
| 4 | Laravel: Models, factory, seeders, belongsTo | Completo (sección 5) |
| 5 | Vista diseñador de BD | Usar pgAdmin ERD Tool o `\d` en psql (proyecto en Postgres, no MySQL) |
| 6 | Contraseñas encriptadas (rúbrica dice MD5) | Se usa bcrypt — MD5 está roto criptográficamente. Documentar esta decisión técnica en el informe si el profesor pregunta por qué no se usó MD5 |
| 7 | Procedimiento, trigger | Trigger `trg_auditoria_notas` (ver sección 3) |
| 8 | Exposición 20 min + 5 preguntas | Pendiente preparar guión de demo |
| 9 | Corrección de notas en el momento | Resuelto por UPDATE + trigger de auditoría |
| 10 | Tono formal | Aplicar en informe y SRS |
| 11 | Informe de requerimientos (sube 1 persona) | Coordinar con el equipo quién lo sube |

---

## 8. Casos de uso (CUS) — deben coincidir con lo implementado

| CUS | Caso de uso | Prioridad | Tabla(s) involucrada(s) |
|---|---|---|---|
| CUS-01 | Iniciar sesión / autenticar usuario | Alta | `users` |
| CUS-02 | Cerrar sesión segura | Alta | — |
| CUS-03 | Buscar y filtrar alumnos dinámicamente | Media | `matriculas`, `alumnos` |
| CUS-04 | Registrar asistencia diaria | Media | `asistencias` |
| CUS-05 | Gestionar actividades por competencias | Alta | `actividades`, `competencias`, `capacidades` |
| CUS-06 | Registrar calificaciones | Alta | `notas` |
| CUS-07 | Registrar incidencias de conducta | Media | `incidencias_conducta` |
| CUS-08 | Consultar actividades pendientes | Alta | `actividades`, `notas` |
| CUS-09 | Consultar calificaciones y progreso | Alta | `notas_bimestrales` |
| CUS-10 | Monitorear alertas de tutorados | Media | `notificaciones` |
| CUS-11 | Restringir visualización de datos (privacidad) | Alta | `notas.visible_para_alumno` |
| CUS-12 | Consultar estado financiero | Media | `pagos` |
| CUS-13 | Enviar notificaciones automáticas | Media | `notificaciones` |
| CUS-14 | Gestionar matrícula y usuarios | Alta | `alumnos`, `padres`, `alumno_padre`, `matriculas` |
| CUS-15 | Gestionar periodos y cursos | Alta | `periodos_academicos`, `cursos`, `asignaciones` |
| CUS-16 | Generar reportes académicos | Alta | Export Excel (pendiente) |
| CUS-17 | Registrar bitácora psicológica | Baja | `bitacora_psicologica` |

---

## 9. Reglas para agentes de IA que trabajen en este repo

1. Antes de generar código, revisar el estado real del repo (migraciones,
   modelos, `php artisan route:list`) — no asumir nada que se pueda
   verificar directamente.
2. No crear tablas, columnas o rutas nuevas que no estén en este
   documento sin señalarlo explícitamente y pedir confirmación.
3. Trabajar una capa a la vez (migraciones → modelos → rutas/
   controladores → vistas) y un rol a la vez (Docente → Alumno → Padre →
   Back-office), nunca todo junto.
4. Para tareas grandes, primero listar el plan de archivos a crear y
   esperar confirmación antes de generar el código completo.
5. Actualizar la sección 5 (Estado actual) de este archivo al terminar
   cada paso.
