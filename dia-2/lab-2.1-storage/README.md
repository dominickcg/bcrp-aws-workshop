# 💾 Laboratorio 2.1 - Almacenamiento de Bloques y Hosting de Objetos

## Índice

- [Descripción General](#descripción-general)
- [Objetivos de Aprendizaje](#objetivos-de-aprendizaje)
- [Duración Estimada](#duración-estimada)
- [Archivos de Soporte](#archivos-de-soporte)
- [Prerequisitos](#prerequisitos)
- [Instrucciones](#instrucciones)
  - [Paso 1: Verificación de Región](#paso-1-verificación-de-región)
  - [PARTE A - Almacenamiento EBS](#parte-a---almacenamiento-ebs)
  - [PARTE B - Almacenamiento S3 y Hosting Estático](#parte-b---almacenamiento-s3-y-hosting-estático)
- [Resumen del Laboratorio](#resumen-del-laboratorio)
- [Solución de Problemas](#solución-de-problemas)
- [Gestión del Ciclo de Vida de Recursos](#gestión-del-ciclo-de-vida-de-recursos)

## Descripción General

En este laboratorio aprenderás a trabajar con dos tipos fundamentales de almacenamiento en AWS: **Amazon EBS** (Elastic Block Store) para almacenamiento de bloques persistente adjunto a instancias EC2, y **Amazon S3** (Simple Storage Service) para almacenamiento de objetos y hosting de sitios web estáticos.

**Amazon EBS** proporciona volúmenes de almacenamiento de bloques que se comportan como discos duros tradicionales, ideales para bases de datos, sistemas de archivos y aplicaciones que requieren acceso de baja latencia. **Amazon S3** es un servicio de almacenamiento de objetos altamente escalable, perfecto para archivos estáticos, backups, contenido multimedia y hosting de sitios web.

## Objetivos de Aprendizaje

Al completar este laboratorio, serás capaz de:

- Crear y adjuntar volúmenes EBS a instancias EC2 existentes
- Formatear, montar y configurar volúmenes EBS para uso persistente en Linux
- Crear buckets S3 y configurarlos para hosting de sitios web estáticos
- Aplicar políticas de acceso público a buckets S3 de forma segura

## Duración Estimada

⏱️ **50 minutos** (25 minutos para EBS, 25 minutos para S3)

## Archivos de Soporte

- `bucket-policy.json`: Política de acceso público para el bucket S3

## Prerequisitos

Para completar este laboratorio necesitas:

- **Instancia EC2 del Día 1**: La instancia creada en el Lab 1.2 debe estar en estado "En ejecución"
- **Par de claves SSH**: El par de claves creado en el Día 1 para conectarse a la instancia
- **Security Group**: El Security Group del Día 1 que permite SSH (puerto 22)

⚠️ **Importante**: Este laboratorio reutiliza recursos del Día 1. No crees una nueva instancia EC2.

## Instrucciones

### Paso 1: Verificación de Región

Antes de comenzar, es fundamental verificar que estás trabajando en la región correcta:

1. En la esquina superior derecha de la consola de AWS, verifica la región actual
2. Confirma que dice la región estipulada por el instructor
3. Si no es correcta, haz clic en el selector de región y selecciona la región indicada

⚠️ **Advertencia**: Crear recursos en la región incorrecta puede causar problemas de conectividad y costos innecesarios.

---

## PARTE A - Almacenamiento EBS

### Paso 2: Crear Volumen EBS

Amazon EBS proporciona almacenamiento de bloques persistente para tus instancias EC2. A diferencia del almacenamiento de instancia (efímero), los volúmenes EBS persisten independientemente del ciclo de vida de la instancia.

1. En la barra de búsqueda global (parte superior), escribe **EC2** y haz clic en el servicio
2. En el panel de navegación de la izquierda, desplázate hacia abajo y haz clic en **Volúmenes** (sección "Elastic Block Store")
3. Haz clic en el botón naranja **Crear volumen**
4. Configure los siguientes parámetros:
   - **Tipo de volumen**: gp3 (SSD de uso general)
   - **Tamaño**: 1 GiB
   - **Zona de disponibilidad**: Seleccione la MISMA zona de disponibilidad donde está su instancia EC2 del Día 1
     - ⚠️ **Crítico**: El volumen debe estar en la misma AZ que la instancia para poder adjuntarlo
   - **Etiquetas**: Haga clic en "Agregar etiqueta"
     - **Clave**: Name
     - **Valor**: `ebs-data-{nombre-participante}`
5. Haz clic en **Crear volumen**

**✓ Verificación**: En la lista de volúmenes, confirme que:
- El nuevo volumen aparece con el nombre `ebs-data-{nombre-participante}`
- El estado es **Disponible** (verde)
- La zona de disponibilidad coincide con la de su instancia EC2

### Paso 3: Adjuntar Volumen a Instancia EC2

Ahora conectaremos el volumen EBS a su instancia EC2 del Día 1.

1. En la lista de volúmenes, seleccione el volumen `ebs-data-{nombre-participante}` (marque la casilla)
2. Haz clic en el menú **Acciones** (parte superior derecha)
3. Seleccione **Adjuntar volumen**
4. Configure los parámetros:
   - **Instancia**: Seleccione su instancia EC2 del Día 1 (busque por su nombre con su sufijo de participante)
   - **Dispositivo**: Deje el valor predeterminado (por ejemplo, `/dev/sdf` o `/dev/xvdf`)
5. Haz clic en **Adjuntar volumen**

**✓ Verificación**: En la lista de volúmenes, confirme que:
- El estado del volumen cambió a **En uso**
- La columna **Instancia adjunta** muestra el ID de su instancia EC2

### Paso 4: Conectarse por SSH a la Instancia

Para trabajar con el volumen, necesitamos conectarnos a la instancia EC2.

1. En el panel de navegación de la izquierda, haz clic en **Instancias**
2. Seleccione su instancia EC2 del Día 1
3. Haz clic en el botón **Conectar** (parte superior)
4. Seleccione la pestaña **Cliente SSH**
5. Siga las instrucciones para conectarse usando su par de claves

   Ejemplo de comando (ajuste según su configuración):
   ```bash
   ssh -i "su-clave.pem" ec2-user@ec2-XX-XX-XX-XX.compute-1.amazonaws.com
   ```

**✓ Verificación**: Confirme que está conectado cuando vea el prompt de la instancia:
```
[ec2-user@ip-XX-XX-XX-XX ~]$
```

### Paso 5: Verificar Volumen con lsblk

El comando `lsblk` lista todos los dispositivos de bloques disponibles en el sistema.

1. En la sesión SSH, ejecute el siguiente comando:
   ```bash
   lsblk
   ```

2. Identifique el nuevo volumen en la salida. Debería ver algo similar a:
   ```
   NAME    MAJ:MIN RM SIZE RO TYPE MOUNTPOINT
   xvda    202:0    0   8G  0 disk 
   └─xvda1 202:1    0   8G  0 part /
   xvdf    202:80   0   1G  0 disk
   ```

   En este ejemplo, `xvdf` es el nuevo volumen de 1 GB que acabamos de adjuntar.

⚠️ **Nota**: El nombre del dispositivo puede variar (`xvdf`, `nvme1n1`, etc.) dependiendo del tipo de instancia. Use el tamaño (1G) para identificarlo.

**✓ Verificación**: Confirme que ve un dispositivo de 1 GB sin punto de montaje (MOUNTPOINT vacío).

### Paso 6: Formatear Volumen

Los volúmenes EBS nuevos no tienen sistema de archivos. Debemos formatearlos antes de usarlos.

1. Formatee el volumen con el sistema de archivos ext4:
   ```bash
   sudo mkfs -t ext4 /dev/xvdf
   ```

   ⚠️ **Importante**: Reemplace `/dev/xvdf` con el nombre de dispositivo que identificó en el paso anterior.

2. El comando mostrará una salida similar a:
   ```
   mke2fs 1.45.6 (20-Mar-2020)
   Creating filesystem with 262144 4k blocks and 65536 inodes
   ...
   Writing superblocks and filesystem accounting information: done
   ```

**✓ Verificación**: El comando debe completarse sin errores y mostrar "done" al final.

### Paso 7: Crear Punto de Montaje y Montar Volumen

Un punto de montaje es un directorio donde se accederá al contenido del volumen.

1. Cree el directorio que servirá como punto de montaje:
   ```bash
   sudo mkdir -p /mnt/data_logs
   ```

2. Monte el volumen en el punto de montaje:
   ```bash
   sudo mount /dev/xvdf /mnt/data_logs
   ```

3. Verifique que el volumen está montado correctamente:
   ```bash
   df -h
   ```

   Debería ver una línea similar a:
   ```
   Filesystem      Size  Used Avail Use% Mounted on
   /dev/xvdf       974M   24K  907M   1% /mnt/data_logs
   ```

**✓ Verificación**: Confirme que `/mnt/data_logs` aparece en la salida de `df -h` con aproximadamente 1 GB de espacio disponible.

### Paso 8: Configurar Montaje Permanente

El montaje actual es temporal y se perderá al reiniciar la instancia. Para hacerlo permanente, debemos editar el archivo `/etc/fstab`.

1. Primero, obtenga el UUID del volumen:
   ```bash
   sudo blkid /dev/xvdf
   ```

   La salida será similar a:
   ```
   /dev/xvdf: UUID="12345678-1234-1234-1234-123456789abc" TYPE="ext4"
   ```

   Copie el valor del UUID (sin las comillas).

2. Edite el archivo `/etc/fstab`:
   ```bash
   sudo nano /etc/fstab
   ```

3. Agregue la siguiente línea al final del archivo (reemplace el UUID con el suyo):
   ```
   UUID=12345678-1234-1234-1234-123456789abc  /mnt/data_logs  ext4  defaults,nofail  0  2
   ```

4. Guarde el archivo:
   - Presione `Ctrl + O` para guardar
   - Presione `Enter` para confirmar
   - Presione `Ctrl + X` para salir

5. Verifique que la configuración es correcta:
   ```bash
   sudo mount -a
   ```

   Si no hay errores, la configuración es correcta.

**✓ Verificación**: El comando `sudo mount -a` debe ejecutarse sin errores.

### Paso 9: Probar con Archivo de Prueba

Vamos a verificar que el volumen funciona correctamente creando un archivo de prueba.

1. Cree un archivo de prueba en el volumen:
   ```bash
   sudo touch /mnt/data_logs/test-file.txt
   echo "Este es un archivo de prueba en el volumen EBS" | sudo tee /mnt/data_logs/test-file.txt
   ```

2. Verifique que el archivo se creó correctamente:
   ```bash
   cat /mnt/data_logs/test-file.txt
   ```

3. Liste el contenido del directorio:
   ```bash
   ls -lh /mnt/data_logs/
   ```

**✓ Verificación**: Confirme que:
- El comando `cat` muestra el contenido del archivo
- El comando `ls` muestra el archivo `test-file.txt`

🎉 **¡Excelente!** Has completado la configuración del almacenamiento EBS. El volumen ahora está disponible para almacenar datos de forma persistente.

---

## PARTE B - Almacenamiento S3 y Hosting Estático

### Paso 10: Crear Bucket S3

Amazon S3 organiza los objetos en contenedores llamados "buckets". Los nombres de bucket deben ser únicos globalmente en toda AWS.

1. En la barra de búsqueda global (parte superior), escribe **S3** y haz clic en el servicio
2. Haz clic en el botón naranja **Crear bucket**
3. Configure los siguientes parámetros:
   - **Nombre del bucket**: `workshop-aws-{nombre-participante}-{numero-aleatorio}`
     - Ejemplo: `workshop-aws-luis-8472`
     - ⚠️ **Importante**: El nombre debe ser único globalmente. Si recibe un error de que el nombre ya existe, agregue más números aleatorios
   - **Región de AWS**: Seleccione la región estipulada por el instructor (debe coincidir con la región actual)
   - **Configuración de bloqueo de acceso público**: 
     - ⚠️ **Desmarque** la casilla "Bloquear todo el acceso público"
     - Marque la casilla de confirmación que aparece: "Reconozco que la configuración actual podría hacer que este bucket y los objetos que contiene sean públicos"
     - ⚠️ **Nota de seguridad**: En este laboratorio deshabilitamos el bloqueo para permitir hosting público. En producción, use CloudFront con OAI para mayor seguridad
4. Deje las demás opciones con sus valores predeterminados
5. Haz clic en **Crear bucket**

**✓ Verificación**: En la lista de buckets, confirme que:
- El nuevo bucket aparece con el nombre que especificó
- La columna **Región** muestra la región correcta
- El bucket está accesible (sin errores de permisos)

### Paso 11: Habilitar Hosting de Sitio Web Estático

S3 puede servir sitios web estáticos directamente, sin necesidad de un servidor web tradicional.

1. En la lista de buckets, haga clic en el nombre de su bucket
2. Haga clic en la pestaña **Propiedades**
3. Desplácese hacia abajo hasta la sección **Alojamiento de sitios web estáticos**
4. Haga clic en **Editar**
5. Configure los siguientes parámetros:
   - **Alojamiento de sitios web estáticos**: Seleccione **Habilitar**
   - **Tipo de alojamiento**: Seleccione **Alojar un sitio web estático**
   - **Documento de índice**: `index.html`
   - **Documento de error**: `error.html`
6. Haz clic en **Guardar cambios**
7. Vuelva a la sección **Alojamiento de sitios web estáticos** y copie la **URL del punto de enlace del sitio web del bucket**
   - Ejemplo: `http://workshop-aws-luis-8472.s3-website-us-east-1.amazonaws.com`
   - ⚠️ **Guarde esta URL**: La necesitará para acceder al sitio web

**✓ Verificación**: Confirme que:
- La sección **Alojamiento de sitios web estáticos** muestra el estado **Habilitado**
- Tiene copiada la URL del punto de enlace del sitio web

### Paso 12: Descargar Archivos del Sitio Web

Para este laboratorio, utilizaremos un sitio web de ejemplo que ya está disponible en el repositorio del workshop.

1. Abra una nueva pestaña en su navegador
2. Navegue al repositorio del workshop en GitHub (el instructor proporcionará la URL)
3. Localice la carpeta `dia-1/lab-1.3-storage/sitio-web-s3/`
4. Haga clic en el botón verde **Code** y seleccione **Download ZIP**
5. Extraiga el archivo ZIP en su computadora local
6. Navegue a la carpeta extraída y localice la carpeta `sitio-web-s3/`

**✓ Verificación**: Confirme que tiene los siguientes archivos y carpetas:
- `index.html` (archivo principal)
- `about.html` (página adicional)
- `error.html` (página de error personalizada)
- `css/` (carpeta con estilos)
- `js/` (carpeta con scripts)
- `assets/` (carpeta con imágenes)

### Paso 13: Cargar Archivos al Bucket

Ahora cargaremos los archivos del sitio web al bucket S3, manteniendo la estructura de carpetas correcta.

1. Regrese a la consola de AWS, en la página de su bucket S3
2. Haga clic en la pestaña **Objetos**
3. Haga clic en el botón naranja **Cargar**
4. Haga clic en **Agregar archivos** y seleccione los archivos HTML de la raíz:
   - `index.html`
   - `about.html`
   - `error.html`
5. Haga clic en **Agregar carpeta** y seleccione las carpetas:
   - `css/`
   - `js/`
   - `assets/`
6. Verifique que la estructura en la vista previa sea correcta:
   ```
   index.html
   about.html
   error.html
   css/
   js/
   assets/
   ```
7. Desplácese hacia abajo y haga clic en **Cargar**
8. Espere a que la carga se complete (verá "Carga correcta")
9. Haga clic en **Cerrar**

**✓ Verificación**: En la pestaña **Objetos**, confirme que:
- Los tres archivos HTML aparecen en la raíz del bucket
- Las tres carpetas (`css/`, `js/`, `assets/`) aparecen en la raíz
- No hay errores de carga

### Paso 14: Aplicar Política de Bucket para Acceso Público

Para que el sitio web sea accesible públicamente, debemos aplicar una política de bucket que permita lectura pública de los objetos.

1. Haga clic en la pestaña **Permisos**
2. Desplácese hacia abajo hasta la sección **Política del bucket**
3. Haga clic en **Editar**
4. Abra el archivo `bucket-policy.json` ubicado en esta carpeta del laboratorio
5. Copie el contenido del archivo
6. Pegue el contenido en el editor de políticas
7. **Reemplace** `NOMBRE-DEL-BUCKET` con el nombre real de su bucket
   - Ejemplo: Si su bucket se llama `workshop-aws-luis-8472`, la línea debe quedar:
   ```json
   "Resource": "arn:aws:s3:::workshop-aws-luis-8472/*"
   ```
8. Haga clic en **Guardar cambios**

**✓ Verificación**: Confirme que:
- La política se guardó sin errores
- La sección **Política del bucket** muestra el contenido de la política

### Paso 15: Verificar Sitio Web Funcionando

Ahora vamos a verificar que el sitio web está accesible públicamente.

1. Abra una nueva pestaña en su navegador
2. Pegue la URL del punto de enlace del sitio web que copió en el Paso 11
3. Presione Enter

**✓ Verificación**: Confirme que:
- El sitio web carga correctamente
- Ve el contenido de `index.html` con estilos aplicados
- No hay errores de "Access Denied" o "404 Not Found"

### Paso 16: Verificar JavaScript Ejecutándose

Vamos a verificar que los archivos JavaScript se cargan y ejecutan correctamente.

1. En la página del sitio web, abra la consola del navegador:
   - **Chrome/Edge**: Presione `F12` o `Ctrl + Shift + I`, luego haga clic en la pestaña "Console"
   - **Firefox**: Presione `F12` o `Ctrl + Shift + K`
   - **Safari**: Presione `Cmd + Option + C`

2. Verifique que no hay errores de carga de archivos JavaScript
3. Interactúe con elementos del sitio que usen JavaScript (si los hay)

**✓ Verificación**: Confirme que:
- No hay errores en la consola del navegador relacionados con archivos `.js`
- Los elementos interactivos funcionan correctamente

### Paso 17: Verificar Navegación Entre Páginas

Vamos a verificar que la navegación entre páginas funciona correctamente.

1. En el sitio web, busque el enlace a "Acerca de" o "About"
2. Haga clic en el enlace
3. Verifique que la página `about.html` carga correctamente

**✓ Verificación**: Confirme que:
- La página `about.html` carga sin errores
- Los estilos CSS se aplican correctamente
- Puede navegar de regreso a la página principal

### Paso 18: Verificar Página de Error Personalizada

Finalmente, vamos a verificar que la página de error personalizada funciona correctamente.

1. En la barra de direcciones del navegador, agregue `/pagina-inexistente.html` al final de la URL
   - Ejemplo: `http://workshop-aws-luis-8472.s3-website-us-east-1.amazonaws.com/pagina-inexistente.html`
2. Presione Enter

**✓ Verificación**: Confirme que:
- En lugar de un error genérico de S3, ve el contenido de `error.html`
- La página de error tiene estilos aplicados
- El mensaje de error es personalizado

🎉 **¡Felicitaciones!** Has completado exitosamente el Laboratorio 2.1. Ahora tienes experiencia práctica con almacenamiento EBS para datos persistentes y S3 para hosting de sitios web estáticos.

---

## Resumen del Laboratorio

En este laboratorio has aprendido a:

- **Almacenamiento EBS**: Crear, adjuntar, formatear y montar volúmenes EBS para almacenamiento de bloques persistente
- **Configuración de Linux**: Usar comandos como `lsblk`, `mkfs`, `mount` y editar `/etc/fstab` para montaje permanente
- **Almacenamiento S3**: Crear buckets S3 y configurarlos para hosting de sitios web estáticos
- **Políticas de S3**: Aplicar políticas de bucket para permitir acceso público de lectura
- **Diferencias clave**: EBS es ideal para datos que requieren acceso de baja latencia desde EC2, mientras que S3 es perfecto para contenido estático, backups y distribución web

## Solución de Problemas

Si encuentra dificultades durante este laboratorio, consulte la [Guía de Solución de Problemas](../TROUBLESHOOTING.md) que contiene soluciones a errores comunes.

**Errores que requieren asistencia del instructor:**
- Errores de permisos IAM al crear recursos
- Errores de límites de cuota de AWS
- Problemas de conectividad de red que impiden acceso SSH

⚠️ **Si recibe un error de permisos, notifique al instructor de inmediato. No intente solucionar este error por su cuenta.**

## Gestión del Ciclo de Vida de Recursos

⚠️ **Importante**: NO elimine los recursos creados en este laboratorio al finalizar. Los utilizaremos como referencia durante el resto del Día 2.

**Recursos creados en este laboratorio:**
- Volumen EBS: `ebs-data-{nombre-participante}`
- Bucket S3: `workshop-aws-{nombre-participante}-{numero-aleatorio}`

**Recursos compartidos (NO modificar):**
- VPC del instructor
- Subredes del instructor
- Internet Gateway del instructor

Si desea eliminar estos recursos al final del workshop, consulte la [Guía de Limpieza](../limpieza/README.md).
