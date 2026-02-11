# 🌐 Laboratorio 1.1 - Aislamiento y Conectividad en VPC

## Objetivo

En este laboratorio aprenderás a configurar una red virtual privada (VPC) en AWS, crear subredes públicas y privadas, y establecer conectividad a Internet mediante un Internet Gateway. El instructor creará la VPC e Internet Gateway en vivo como demostración, y luego tú configurarás tus propias subredes y enrutamiento.

**Duración estimada:** 50 minutos

## Paso 1: Verificación de Región

**⏱️ Tiempo estimado: 2 minutos**

Antes de comenzar, es fundamental verificar que estás trabajando en la región correcta de AWS.

1. En la esquina superior derecha de la Consola de AWS, localiza el selector de región
2. Verifica que la región mostrada coincide con la región designada por el instructor (ejemplo: **US East (N. Virginia) us-east-1**)
3. Si la región es incorrecta, haz clic en el selector y elige la región correcta
4. **IMPORTANTE:** Todos los recursos deben crearse en la misma región

## Demostración del Instructor (En Vivo)

**⏱️ Tiempo estimado: 10 minutos**

El instructor realizará los siguientes pasos en vivo. **NO debes realizar estas acciones**, solo observa y toma notas.

### Creación de la VPC

El instructor creará una VPC compartida que todos los participantes utilizarán:

1. En la barra de búsqueda global de AWS (parte superior), escribe **VPC** y selecciona el servicio
2. En el panel de navegación de la izquierda, haz clic en **Sus VPC**
3. Haz clic en el botón naranja **Crear VPC** en la esquina superior derecha
4. Configuración:
   - **Recursos que se crearán:** Solo VPC
   - **Etiqueta de nombre:** `Lab-VPC`
   - **Bloque de CIDR IPv4:** `10.0.0.0/16`
   - **Bloque de CIDR IPv6:** Sin bloque de CIDR IPv6
   - **Tenencia:** Predeterminada
5. Haz clic en **Crear VPC**

### Habilitación de DNS en la VPC

El instructor habilitará las opciones de DNS necesarias:

1. Selecciona la VPC recién creada (`Lab-VPC`)
2. Haz clic en el botón **Acciones** en la parte superior
3. Selecciona **Editar configuración de DNS**
4. Marca las siguientes casillas:
   - ✅ **Habilitar nombres de host DNS**
   - ✅ **Habilitar resolución DNS**
5. Haz clic en **Guardar**

### Creación del Internet Gateway

El instructor creará un Internet Gateway para proporcionar conectividad a Internet:

1. En el panel de navegación de la izquierda, haz clic en **Puertas de enlace de Internet**
2. Haz clic en el botón naranja **Crear puerta de enlace de Internet**
3. Configuración:
   - **Etiqueta de nombre:** `Lab-IGW`
4. Haz clic en **Crear puerta de enlace de Internet**

### Asociación del Internet Gateway a la VPC

El instructor asociará el IGW a la VPC:

1. Con el Internet Gateway `Lab-IGW` seleccionado, haz clic en el botón **Acciones**
2. Selecciona **Asociar a una VPC**
3. En el campo **VPC disponibles**, selecciona `Lab-VPC`
4. Haz clic en **Asociar puerta de enlace de Internet**

**✅ Verificación del Instructor:** El estado del IGW debe mostrar **Attached** (Asociado)

---

## Actividades del Participante

**⏱️ Tiempo estimado: 35 minutos**

Ahora es tu turno de configurar tus propias subredes y enrutamiento dentro de la VPC compartida.

### Paso 2: Verificación de Recursos del Instructor

**⏱️ Tiempo estimado: 3 minutos**

Antes de continuar, verifica que los recursos compartidos fueron creados correctamente:

1. En el servicio VPC, ve a **Sus VPC** en el panel de navegación izquierdo
2. Busca la VPC con nombre `Lab-VPC`
3. Verifica que el **Bloque de CIDR IPv4** sea `10.0.0.0/16`
4. Ve a **Puertas de enlace de Internet** en el panel de navegación izquierdo
5. Busca el IGW con nombre `Lab-IGW`
6. Verifica que el **Estado** sea **Attached** y esté asociado a `Lab-VPC`

**✅ Checkpoint de Verificación:** Si no ves estos recursos o el estado es incorrecto, notifica al instructor inmediatamente.

### Paso 3: Creación de Subred Pública

**⏱️ Tiempo estimado: 5 minutos**

Ahora crearás tu propia subred pública con un bloque CIDR único.

**IMPORTANTE:** El instructor te asignará un número único (X). Usa este número para calcular tu CIDR:
- **Subred pública:** `10.0.{X*2}.0/24`
- **Ejemplo:** Si tu número es 5, tu CIDR será `10.0.10.0/24`

1. En el panel de navegación de la izquierda, haz clic en **Subredes**
2. Haz clic en el botón naranja **Crear subred**
3. Configuración:
   - **ID de VPC:** Selecciona `Lab-VPC`
   - Haz clic en **Agregar nueva subred**
   - **Nombre de subred:** `subnet-publica-{tu-nombre}` (reemplaza `{tu-nombre}` con tu identificador en minúsculas, ejemplo: `subnet-publica-luis`)
   - **Zona de disponibilidad:** Selecciona la primera zona disponible terminada en **-a** (ejemplo: `us-east-1a`)
   - **Bloque de CIDR IPv4:** `10.0.{X*2}.0/24` (usa tu número asignado)
4. Haz clic en **Crear subred**

### Paso 4: Etiquetado de Subred Pública

**⏱️ Tiempo estimado: 2 minutos**

Agrega las etiquetas obligatorias a tu subred pública:

1. Selecciona la subred que acabas de crear (`subnet-publica-{tu-nombre}`)
2. En la parte inferior, haz clic en la pestaña **Etiquetas**
3. Haz clic en **Administrar etiquetas**
4. Haz clic en **Agregar nueva etiqueta** y agrega las siguientes:
   - **Clave:** `Owner` | **Valor:** `{tu-nombre-completo}` (ejemplo: `Luis García`)
   - **Clave:** `Project` | **Valor:** `Workshop-BCRP`
5. Haz clic en **Guardar**

### Paso 5: Habilitar Asignación Automática de IPv4 Pública

**⏱️ Tiempo estimado: 2 minutos**

Configura la subred pública para que asigne automáticamente direcciones IP públicas:

1. Con la subred pública seleccionada, haz clic en el botón **Acciones**
2. Selecciona **Editar configuración de subred**
3. En la sección **Configuración de dirección IP automática**, marca la casilla:
   - ✅ **Habilitar la asignación automática de direcciones IPv4 públicas**
4. Haz clic en **Guardar**

**✅ Checkpoint de Verificación:** En la pestaña **Detalles** de tu subred, verifica que **Asignación automática de IP pública** muestre **Sí**

### Paso 6: Creación de Subred Privada

**⏱️ Tiempo estimado: 5 minutos**

Ahora crearás tu subred privada con un bloque CIDR diferente.

**IMPORTANTE:** Usa tu número asignado (X) para calcular el CIDR:
- **Subred privada:** `10.0.{X*2+1}.0/24`
- **Ejemplo:** Si tu número es 5, tu CIDR será `10.0.11.0/24`

1. En el panel de navegación de la izquierda, haz clic en **Subredes**
2. Haz clic en el botón naranja **Crear subred**
3. Configuración:
   - **ID de VPC:** Selecciona `Lab-VPC`
   - Haz clic en **Agregar nueva subred**
   - **Nombre de subred:** `subnet-privada-{tu-nombre}` (ejemplo: `subnet-privada-luis`)
   - **Zona de disponibilidad:** Selecciona la misma zona que usaste para la subred pública (terminada en **-a**)
   - **Bloque de CIDR IPv4:** `10.0.{X*2+1}.0/24` (usa tu número asignado)
4. Haz clic en **Crear subred**

### Paso 7: Etiquetado de Subred Privada

**⏱️ Tiempo estimado: 2 minutos**

Agrega las etiquetas obligatorias a tu subred privada:

1. Selecciona la subred que acabas de crear (`subnet-privada-{tu-nombre}`)
2. En la parte inferior, haz clic en la pestaña **Etiquetas**
3. Haz clic en **Administrar etiquetas**
4. Haz clic en **Agregar nueva etiqueta** y agrega las siguientes:
   - **Clave:** `Owner` | **Valor:** `{tu-nombre-completo}`
   - **Clave:** `Project` | **Valor:** `Workshop-BCRP`
5. Haz clic en **Guardar**

**✅ Checkpoint de Verificación:** Debes tener dos subredes creadas con tus nombres únicos y etiquetas correctas

### Paso 8: Creación de Tabla de Ruteo Personalizada

**⏱️ Tiempo estimado: 3 minutos**

Crearás una tabla de ruteo personalizada para tu subred pública:

1. En el panel de navegación de la izquierda, haz clic en **Tablas de enrutamiento**
2. Haz clic en el botón naranja **Crear tabla de enrutamiento**
3. Configuración:
   - **Nombre:** `rtb-publica-{tu-nombre}` (ejemplo: `rtb-publica-luis`)
   - **VPC:** Selecciona `Lab-VPC`
4. Haz clic en **Crear tabla de enrutamiento**

### Paso 9: Etiquetado de Tabla de Ruteo

**⏱️ Tiempo estimado: 2 minutos**

Agrega las etiquetas obligatorias a tu tabla de ruteo:

1. Selecciona la tabla de ruteo que acabas de crear (`rtb-publica-{tu-nombre}`)
2. En la parte inferior, haz clic en la pestaña **Etiquetas**
3. Haz clic en **Administrar etiquetas**
4. Haz clic en **Agregar nueva etiqueta** y agrega las siguientes:
   - **Clave:** `Owner` | **Valor:** `{tu-nombre-completo}`
   - **Clave:** `Project` | **Valor:** `Workshop-BCRP`
5. Haz clic en **Guardar**

### Paso 10: Configuración de Ruta hacia Internet

**⏱️ Tiempo estimado: 3 minutos**

Configura una ruta que dirija todo el tráfico de Internet hacia el Internet Gateway:

1. Con tu tabla de ruteo seleccionada (`rtb-publica-{tu-nombre}`), haz clic en la pestaña **Rutas** en la parte inferior
2. Haz clic en el botón **Editar rutas**
3. Haz clic en **Agregar ruta**
4. Configuración de la nueva ruta:
   - **Destino:** `0.0.0.0/0`
   - **Objetivo:** Selecciona **Internet Gateway** y luego elige `Lab-IGW`
5. Haz clic en **Guardar cambios**

**✅ Checkpoint de Verificación:** En la pestaña **Rutas**, debes ver dos rutas:
- Una ruta local para `10.0.0.0/16` (creada automáticamente)
- Una ruta para `0.0.0.0/0` apuntando a `Lab-IGW`

### Paso 11: Asociación de Subred Pública a Tabla de Ruteo

**⏱️ Tiempo estimado: 3 minutos**

Asocia tu subred pública a la tabla de ruteo personalizada:

1. Con tu tabla de ruteo seleccionada (`rtb-publica-{tu-nombre}`), haz clic en la pestaña **Asociaciones de subred** en la parte inferior
2. En la sección **Subredes explícitas**, haz clic en **Editar asociaciones de subred**
3. Marca la casilla de tu subred pública (`subnet-publica-{tu-nombre}`)
4. **IMPORTANTE:** NO marques la subred privada
5. Haz clic en **Guardar asociaciones**

**✅ Checkpoint de Verificación:** En la pestaña **Asociaciones de subred**, tu subred pública debe aparecer en la sección **Subredes explícitas**

### Paso 12: Verificación Final de Configuración

**⏱️ Tiempo estimado: 3 minutos**

Verifica que toda tu configuración de red está correcta:

1. Ve a **Subredes** en el panel de navegación izquierdo
2. Localiza tu subred pública (`subnet-publica-{tu-nombre}`)
3. Verifica en la pestaña **Detalles**:
   - **VPC:** `Lab-VPC`
   - **Bloque de CIDR IPv4:** `10.0.{X*2}.0/24`
   - **Asignación automática de IP pública:** Sí
   - **Tabla de enrutamiento:** `rtb-publica-{tu-nombre}`
4. Localiza tu subred privada (`subnet-privada-{tu-nombre}`)
5. Verifica en la pestaña **Detalles**:
   - **VPC:** `Lab-VPC`
   - **Bloque de CIDR IPv4:** `10.0.{X*2+1}.0/24`
   - **Asignación automática de IP pública:** No
   - **Tabla de enrutamiento:** Debe mostrar la tabla de ruteo principal (no tu tabla personalizada)

**✅ Checkpoint Final:** Si todos los valores son correctos, has completado exitosamente el laboratorio 1.1

---

## Resumen de Recursos Creados

Al finalizar este laboratorio, habrás creado los siguientes recursos:

| Recurso | Nombre | Descripción |
|---------|--------|-------------|
| Subred Pública | `subnet-publica-{tu-nombre}` | Subred con acceso a Internet |
| Subred Privada | `subnet-privada-{tu-nombre}` | Subred sin acceso directo a Internet |
| Tabla de Ruteo | `rtb-publica-{tu-nombre}` | Tabla de ruteo con ruta a Internet Gateway |

**Recursos compartidos del instructor:**
- VPC: `Lab-VPC` (10.0.0.0/16)
- Internet Gateway: `Lab-IGW`

---

## Solución de Problemas

Si encuentras problemas durante este laboratorio, consulta la [Guía de Troubleshooting del Día 1](../TROUBLESHOOTING.md) para soluciones a errores comunes.

Los problemas más frecuentes en este laboratorio incluyen:
- Error "CIDR overlaps" al crear subredes
- Error "Route already exists" al configurar enrutamiento
- No poder ver los recursos creados por el instructor
- Problemas con asociaciones de subredes a tablas de ruteo
- Errores de permisos

Para soluciones detalladas, consulta la sección **Lab 1.1 - VPC y Subredes** en la [Guía de Troubleshooting](../TROUBLESHOOTING.md).

---

## Ciclo de Vida de Recursos

**IMPORTANTE:** Los recursos creados en este laboratorio deben mantenerse activos para el Día 2 del workshop.

### ✅ Recursos a MANTENER (NO eliminar):
- Subred pública (`subnet-publica-{tu-nombre}`)
- Subred privada (`subnet-privada-{tu-nombre}`)
- Tabla de ruteo personalizada (`rtb-publica-{tu-nombre}`)

### ⚠️ Recursos compartidos (NO modificar ni eliminar):
- VPC (`Lab-VPC`)
- Internet Gateway (`Lab-IGW`)

**Estos recursos son necesarios para los laboratorios 1.2 y 1.3. NO los elimines al finalizar el día.**

---

## Próximos Pasos

Una vez completado este laboratorio, estarás listo para:
- **Laboratorio 1.2:** Desplegar una instancia EC2 con servidor web en tu subred pública
- **Laboratorio 1.3:** Configurar almacenamiento EBS y hosting estático en S3

[← Volver al Día 1](../README.md) | [Siguiente: Lab 1.2 - EC2 →](../lab-1.2-ec2/README.md)
