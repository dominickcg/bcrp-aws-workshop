# 🗄️ Laboratorio 2.2 - Base de Datos en Alta Disponibilidad (RDS Multi-AZ)

## Índice

- [Descripción General](#descripción-general)
- [Objetivos de Aprendizaje](#objetivos-de-aprendizaje)
- [Duración Estimada](#duración-estimada)
- [Prerequisitos](#prerequisitos)
- [Instrucciones](#instrucciones)
  - [Paso 1: Verificación de Región](#paso-1-verificación-de-región)
  - [Paso 2: Crear Grupo de Subredes de RDS](#paso-2-crear-grupo-de-subredes-de-rds)
  - [Paso 3: Agregar Subred Privada del Participante](#paso-3-agregar-subred-privada-del-participante)
  - [Paso 4: Agregar Subred Privada de Respaldo](#paso-4-agregar-subred-privada-de-respaldo)
  - [Paso 5: Crear Security Group para Instancias Web](#paso-5-crear-security-group-para-instancias-web)
  - [Paso 6: Crear Security Group para RDS](#paso-6-crear-security-group-para-rds)
  - [Paso 7: Configurar Regla de Entrada MySQL](#paso-7-configurar-regla-de-entrada-mysql)
  - [Paso 8: Crear Instancia RDS MySQL](#paso-8-crear-instancia-rds-mysql)
  - [Paso 9: Configurar Multi-AZ y Conectividad](#paso-9-configurar-multi-az-y-conectividad)
  - [Paso 10: Anotar Credenciales y Endpoint](#paso-10-anotar-credenciales-y-endpoint)
- [Resumen del Laboratorio](#resumen-del-laboratorio)
- [Solución de Problemas](#solución-de-problemas)
- [Gestión del Ciclo de Vida de Recursos](#gestión-del-ciclo-de-vida-de-recursos)

## Descripción General

En este laboratorio aprenderás a crear una base de datos relacional en Amazon RDS (Relational Database Service) con configuración Multi-AZ para alta disponibilidad. RDS es un servicio administrado que facilita la configuración, operación y escalado de bases de datos relacionales en la nube.

**¿Qué es Multi-AZ?** Multi-AZ (Multi-Availability Zone) es una característica de alta disponibilidad que mantiene automáticamente una réplica sincrónica de tu base de datos en una zona de disponibilidad diferente. Si la instancia principal falla, RDS realiza un failover automático a la réplica en 1-2 minutos, minimizando el tiempo de inactividad.

## Objetivos de Aprendizaje

Al completar este laboratorio, serás capaz de:

- Crear y configurar un Grupo de subredes de RDS para despliegue Multi-AZ
- Configurar Security Groups para controlar el acceso a la base de datos
- Desplegar una instancia RDS MySQL con alta disponibilidad Multi-AZ
- Comprender los conceptos de alta disponibilidad y failover automático en la capa de datos

## Duración Estimada

50 minutos

## Prerequisitos

Antes de comenzar este laboratorio, debes tener:

- **Lab 1.1 del Día 1 completado**: Necesitas la subred privada creada en el laboratorio de VPC
- **Acceso a la consola de AWS**: Con permisos para crear recursos de RDS, VPC y EC2
- **Información de la subred privada**: Anota el ID de tu subred privada del Día 1

⚠️ **Recursos Compartidos del Instructor**: El instructor proporcionará una subred privada de respaldo en una zona de disponibilidad diferente. NO modifiques recursos que no tengan tu nombre de participante.

## Instrucciones

### Paso 1: Verificación de Región

1. Verifique que está trabajando en la región correcta:
   - En la esquina superior derecha de la consola de AWS
   - Confirme que dice la región estipulada por el instructor
   - Si no es correcta, haga clic y seleccione la región indicada

### Paso 2: Crear Grupo de Subredes de RDS

Un Grupo de subredes de RDS define en qué subredes puede desplegarse tu base de datos. Para Multi-AZ, necesitas al menos dos subredes en diferentes zonas de disponibilidad.

1. En la barra de búsqueda global (parte superior), escriba **RDS** y haga clic en el servicio
2. En el panel de navegación de la izquierda, haga clic en **Grupos de subredes**
3. Haga clic en el botón naranja **Crear grupo de subredes de BD**
4. Configure los siguientes parámetros:
   - **Nombre**: `rds-subnet-group-{nombre-participante}`
   - **Descripción**: `Grupo de subredes para RDS Multi-AZ`
   - **VPC**: Seleccione la VPC compartida del workshop (proporcionada por el instructor)

⚠️ **Importante**: NO cree una nueva VPC. Utilice la VPC compartida existente del workshop.

### Paso 3: Agregar Subred Privada del Participante

1. En la sección **Agregar subredes**, seleccione la primera zona de disponibilidad donde creó su subred privada en el Día 1
2. En la lista de subredes disponibles, seleccione su subred privada:
   - Busque la subred con el nombre que contiene su nombre de participante
   - Ejemplo: `subnet-private-{nombre-participante}`
3. Haga clic en **Agregar subred**

**✓ Verificación**: Confirme que aparece una subred en la tabla de subredes seleccionadas con su nombre de participante.

### Paso 4: Agregar Subred Privada de Respaldo

Para habilitar Multi-AZ, necesitas una segunda subred en una zona de disponibilidad diferente.

1. En la sección **Agregar subredes**, seleccione una zona de disponibilidad DIFERENTE a la del Paso 3
2. En la lista de subredes disponibles, seleccione la subred privada de respaldo proporcionada por el instructor:
   - Busque una subred marcada como "Recurso compartido - Instructor"
   - O consulte con el instructor el nombre de la subred de respaldo
3. Haga clic en **Agregar subred**
4. Haga clic en el botón naranja **Crear** al final de la página

**✓ Verificación**: Confirme que el Grupo de subredes muestra:
- Estado: **Completo**
- Número de subredes: **2**
- Zonas de disponibilidad: **2 zonas diferentes**

⏱️ **Nota**: La creación del Grupo de subredes es instantánea.

### Paso 5: Crear Security Group para Instancias Web

Antes de crear el Security Group para RDS, crearemos un Security Group para las instancias web que se conectarán a la base de datos en el Lab 2.3.

1. En la barra de búsqueda global, escriba **EC2** y haga clic en el servicio
2. En el panel de navegación de la izquierda, desplácese hasta **Red y seguridad** y haga clic en **Grupos de seguridad**
3. Haga clic en el botón naranja **Crear grupo de seguridad**
4. Configure los siguientes parámetros:
   - **Nombre del grupo de seguridad**: `sg-web-{nombre-participante}`
   - **Descripción**: `Security Group para instancias web`
   - **VPC**: Seleccione la VPC compartida del workshop
5. En la sección **Reglas de entrada**, haga clic en **Agregar regla**:
   - **Tipo**: HTTP
   - **Origen**: Anywhere-IPv4 (0.0.0.0/0)
6. Haga clic en el botón naranja **Crear grupo de seguridad**

**✓ Verificación**: Confirme que el Security Group `sg-web-{nombre-participante}` aparece en la lista con una regla de entrada para HTTP (puerto 80).

### Paso 6: Crear Security Group para RDS

Ahora crearemos el Security Group que controlará el acceso a la base de datos RDS.

1. En la página de Grupos de seguridad, haga clic en el botón naranja **Crear grupo de seguridad**
2. Configure los siguientes parámetros:
   - **Nombre del grupo de seguridad**: `sg-rds-{nombre-participante}`
   - **Descripción**: `Security Group para RDS MySQL`
   - **VPC**: Seleccione la VPC compartida del workshop
3. Por ahora, NO agregue reglas de entrada (las configuraremos en el siguiente paso)
4. Haga clic en el botón naranja **Crear grupo de seguridad**

**✓ Verificación**: Confirme que el Security Group `sg-rds-{nombre-participante}` aparece en la lista.

### Paso 7: Configurar Regla de Entrada MySQL

Ahora configuraremos el Security Group de RDS para permitir conexiones MySQL solo desde las instancias web.

1. En la lista de Grupos de seguridad, seleccione `sg-rds-{nombre-participante}` (haga clic en el nombre)
2. En la parte inferior, haga clic en la pestaña **Reglas de entrada**
3. Haga clic en el botón **Editar reglas de entrada**
4. Haga clic en **Agregar regla** y configure:
   - **Tipo**: MySQL/Aurora (puerto 3306 se selecciona automáticamente)
   - **Origen**: Personalizado
   - En el campo de búsqueda, escriba `sg-web-{nombre-participante}` y seleccione su Security Group de instancias web
5. Haga clic en el botón naranja **Guardar reglas**

**✓ Verificación**: Confirme que la regla de entrada muestra:
- **Tipo**: MySQL/Aurora
- **Puerto**: 3306
- **Origen**: sg-web-{nombre-participante}

**¿Por qué usar un Security Group como origen?** Al especificar un Security Group como origen, cualquier instancia EC2 que tenga asignado el Security Group `sg-web-{nombre-participante}` podrá conectarse a la base de datos. Esto es más seguro y flexible que especificar direcciones IP individuales.

### Paso 8: Crear Instancia RDS MySQL

Ahora crearemos la instancia de base de datos RDS con MySQL.

1. En la barra de búsqueda global, escriba **RDS** y haga clic en el servicio
2. En el panel de navegación de la izquierda, haga clic en **Bases de datos**
3. Haga clic en el botón naranja **Crear base de datos**
4. Configure los siguientes parámetros:

**Opciones del motor**:
   - **Método de creación**: Creación estándar
   - **Tipo de motor**: MySQL
   - **Versión**: MySQL 8.0.35 (o la versión más reciente disponible)

**Plantillas**:
   - Seleccione **Desarrollo/Pruebas**

⚠️ **Importante**: La plantilla "Desarrollo/Pruebas" permite habilitar Multi-AZ. NO seleccione "Capa gratuita" ya que no soporta Multi-AZ.

**Configuración**:
   - **Identificador de instancia de base de datos**: `rds-mysql-{nombre-participante}`
   - **Nombre de usuario maestro**: `admin`
   - **Contraseña maestra**: Cree una contraseña segura (mínimo 8 caracteres)
   - **Confirmar contraseña**: Repita la contraseña

⚠️ **CRÍTICO**: Anote estas credenciales en un lugar seguro. Las necesitará para el Laboratorio 2.3:
- Usuario: `admin`
- Contraseña: `[su-contraseña]`

**Configuración de la instancia**:
   - **Clases con ráfagas (incluye clases t)**: Seleccionado
   - **Clase de instancia de base de datos**: db.t3.micro

**Almacenamiento**:
   - **Tipo de almacenamiento**: SSD de uso general (gp3)
   - **Almacenamiento asignado**: 20 GiB
   - **Desmarque** la opción "Habilitar escalado automático del almacenamiento"

⏱️ **Nota**: La creación de la instancia RDS puede tardar 10-15 minutos. Puede continuar con el Paso 9 mientras se aprovisiona.

### Paso 9: Configurar Multi-AZ y Conectividad

Continúe configurando los parámetros de la instancia RDS:

**Disponibilidad y durabilidad**:
   - **Implementación Multi-AZ**: Seleccione **Crear una instancia de base de datos en espera (recomendado para uso en producción)**

**¿Qué hace esta opción?** Al habilitar Multi-AZ, RDS crea automáticamente una réplica sincrónica de tu base de datos en una zona de disponibilidad diferente. Si la instancia principal falla, RDS realiza un failover automático a la réplica en 1-2 minutos, asegurando alta disponibilidad.

**Conectividad**:
   - **Recurso de computación**: No conectarse a un recurso de computación de EC2
   - **Nube privada virtual (VPC)**: Seleccione la VPC compartida del workshop
   - **Grupo de subredes de base de datos**: Seleccione `rds-subnet-group-{nombre-participante}`
   - **Acceso público**: No
   - **Grupo de seguridad de VPC**: Seleccione **Elegir existente**
   - En la lista, seleccione `sg-rds-{nombre-participante}` y elimine el grupo "default" si aparece

**Configuración adicional**:
   - Expanda la sección **Configuración adicional**
   - **Nombre de base de datos inicial**: `workshopdb`
   - **Desmarque** la opción "Habilitar copias de seguridad automatizadas" (para simplificar el workshop)
   - **Desmarque** la opción "Habilitar cifrado" (opcional para el workshop)

5. Desplácese hasta el final de la página y haga clic en el botón naranja **Crear base de datos**

**✓ Verificación**: Confirme que aparece el mensaje "Creando base de datos rds-mysql-{nombre-participante}".

⏱️ **Tiempo de espera**: La instancia RDS tardará aproximadamente 10-15 minutos en estar disponible. El estado cambiará de "Creando" a "Respaldo" y finalmente a "Disponible".

**Mientras espera**, puede:
- Revisar la configuración de los Security Groups creados
- Leer sobre RDS Multi-AZ en la documentación de AWS
- Prepararse para el Laboratorio 2.3

⚠️ **NO proceda al Paso 10** hasta que el estado de la base de datos sea **Disponible** (indicador verde).

### Paso 10: Anotar Credenciales y Endpoint

Una vez que la instancia RDS esté en estado "Disponible", necesitas anotar el endpoint de conexión.

1. En la consola de RDS, haga clic en el nombre de su base de datos: `rds-mysql-{nombre-participante}`
2. En la sección **Conectividad y seguridad**, localice el **Punto de enlace**
3. Copie el endpoint completo (ejemplo: `rds-mysql-luis.abc123.us-east-1.rds.amazonaws.com`)

⚠️ **CRÍTICO**: Anote la siguiente información para el Laboratorio 2.3:

```
Endpoint: [su-endpoint-rds]
Usuario: admin
Contraseña: [su-contraseña]
Nombre de base de datos: workshopdb
```

**✓ Verificación**: Confirme que su instancia RDS muestra:
- **Estado**: Disponible (indicador verde)
- **Multi-AZ**: Sí
- **Punto de enlace**: Visible y copiado
- **Grupo de subredes**: rds-subnet-group-{nombre-participante}
- **Grupos de seguridad de VPC**: sg-rds-{nombre-participante}

## Resumen del Laboratorio

¡Felicitaciones! Has completado el Laboratorio 2.2. En este laboratorio has:

- Creado un Grupo de subredes de RDS con subredes en dos zonas de disponibilidad diferentes
- Configurado Security Groups para controlar el acceso a la base de datos desde instancias web
- Desplegado una instancia RDS MySQL con configuración Multi-AZ para alta disponibilidad
- Comprendido cómo Multi-AZ proporciona failover automático en caso de fallo de zona de disponibilidad

**Conceptos clave aprendidos**:
- **RDS Multi-AZ**: Proporciona alta disponibilidad mediante replicación sincrónica en múltiples zonas
- **Grupos de subredes**: Definen la topología de red para el despliegue de bases de datos
- **Security Groups**: Actúan como firewalls virtuales para controlar el tráfico de red
- **Failover automático**: RDS detecta fallos y cambia automáticamente a la réplica en espera

⚠️ **Importante**: NO elimine los recursos creados en este laboratorio. Los utilizaremos en el Laboratorio 2.3 para desplegar una aplicación web que se conecta a esta base de datos.

## Solución de Problemas

Si encuentra dificultades durante este laboratorio, consulte la [Guía de Solución de Problemas](../TROUBLESHOOTING.md) que contiene soluciones a errores comunes.

**Errores comunes específicos de este laboratorio**:

- **Error al crear Grupo de subredes**: Verifique que las subredes estén en zonas de disponibilidad diferentes
- **RDS no inicia (estado "failed")**: Revise los eventos de la instancia y verifique que el Grupo de subredes tenga al menos 2 subredes
- **No se puede seleccionar Multi-AZ**: Asegúrese de haber seleccionado la plantilla "Desarrollo/Pruebas" y no "Capa gratuita"

**Errores que requieren asistencia del instructor**:
- Errores de permisos IAM al crear recursos de RDS
- Errores de límites de cuota de AWS
- No puede encontrar la subred privada de respaldo del instructor

## Gestión del Ciclo de Vida de Recursos

⚠️ **NO elimine los recursos de este laboratorio**. Los necesitará para el Laboratorio 2.3.

**Recursos creados en este laboratorio**:
- Grupo de subredes de RDS: `rds-subnet-group-{nombre-participante}`
- Security Group web: `sg-web-{nombre-participante}`
- Security Group RDS: `sg-rds-{nombre-participante}`
- Instancia RDS: `rds-mysql-{nombre-participante}`

**Limpieza opcional**: Si desea eliminar estos recursos al finalizar el workshop completo, consulte la [Guía de Limpieza](../limpieza/README.md) que proporciona instrucciones detalladas en el orden correcto de eliminación.

---

**Siguiente paso**: Continúe con el [Laboratorio 2.3 - Elasticidad y Alta Disponibilidad Integrada](../lab-2.3-ha-elb-asg/README.md) donde desplegará una aplicación web escalable que se conecta a esta base de datos.
