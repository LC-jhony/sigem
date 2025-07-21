# Componente de Tabla Personalizado - Guía de Uso

## Descripción

El componente de tabla personalizado (`<x-table>`) ha sido actualizado para heredar completamente los estilos de **Filament PHP** y los **estilos personalizados del theme**. Esto garantiza una apariencia consistente con el resto de la aplicación.

## Características

- ✅ **Hereda estilos de Filament PHP** (`fi-ta-table`)
- ✅ **Aplica estilos personalizados del theme** (`es-table`)
- ✅ **Soporte para modo oscuro** (dark mode)
- ✅ **Diseño responsivo**
- ✅ **Efectos hover y transiciones**
- ✅ **Bordes redondeados y sombras** (shadow-sm, ring)
- ✅ **Colores consistentes** con el sistema de diseño de Filament

## Componentes Disponibles

### 1. Tabla Principal (`<x-table>`)
```blade
<x-table>
    <x-slot name="header">
        <!-- Contenido del header -->
    </x-slot>
    
    <!-- Contenido del body -->
</x-table>
```

### 2. Encabezado de Tabla (`<x-th>`)
```blade
<x-th align="left|right|center" sortable="true|false">
    Título de Columna
</x-th>
```

### 3. Celda de Tabla (`<x-td>`)
```blade
<x-td align="left|right|center" type="body|header">
    Contenido de la celda
</x-td>
```

## Ejemplo de Uso Completo

```blade
<x-filament-panels::page>
    <x-container>
        <x-table>
            <x-slot name="header">
                <!-- Fila de encabezado principal -->
                <tr>
                    <x-th colspan="3">Información del Vehículo</x-th>
                    <x-th colspan="2">Documentación</x-th>
                </tr>
                
                <!-- Fila de sub-encabezados -->
                <tr>
                    <x-th>Código</x-th>
                    <x-th>Placa</x-th>
                    <x-th>Marca</x-th>
                    <x-th>SOAT</x-th>
                    <x-th>Revisión Técnica</x-th>
                </tr>
            </x-slot>
            
            <!-- Filas de datos -->
            <tr>
                <x-td>001</x-td>
                <x-td>ABC-123</x-td>
                <x-td>Toyota</x-td>
                <x-td>2024-12-31</x-td>
                <x-td>2024-06-30</x-td>
            </tr>
            
            <tr>
                <x-td>002</x-td>
                <x-td>DEF-456</x-td>
                <x-td>Honda</x-td>
                <x-td>2024-11-15</x-td>
                <x-td>2024-05-20</x-td>
            </tr>
        </x-table>
    </x-container>
</x-filament-panels::page>
```

## Estilos Aplicados

### Tabla Principal
- **Contenedor**: `es-table__header-ctn` con bordes redondeados y sombra
- **Tabla**: `fi-ta-table es-table` con estilos combinados de Filament y theme personalizado
- **Fondo**: Blanco en modo claro, gris oscuro en modo oscuro
- **Bordes**: Ring sutil con colores adaptativos

### Encabezados (`<x-th>`)
- **Fondo**: Gris claro en modo claro, blanco/5 en modo oscuro
- **Texto**: Semibold, color adaptativo
- **Padding**: `px-3 py-3`
- **Hover**: Efecto hover opcional para columnas ordenables

### Celdas (`<x-td>`)
- **Padding**: `px-3 py-4` para celdas del body
- **Texto**: Color adaptativo según el modo
- **Hover**: Efecto hover en las filas
- **Responsive**: Padding ajustado en dispositivos móviles

## Integración con Filament

El componente está diseñado para funcionar perfectamente dentro del ecosistema de Filament PHP:

1. **Usa las mismas clases CSS** que las tablas nativas de Filament
2. **Respeta el sistema de colores** definido en el AdminPanelProvider
3. **Sigue las convenciones de naming** de Filament (`fi-ta-*`)
4. **Es compatible con el modo oscuro** de Filament

## Archivos Relacionados

- `resources/views/components/table.blade.php` - Componente principal
- `resources/views/components/th.blade.php` - Componente de encabezado
- `resources/views/components/td.blade.php` - Componente de celda
- `resources/css/filament/admin/custom-data-table.css` - Estilos CSS
- `resources/css/filament/admin/theme.css` - Theme principal

## Notas Importantes

- Los estilos se compilan automáticamente con Vite
- El componente es totalmente responsivo
- Compatible con todas las funcionalidades de Filament PHP
- Mantiene la accesibilidad y usabilidad estándar
