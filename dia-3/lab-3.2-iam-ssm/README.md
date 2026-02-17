# 👤 Laboratorio 3.2 - Gestión de Identidades y Acceso Seguro

## Índice

- [Descripción General](#descripción-general)
- [Objetivos de Aprendizaje](#objetivos-de-aprendizaje)
- [Duración Estimada](#duración-estimada)
- [Archivos de Soporte](#archivos-de-soporte)
- [Prerequisitos](#prerequisitos)
- [Instrucciones](#instrucciones)
  - [Paso 1: Verificación de Región](#paso-1-verificación-de-región)
  - [Paso 2: Crear Rol IAM](#paso-2-crear-rol-iam)
  - [Paso 3: Adjuntar Política Gestionada para Session Manager](#paso-3-adjuntar-política-gestionada-para-session-manager)
  - [Paso 4: Crear Política Inline para S3](#paso-4-crear-política-inline-para-s3)
  - [Paso 5: Modificar Launch Template del Auto Scaling Group](#paso-5-modificar-launch-template-del-auto-scaling-group)
  - [Paso 6: Forzar Lanzamiento de Nueva Instancia](#paso-6-forzar-lanzamiento-de-nueva-instancia)
  - [Paso 7: Conectarse por Session Manager](#paso-7-conectarse-por-session-manager)
  - [Paso 8: Validar Permisos S3](#paso-8-validar-permisos-s3)
- [Resumen del Laboratorio](#resumen-del-laboratorio)
- [Solución de Problemas](#solución-de-problemas)
- [Gestión del Ciclo de Vida de Recursos](#gestión-del-ciclo-de-vida-de-recursos)

## Descripción General

AWS Identity and Access Management (IAM) es el servicio que te permite controlar de forma segura el acceso a los recursos de AWS. Los roles de IAM son identidades que puedes asumir para obtener credenciales temporales, y son especialmente útiles para otorgar permisos a servicios de AWS como EC2.

AWS Systems Manager Session Manager proporciona acceso seguro a tus instancias EC2 sin necesidad de abrir puertos SSH (22) en los Security Groups, sin gestionar claves SSH, y con capacidad de auditoría completa de todas las sesiones.

En este laboratorio crearás un rol IAM con permisos de solo lectura a un bucket S3, adjuntarás este rol a las instancias EC2 de tu Auto Scaling Group, y utilizarás Session Manager para conectarte de forma segura y validar los permisos configurados.

## Objetivos de Aprendizaje

Al completar este laboratorio, serás capaz de:

- Crear roles IAM con políticas personalizadas siguiendo el principio de mínimo privilegio
- Comprender la diferencia entre políticas gestionadas y políticas inline
- Adjuntar roles IAM a instancias EC2 mediante perfiles de instancia
- Utilizar Session Manager para acceso seguro sin SSH
- Validar permisos IAM mediante AWS CLI desde una instancia EC2

## Duración Estimada

⏱️ **50 minutos**

## Archivos de Soporte

Este laboratorio incluye el siguiente archivo de soporte ubicado en esta carpeta:

- **`s3-readonly-policy.json`**: Plantilla de política IAM para permisos de solo lectura en S3

## Prerequisitos

Para completar este laboratorio necesitas:

- **Auto Scaling Group**: Creado en el Laboratorio 2.3 del Día 2
- **Launch Template**: Asociado al Auto Scaling Group del Lab 2.3
- **Bucket S3**: Creado en el Laboratorio 2.1 del Día 2 (para validar permisos)
- **Instancias EC2**: Al menos una instancia en ejecución en el Auto Scaling Group

## Instrucciones

### Paso 1: Verificación de Región

**⏱️ Tiempo estimado: 2 minutos**

1. En la esquina superior derecha de la Consola de AWS, localiza el selector de región
2. Verifica que la región mostrada coincide con la región designada por el instructor
3. Si la región es incorrecta, haz clic en el selector y elige la región correcta

⚠️ **Importante**: El rol IAM debe crearse en la misma región que tus instancias EC2.

### Paso 2: Crear Rol IAM

**⏱️ Tiempo estimado: 8 minutos**

1. En la barra de búsqueda global de AWS (parte superior), escribe **IAM**
2. Selecciona **IAM** de los resultados
3. En el panel de navegación de la izquierda, haz clic en **Roles**

4. Haz clic en el botón naranja **Crear rol** en la esquina superior derecha

5. En la página **Seleccionar entidad de confianza**:
   - **Tipo de entidad de confianza**: Selecciona **Servicio de AWS**
   - **Caso de uso**: Selecciona **EC2** de la lista
   - Haz clic en **Siguiente**

6. En la página **Agregar permisos**:
   - Por ahora, no selecciones ninguna política
   - Haz clic en **Siguiente**
   - (Agregaremos las políticas en los siguientes pasos)

7. En la página **Nombrar, revisar y crear**:
   - **Nombre del rol**: `role-ec2-s3readonly-{nombre-participante}`
   - **Descripción**: `Rol para EC2 con acceso de solo lectura a S3 y Session Manager`
   - Haz clic en **Crear rol**

**✓ Verificación**: Confirme que:
- El rol se creó exitosamente
- El nombre del rol incluye tu identificador de participante
- La entidad de confianza es el servicio EC2 (ec2.amazonaws.com)

**Nota educativa**: Los roles IAM proporcionan credenciales temporales que se renuevan automáticamente. A diferencia de las claves de acceso permanentes, los roles son más seguros porque las credenciales expiran y no necesitan almacenarse en la instancia.

### Paso 3: Adjuntar Política Gestionada para Session Manager

**⏱️ Tiempo estimado: 5 minutos**

Para que Session Manager funcione, la instancia EC2 necesita permisos para comunicarse con el servicio Systems Manager.

1. En la consola de IAM, localiza el rol que acabas de crear
2. Haz clic en el nombre del rol `role-ec2-s3readonly-{nombre-participante}`

3. En la pestaña **Permisos**, haz clic en el botón **Agregar permisos**
4. Selecciona **Adjuntar políticas**

5. En la barra de búsqueda de políticas, escribe: **AmazonSSMManagedInstanceCore**
6. Marca la casilla junto a la política **AmazonSSMManagedInstanceCore**
7. Haz clic en **Agregar permisos**

**✓ Verificación**: Confirme que:
- La política **AmazonSSMManagedInstanceCore** aparece en la lista de políticas de permisos
- El tipo de política es "Política administrada de AWS"

**Nota educativa**: Las políticas gestionadas son políticas predefinidas y mantenidas por AWS. La política AmazonSSMManagedInstanceCore otorga los permisos mínimos necesarios para que el agente de Systems Manager funcione correctamente.

### Paso 4: Crear Política Inline para S3

**⏱️ Tiempo estimado: 10 minutos**

Ahora crearemos una política personalizada que otorga permisos de solo lectura a tu bucket S3 específico, siguiendo el principio de mínimo privilegio.

1. En la misma página del rol, en la pestaña **Permisos**, haz clic en **Agregar permisos**
2. Selecciona **Crear política insertada**

3. Haz clic en la pestaña **JSON**

4. Abre el archivo `s3-readonly-policy.json` ubicado en esta carpeta del laboratorio

5. Copia el contenido del archivo JSON

6. Pega el contenido en el editor de políticas de la consola

7. **IMPORTANTE**: Reemplaza el placeholder `NOMBRE-DEL-BUCKET` con el nombre real de tu bucket S3 del Laboratorio 2.1
   - El nombre de tu bucket debe seguir el patrón: `s3-sitio-web-{nombre-participante}`
   - Debes reemplazar el placeholder en **dos lugares**:
     - En el Resource para `s3:ListBucket`: `arn:aws:s3:::s3-sitio-web-{nombre-participante}`
     - En el Resource para `s3:GetObject`: `arn:aws:s3:::s3-sitio-web-{nombre-participante}/*`

8. Ejemplo de política completada:
   ```json
   {
     "Version": "2012-10-17",
     "Statement": [
       {
         "Effect": "Allow",
         "Action": [
           "s3:ListBucket"
         ],
         "Resource": "arn:aws:s3:::s3-sitio-web-luis"
       },
       {
         "Effect": "Allow",
         "Action": [
           "s3:GetObject"
         ],
         "Resource": "arn:aws:s3:::s3-sitio-web-luis/*"
       }
     ]
   }
   ```

9. Haz clic en **Revisar política**

10. En la página de revisión:
    - **Nombre**: `S3ReadOnlyAccess`
    - Haz clic en **Crear política**

**✓ Verificación**: Confirme que:
- La política inline **S3ReadOnlyAccess** aparece en la lista de permisos del rol
- El tipo de política es "Política insertada"
- El ARN del bucket S3 en la política coincide con tu bucket del Lab 2.1

**Nota educativa**: Las políticas inline están directamente integradas en un único usuario, grupo o rol. A diferencia de las políticas gestionadas, no pueden reutilizarse. Son útiles cuando quieres asegurar que los permisos están estrictamente vinculados a una identidad específica. Esta política otorga solo dos permisos: listar el contenido del bucket (ListBucket) y leer objetos (GetObject), pero NO permite escribir, eliminar o modificar objetos.

### Paso 5: Modificar Launch Template del Auto Scaling Group

**⏱️ Tiempo estimado: 8 minutos**

Ahora modificaremos el Launch Template para que las nuevas instancias EC2 se lancen con el rol IAM que acabamos de crear.

1. En la barra de búsqueda global, escribe **EC2**
2. Selecciona **EC2** de los resultados

3. En el panel de navegación de la izquierda, desplázate hacia abajo y haz clic en **Grupos de Auto Scaling**

4. Selecciona tu Auto Scaling Group del Laboratorio 2.3 (debe contener tu nombre de participante)

5. En la pestaña **Detalles**, localiza la sección **Plantilla de lanzamiento**
6. Haz clic en el nombre de la plantilla de lanzamiento (enlace azul)

7. En la página de la plantilla de lanzamiento:
   - Selecciona la plantilla (marca la casilla)
   - Haz clic en el menú desplegable **Acciones**
   - Selecciona **Modificar plantilla (Crear nueva versión)**

8. En la página de modificación:
   - Desplázate hacia abajo hasta la sección **Configuración avanzada**
   - Expande la sección si está colapsada

9. En **Perfil de instancia de IAM**:
   - Haz clic en el menú desplegable
   - Selecciona el rol que creaste: `role-ec2-s3readonly-{nombre-participante}`

10. Desplázate hasta el final de la página
11. Haz clic en **Crear versión de plantilla**

12. En la página de confirmación:
    - Marca la casilla **Establecer esta versión como predeterminada**
    - Haz clic en **Ver plantilla de lanzamiento**

⏱️ **Nota**: La propagación de la nueva versión de la plantilla puede tardar 2-3 minutos.

**✓ Verificación**: Confirme que:
- Se creó una nueva versión de la plantilla de lanzamiento
- La nueva versión está marcada como "Predeterminada"
- El perfil de instancia de IAM aparece en los detalles de la versión

**Nota educativa**: Un perfil de instancia es un contenedor para un rol IAM que puedes usar para pasar información del rol a una instancia EC2 cuando se inicia. Cuando adjuntas un rol IAM a una instancia EC2, en realidad estás adjuntando un perfil de instancia que contiene ese rol.

### Paso 6: Forzar Lanzamiento de Nueva Instancia

**⏱️ Tiempo estimado: 7 minutos**

Las instancias EC2 existentes no se actualizan automáticamente con la nueva configuración del Launch Template. Necesitamos forzar el lanzamiento de una nueva instancia.

1. Regresa a la consola de EC2
2. En el panel de navegación de la izquierda, haz clic en **Instancias**

3. Identifica una de las instancias de tu Auto Scaling Group:
   - Busca instancias con el nombre que contiene tu identificador de participante
   - Verifica en la columna **Detalles** que pertenece a tu Auto Scaling Group

4. Selecciona una instancia (marca la casilla)
5. Haz clic en el menú desplegable **Estado de la instancia**
6. Selecciona **Terminar instancia**
7. Confirma la terminación

⏱️ **Nota**: El Auto Scaling Group detectará que hay menos instancias que la capacidad deseada y lanzará automáticamente una nueva instancia con el rol IAM. Este proceso puede tardar 3-5 minutos.

8. Espera a que el Auto Scaling Group lance la nueva instancia:
   - Actualiza la lista de instancias cada 30 segundos
   - Busca una nueva instancia con estado **En ejecución**
   - Verifica que las **Comprobaciones de estado** muestren "2/2 comprobaciones aprobadas"

9. Identifica la nueva instancia:
   - Selecciona la instancia recién lanzada
   - En la pestaña **Detalles**, desplázate hasta **Rol de IAM**
   - Verifica que aparece el rol `role-ec2-s3readonly-{nombre-participante}`

**✓ Verificación**: Confirme que:
- La instancia antigua fue terminada exitosamente
- Una nueva instancia se lanzó automáticamente
- La nueva instancia tiene el rol IAM adjunto (visible en la pestaña Detalles)
- El estado de la instancia es "En ejecución"
- Las comprobaciones de estado muestran "2/2 comprobaciones aprobadas"

**Nota educativa**: El Auto Scaling Group mantiene automáticamente la capacidad deseada. Cuando terminas una instancia manualmente, el ASG detecta que el número de instancias está por debajo de la capacidad deseada y lanza una nueva instancia usando la versión predeterminada del Launch Template.

### Paso 7: Conectarse por Session Manager

**⏱️ Tiempo estimado: 5 minutos**

Ahora utilizaremos Session Manager para conectarnos de forma segura a la instancia sin necesidad de SSH.

1. En la barra de búsqueda global, escribe **Systems Manager**
2. Selecciona **Systems Manager** de los resultados

3. En el panel de navegación de la izquierda, desplázate hacia abajo hasta la sección **Administración de nodos**
4. Haz clic en **Administrador de sesiones**

5. Haz clic en el botón naranja **Iniciar sesión**

6. En la página **Iniciar sesión**:
   - **Tipo de destino**: Selecciona **Instancia**
   - **Instancia**: Selecciona la nueva instancia EC2 que tiene el rol IAM adjunto
     - Busca la instancia por su ID o nombre
     - Verifica que sea la instancia recién lanzada con el rol IAM
   - Haz clic en **Iniciar sesión**

⏱️ **Nota**: La sesión puede tardar 10-15 segundos en establecerse.

7. Se abrirá una nueva pestaña o ventana con una terminal de línea de comandos

**✓ Verificación**: Confirme que:
- Se abrió una terminal de Session Manager
- El prompt muestra algo similar a: `sh-4.2$` o `[ssm-user@ip-xxx-xxx-xxx-xxx ~]$`
- Puedes escribir comandos en la terminal

**Nota educativa**: Session Manager proporciona acceso seguro a instancias sin necesidad de:
- Abrir el puerto 22 (SSH) en los Security Groups
- Gestionar claves SSH (.pem)
- Configurar bastion hosts
- Exponer instancias a internet

Además, todas las sesiones se registran en CloudTrail para auditoría. La autenticación se realiza mediante IAM, lo que significa que puedes controlar quién puede acceder a qué instancias usando políticas IAM.

### Paso 8: Validar Permisos S3

**⏱️ Tiempo estimado: 5 minutos**

Ahora validaremos que el rol IAM otorga correctamente permisos de solo lectura al bucket S3.

1. En la terminal de Session Manager, ejecuta el siguiente comando para listar el contenido de tu bucket S3:

   ```bash
   aws s3 ls s3://s3-sitio-web-{nombre-participante}
   ```

   **Reemplaza** `{nombre-participante}` con tu identificador real.

   **Ejemplo**:
   ```bash
   aws s3 ls s3://s3-sitio-web-luis
   ```

2. Deberías ver una lista de los archivos en tu bucket (index.html, error.html, etc.)

**✓ Verificación**: Confirme que:
- El comando se ejecutó exitosamente
- Se muestra una lista de archivos del bucket
- No hay errores de "Access Denied"

3. **Opcional**: Prueba que los permisos de escritura están correctamente denegados:

   ```bash
   echo "test" > test.txt
   aws s3 cp test.txt s3://s3-sitio-web-{nombre-participante}/test.txt
   ```

4. Deberías recibir un error de **Access Denied** o **upload failed**, confirmando que la política de solo lectura funciona correctamente

**✓ Verificación**: Confirme que:
- El comando de escritura falló con error de permisos
- Esto confirma que la política otorga solo permisos de lectura, no de escritura

**Nota educativa**: Acabas de validar el principio de mínimo privilegio en acción. La instancia EC2 puede:
- ✅ Listar el contenido del bucket (s3:ListBucket)
- ✅ Leer objetos del bucket (s3:GetObject)
- ❌ Escribir, modificar o eliminar objetos (permisos no otorgados)

Este enfoque minimiza el riesgo de seguridad. Si la instancia fuera comprometida, un atacante no podría modificar o eliminar datos en el bucket S3.

5. Para salir de la sesión de Session Manager, escribe:
   ```bash
   exit
   ```

## Resumen del Laboratorio

En este laboratorio has:

- Creado un rol IAM con políticas personalizadas siguiendo el principio de mínimo privilegio
- Comprendido la diferencia entre políticas gestionadas (AmazonSSMManagedInstanceCore) y políticas inline (S3ReadOnlyAccess)
- Adjuntado el rol IAM a instancias EC2 mediante la modificación del Launch Template
- Utilizado Session Manager para acceso seguro sin necesidad de SSH, claves o puertos abiertos
- Validado permisos IAM mediante AWS CLI, confirmando acceso de solo lectura a S3

Los roles IAM y Session Manager son componentes fundamentales de una arquitectura segura en AWS. Los roles proporcionan credenciales temporales que se renuevan automáticamente, eliminando la necesidad de gestionar claves de acceso permanentes. Session Manager elimina la necesidad de exponer puertos SSH y proporciona auditoría completa de todas las sesiones.

## Solución de Problemas

Si encuentra dificultades durante este laboratorio, consulte la [Guía de Solución de Problemas](../TROUBLESHOOTING.md) que contiene soluciones a errores comunes.

**Errores que requieren asistencia del instructor:**
- Errores de permisos IAM al crear roles
- Errores de límites de cuota de AWS

## Gestión del Ciclo de Vida de Recursos

⚠️ **Importante**: NO elimine el rol IAM al finalizar este laboratorio. Este recurso se utilizará en el Día 4 del workshop.

Si necesita eliminar el rol IAM más adelante, consulte la [Guía de Limpieza de Recursos](../limpieza/README.md) para instrucciones detalladas sobre cómo desasociar correctamente el rol del Launch Template antes de eliminarlo.

---

**¡Felicitaciones!** Has completado el Laboratorio 3.2. Continúa con el [Laboratorio 3.3: Gobernanza y Auditoría](../lab-3.3-governance/README.md).
