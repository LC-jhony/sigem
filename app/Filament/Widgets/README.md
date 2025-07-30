# Widgets del Dashboard

Este directorio contiene los widgets personalizados para el dashboard de Filament.

## Widgets Disponibles

### 1. StatsOverview
- **Descripción**: Muestra estadísticas generales del sistema
- **Información mostrada**:
  - Total de minas registradas
  - Vehículos activos
  - Conductores activos
  - Mantenimientos pendientes

### 2. VehicleStatusChart
- **Descripción**: Gráfico de dona que muestra la distribución de vehículos por estado
- **Tipo**: Gráfico de dona (doughnut)
- **Colores**: Verde para activos, rojo para inactivos

### 3. LatestMaintenanceTable
- **Descripción**: Tabla que muestra los últimos 5 mantenimientos registrados
- **Información mostrada**:
  - Vehículo
  - Descripción del mantenimiento
  - Fecha
  - Estado (con colores)

### 4. TopMinesWidget
- **Descripción**: Muestra las minas más activas basado en asignaciones de conductores
- **Información mostrada**:
  - Nombre de la mina
  - Número de conductores asignados activamente

### 5. MaintenanceTrendChart
- **Descripción**: Gráfico de líneas que muestra la tendencia de mantenimientos por mes
- **Período**: Últimos 6 meses
- **Tipo**: Gráfico de líneas con área rellena

### 6. TopDriversWidget
- **Descripción**: Tabla que muestra los conductores más activos
- **Información mostrada**:
  - Nombre del conductor
  - Número de asignaciones activas
  - Número de licencia
  - Estado

### 7. AlertsWidget
- **Descripción**: Widget de alertas que muestra situaciones que requieren atención
- **Alertas incluidas**:
  - Vehículos sin mantenimiento reciente (3 meses)
  - Mantenimientos vencidos
  - Documentos próximos a vencer (7 días)

### 8. RecentActivityWidget
- **Descripción**: Tabla que muestra las actividades más recientes del sistema
- **Información mostrada**:
  - Vehículo involucrado
  - Descripción de la actividad
  - Fecha y hora
  - Estado

## Configuración

Los widgets están registrados en `app/Providers/Filament/AdminPanelProvider.php` y se cargan automáticamente en el dashboard.

## Personalización

Para personalizar los widgets:

1. Modifica los archivos en `app/Filament/Widgets/`
2. Ajusta las consultas según tus necesidades
3. Cambia los colores y estilos según tu tema
4. Agrega nuevos widgets siguiendo la estructura existente

## Dependencias

Los widgets utilizan los siguientes modelos:
- `App\Models\Mine`
- `App\Models\Vehicle`
- `App\Models\Driver`
- `App\Models\Maintenance`
- `App\Models\DriverMineAssigment`
- `App\Models\Document` 