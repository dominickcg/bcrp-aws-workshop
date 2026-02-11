# 💾 Laboratorio 1.3 - Almacenamiento de Bloques y Hosting de Objetos

## Descripción General

En este laboratorio aprenderá a trabajar con dos tipos fundamentales de almacenamiento en AWS:

- **Amazon EBS (Elastic Block Store)**: Almacenamiento de bloques persistente para instancias EC2
- **Amazon S3 (Simple Storage Service)**: Almacenamiento de objetos escalable y hosting de sitios web estáticos

Al finalizar este laboratorio, habrá configurado un volumen EBS adicional en su instancia EC2 y desplegado un sitio web estático completamente funcional en S3.

## Objetivos de Aprendizaje

- Crear y adjuntar volúmenes EBS a instancias EC2
- Formatear y montar sistemas de archivos en Linux
- Crear buckets de S3 con nombres únicos
- Configurar hosting de sitios web estáticos en S3
- Aplicar políticas de bucket para acceso público
- Verificar el funcionamiento de CSS y JavaScript en S3

## Duración Estimada

50 minutos

## Archivos de Soporte

Este laboratorio utiliza los siguientes archivos de soporte:

- **[`bucket-policy.json`](./bucket-policy.json)** - Política de bucket S3 para permitir acceso público de lectura
- **[`sitio-web-s3/`](./sitio-web-s3/)** - Carpeta con todos los archivos del sitio web estático (HTML, CSS, JavaScript, imágenes)
  - `index.html` - Página principal
  - `nosotros.html` - Página "Acerca de"
  - `contacto.html` - Página de contacto
  - `error.html` - Página de error 404 personalizada
  - `css/styles.css` - Estilos del sitio
  - `js/main.js` - JavaScript del sitio
  - `assets/` - Imágenes y recursos gráficos

## Prerrequisitos

- Laboratorio 1.1 completado (VPC y subredes configuradas)
- Laboratorio 1.2 completado (instancia EC2 en ejecución)
- Par de claves descargado del Lab 1.2
- Navegador web moderno
- Cliente SSH (PuTTY, Terminal, o similar)

---

## Paso 1: Verificación de Región

**⚠️ IMPORTANTE**: Antes de comenzar, verifique que está trabajando en la región correcta.

1. En la esquina superior derecha de la Consola de AWS, verifique que la región seleccionada sea la designada por el instructor (ejemplo: **US East (N. Virginia) us-east-1**)
2. Si no es la región correcta, haga clic en el nombre de la región y seleccione la región apropiada

---

## PARTE A - ALMACENAMIENTO EBS

### Paso 2: Crear Volumen EBS

Amazon EBS proporciona almacenamiento de bloques persistente que puede adjuntarse a instancias EC2.


1. En la barra de búsqueda global de AWS (parte superior), escriba **EC2** y seleccione el servicio
2. En el panel de navegación de la izquierda, en la sección **Elastic Block Store**, haga clic en **Volúmenes**
3. Haga clic en el botón naranja **Crear volumen** (esquina superior derecha)
4. Configure el volumen con los siguientes parámetros:
   - **Tipo de volumen**: `gp3` (General Purpose SSD)
   - **Tamaño**: `1` GiB
   - **Zona de disponibilidad**: Seleccione la **misma zona** donde está su instancia EC2 del Lab 1.2 (ejemplo: `us-east-1a`)
     - ⚠️ **CRÍTICO**: El volumen DEBE estar en la misma zona de disponibilidad que la instancia EC2
   - **Etiquetas**: Haga clic en **Agregar etiqueta** y configure:
     - Etiqueta 1:
       - **Clave**: `Name`
       - **Valor**: `ebs-data-{su-nombre}` (reemplace `{su-nombre}` con su sufijo de participante)
     - Etiqueta 2:
       - **Clave**: `Owner`
       - **Valor**: Su nombre completo
     - Etiqueta 3:
       - **Clave**: `Project`
       - **Valor**: `Workshop-BCRP`
5. Haga clic en **Crear volumen**
6. Espere a que el estado del volumen cambie de `creating` a **available** (aproximadamente 1 minuto)

**✅ Checkpoint de Verificación**: En la lista de volúmenes, debe ver su nuevo volumen con estado **available** y el nombre `ebs-data-{su-nombre}`.

**⏱️ Tiempo estimado**: 5 minutos

---

### Paso 3: Adjuntar Volumen a la Instancia EC2

Ahora adjuntaremos el volumen EBS a la instancia EC2 creada en el Lab 1.2.

1. En la lista de volúmenes, seleccione el volumen que acaba de crear (marque la casilla)
2. Haga clic en el menú **Acciones** (parte superior)
3. Seleccione **Adjuntar volumen**
4. En el campo **Instancia**, comience a escribir el nombre de su instancia EC2 (`ec2-webserver-{su-nombre}`) y selecciónela de la lista
5. En el campo **Dispositivo**, deje el valor predeterminado (ejemplo: `/dev/sdf` o `/dev/xvdf`)
   - Nota: Linux puede renombrar el dispositivo automáticamente
6. Haga clic en **Adjuntar volumen**
7. Espere a que el estado del volumen cambie de `available` a **in-use** (aproximadamente 10 segundos)

**✅ Checkpoint de Verificación**: El volumen debe mostrar estado **in-use** y en la columna **Instancia adjunta** debe aparecer el ID de su instancia EC2.

**⏱️ Tiempo estimado**: 2 minutos

---

### Paso 4: Conectarse a la Instancia EC2 por SSH

Para formatear y montar el volumen, necesitamos conectarnos a la instancia por SSH.

1. En el panel de navegación de la izquierda, haga clic en **Instancias**
2. Seleccione su instancia `ec2-webserver-{su-nombre}`
3. Copie la **Dirección IPv4 pública** de su instancia
4. Abra su cliente SSH:

**En Linux/Mac (Terminal):**
```bash
chmod 400 /ruta/a/su/keypair-{su-nombre}.pem
ssh -i /ruta/a/su/keypair-{su-nombre}.pem ec2-user@{IP-PUBLICA}
```

**En Windows (PuTTY):**
- Convierta el archivo .pem a .ppk usando PuTTYgen si aún no lo ha hecho
- Configure la conexión con la IP pública y la clave privada

5. Acepte la huella digital del host cuando se le solicite (escriba `yes`)

**✅ Checkpoint de Verificación**: Debe ver el prompt de la instancia EC2:
```
[ec2-user@ip-10-0-X-X ~]$
```

**⏱️ Tiempo estimado**: 3 minutos

---

### Paso 5: Verificar el Volumen Adjunto

Una vez conectado por SSH, verificaremos que el volumen está disponible.

1. Ejecute el siguiente comando para listar los dispositivos de bloque:
```bash
lsblk
```

2. Debe ver una salida similar a esta:
```
NAME    MAJ:MIN RM SIZE RO TYPE MOUNTPOINT
xvda    202:0    0   8G  0 disk 
└─xvda1 202:1    0   8G  0 part /
xvdf    202:80   0   1G  0 disk 
```

3. Identifique el nuevo volumen de 1 GB (en este ejemplo es `xvdf`, pero puede variar)
   - El volumen raíz (`xvda`) es de 8 GB y está montado en `/`
   - El nuevo volumen (`xvdf`) es de 1 GB y NO tiene punto de montaje

**✅ Checkpoint de Verificación**: Debe ver un dispositivo de 1 GB sin punto de montaje.

**⏱️ Tiempo estimado**: 1 minuto

---

### Paso 6: Formatear el Volumen

Antes de usar el volumen, debemos crear un sistema de archivos.

1. Verifique si el volumen ya tiene un sistema de archivos:
```bash
sudo file -s /dev/xvdf
```

2. Si la salida es `/dev/xvdf: data`, el volumen está vacío y necesita formato
3. Formatee el volumen con el sistema de archivos ext4:
```bash
sudo mkfs -t ext4 /dev/xvdf
```

4. Espere a que el proceso complete (aproximadamente 5-10 segundos)
5. Verá una salida similar a:
```
mke2fs 1.46.5 (30-Dec-2021)
Creating filesystem with 262144 4k blocks and 65536 inodes
...
Writing superblocks and filesystem accounting information: done
```

**✅ Checkpoint de Verificación**: El comando debe completarse sin errores y mostrar "done" al final.

**⏱️ Tiempo estimado**: 2 minutos

---

### Paso 7: Crear Punto de Montaje y Montar el Volumen

Ahora crearemos un directorio y montaremos el volumen formateado.

1. Cree el directorio donde se montará el volumen:
```bash
sudo mkdir -p /mnt/data_logs
```

2. Monte el volumen en el directorio:
```bash
sudo mount /dev/xvdf /mnt/data_logs
```

3. Verifique que el volumen está montado correctamente:
```bash
df -h
```

4. Debe ver una línea similar a:
```
Filesystem      Size  Used Avail Use% Mounted on
/dev/xvdf       974M   24K  907M   1% /mnt/data_logs
```

5. Verifique los permisos del directorio:
```bash
ls -ld /mnt/data_logs
```

**✅ Checkpoint de Verificación**: El comando `df -h` debe mostrar `/dev/xvdf` montado en `/mnt/data_logs` con aproximadamente 974M de tamaño.

**⏱️ Tiempo estimado**: 2 minutos

---

### Paso 8: Configurar Montaje Permanente

Para que el volumen se monte automáticamente al reiniciar la instancia, debemos configurar `/etc/fstab`.

1. Obtenga el UUID del volumen:
```bash
sudo blkid /dev/xvdf
```

2. Copie el UUID que aparece (ejemplo: `12345678-1234-1234-1234-123456789abc`)

3. Cree una copia de seguridad del archivo fstab:
```bash
sudo cp /etc/fstab /etc/fstab.backup
```

4. Edite el archivo fstab:
```bash
sudo nano /etc/fstab
```

5. Agregue la siguiente línea al final del archivo (reemplace `UUID-DEL-VOLUMEN` con el UUID que copió):
```
UUID=UUID-DEL-VOLUMEN  /mnt/data_logs  ext4  defaults,nofail  0  2
```

6. Guarde el archivo:
   - Presione `Ctrl + O` para guardar
   - Presione `Enter` para confirmar
   - Presione `Ctrl + X` para salir

7. Verifique que la configuración es correcta:
```bash
sudo mount -a
```

8. Si no hay errores, la configuración es correcta

**✅ Checkpoint de Verificación**: El comando `sudo mount -a` debe ejecutarse sin errores.

**⏱️ Tiempo estimado**: 3 minutos

---

### Paso 9: Probar el Volumen EBS

Vamos a crear un archivo de prueba en el nuevo volumen.

1. Cree un archivo de prueba:
```bash
sudo touch /mnt/data_logs/test-file.txt
echo "Laboratorio 1.3 - Workshop AWS" | sudo tee /mnt/data_logs/test-file.txt
```

2. Verifique el contenido:
```bash
cat /mnt/data_logs/test-file.txt
```

3. Liste los archivos en el volumen:
```bash
ls -lh /mnt/data_logs/
```

**✅ Checkpoint de Verificación**: Debe ver el archivo `test-file.txt` con el contenido "Laboratorio 1.3 - Workshop AWS".

**⏱️ Tiempo estimado**: 2 minutos

---

## PARTE B - ALMACENAMIENTO S3 Y HOSTING ESTÁTICO

### Paso 10: Crear Bucket de S3

Amazon S3 proporciona almacenamiento de objetos escalable y puede hospedar sitios web estáticos.


1. En la barra de búsqueda global de AWS, escriba **S3** y seleccione el servicio
2. Haga clic en el botón naranja **Crear bucket** (esquina superior derecha)
3. Configure el bucket con los siguientes parámetros:

   **Configuración general:**
   - **Nombre del bucket**: `workshop-aws-{su-nombre}-{numero-aleatorio}`
     - Ejemplo: `workshop-aws-luis-2024` o `workshop-aws-maria-5678`
     - ⚠️ **IMPORTANTE**: El nombre debe ser globalmente único en todo AWS
     - Use solo minúsculas, números y guiones
   - **Región de AWS**: Seleccione la misma región del laboratorio (ejemplo: `US East (N. Virginia) us-east-1`)

   **Configuración de acceso público:**
   - **Desmarque** la casilla "Bloquear todo el acceso público"
   - Marque la casilla de confirmación que aparece: "Reconozco que la configuración actual podría hacer que este bucket y los objetos que contiene sean públicos"

   **Versionado de bucket:**
   - Deje **deshabilitado** (configuración predeterminada)

   **Etiquetas:**
   - Haga clic en **Agregar etiqueta** y configure:
     - Etiqueta 1:
       - **Clave**: `Owner`
       - **Valor**: Su nombre completo
     - Etiqueta 2:
       - **Clave**: `Project`
       - **Valor**: `Workshop-BCRP`

   **Cifrado predeterminado:**
   - Deje la configuración predeterminada (SSE-S3)

4. Haga clic en **Crear bucket** (parte inferior de la página)
5. Debe ver un mensaje de éxito: "Se creó correctamente el bucket {nombre-del-bucket}"

**✅ Checkpoint de Verificación**: En la lista de buckets, debe ver su nuevo bucket con el nombre `workshop-aws-{su-nombre}-{numero}`.

**⏱️ Tiempo estimado**: 3 minutos

---

### Paso 11: Habilitar Hosting de Sitio Web Estático

Ahora configuraremos el bucket para hospedar un sitio web estático.

1. En la lista de buckets, haga clic en el **nombre** de su bucket (no la casilla)
2. Haga clic en la pestaña **Propiedades** (parte superior)
3. Desplácese hacia abajo hasta la sección **Alojamiento de sitios web estáticos** (última sección)
4. Haga clic en el botón **Editar**
5. Configure los siguientes parámetros:
   - **Alojamiento de sitios web estáticos**: Seleccione **Habilitar**
   - **Tipo de alojamiento**: Seleccione **Alojar un sitio web estático**
   - **Documento de índice**: `index.html`
   - **Documento de error**: `error.html`
6. Haga clic en **Guardar cambios** (parte inferior)
7. Vuelva a la sección **Alojamiento de sitios web estáticos** en la pestaña **Propiedades**
8. **Copie** el **Punto de enlace del sitio web del bucket** (ejemplo: `http://workshop-aws-luis-2024.s3-website-us-east-1.amazonaws.com`)
   - ⚠️ **IMPORTANTE**: Guarde esta URL, la necesitará para verificar el sitio web

**✅ Checkpoint de Verificación**: Debe ver el punto de enlace del sitio web en formato `http://{nombre-bucket}.s3-website-{region}.amazonaws.com`.

**⏱️ Tiempo estimado**: 2 minutos

---

### Paso 12: Descargar Archivos del Sitio Web

Los archivos del sitio web están disponibles en el repositorio del workshop.

**📁 Ubicación de los archivos:** Los archivos del sitio web se encuentran en la carpeta [`sitio-web-s3/`](./sitio-web-s3/) de este laboratorio.

**Opciones para acceder a los archivos:**

**Opción A: Desde el repositorio local (si ya clonó el repositorio)**
1. Navegue a la carpeta `dia-1/lab-1.3-storage/sitio-web-s3/` en su computadora
2. Verifique que tiene todos los archivos necesarios

**Opción B: Descargar desde GitHub (si no tiene el repositorio)**
1. Abra una nueva pestaña en su navegador
2. Navegue al repositorio del workshop (el instructor proporcionará la URL)
3. Descargue el archivo ZIP completo del repositorio, o navegue a la carpeta `dia-1/lab-1.3-storage/sitio-web-s3/`
4. Descargue los archivos individualmente o clone el repositorio completo

**Estructura de archivos requerida:**

Verifique que tiene los siguientes archivos y carpetas:
```
sitio-web-s3/
├── index.html
├── nosotros.html
├── contacto.html
├── error.html
├── css/
│   └── styles.css
├── js/
│   └── main.js
└── assets/
    ├── logo.svg
    └── favicon.svg
```

**✅ Checkpoint de Verificación**: Debe tener 4 archivos HTML en la raíz y 3 carpetas (css, js, assets) con sus respectivos archivos.

**⏱️ Tiempo estimado**: 3 minutos

---

### Paso 13: Cargar Archivos al Bucket S3

**⚠️ CRÍTICO**: Todos los archivos deben cargarse en una sola operación para mantener la estructura de carpetas.

1. Vuelva a la pestaña de la Consola de AWS con su bucket S3
2. Haga clic en la pestaña **Objetos** (si no está ya seleccionada)
3. Haga clic en el botón naranja **Cargar**
4. En la ventana de carga:
   - Haga clic en **Agregar archivos** y seleccione los 4 archivos HTML:
     - `index.html`
     - `nosotros.html`
     - `contacto.html`
     - `error.html`
   - Haga clic en **Agregar carpeta** y seleccione la carpeta `css/` completa
   - Haga clic en **Agregar carpeta** y seleccione la carpeta `js/` completa
   - Haga clic en **Agregar carpeta** y seleccione la carpeta `assets/` completa
5. Verifique que la lista de archivos muestra:
   ```
   index.html
   nosotros.html
   contacto.html
   error.html
   css/styles.css
   js/main.js
   assets/logo.svg
   assets/favicon.svg
   ```
6. Desplácese hacia abajo y haga clic en **Cargar** (parte inferior)
7. Espere a que todos los archivos se carguen (verá "Carga correcta" cuando termine)
8. Haga clic en **Cerrar** (esquina superior derecha)

**✅ Checkpoint de Verificación**: En la pestaña **Objetos**, debe ver 4 archivos HTML en la raíz y 3 carpetas (css/, js/, assets/).

**⏱️ Tiempo estimado**: 4 minutos

---

### Paso 14: Aplicar Política de Bucket para Acceso Público

Para que el sitio web sea accesible públicamente, debemos aplicar una política de bucket.

1. Haga clic en la pestaña **Permisos** (parte superior)
2. Desplácese hacia abajo hasta la sección **Política de bucket**
3. Haga clic en el botón **Editar**
4. En el editor de políticas, pegue la siguiente política JSON

**📄 Archivo de soporte:** La política completa está disponible en [`bucket-policy.json`](./bucket-policy.json) en esta misma carpeta del laboratorio.

**Contenido de la política de bucket:**

```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "PublicReadGetObject",
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::NOMBRE-DEL-BUCKET/*"
        }
    ]
}
```

5. **⚠️ IMPORTANTE**: Reemplace `NOMBRE-DEL-BUCKET` con el nombre real de su bucket
   - Ejemplo: Si su bucket se llama `workshop-aws-luis-2024`, la línea Resource debe ser:
   - `"Resource": "arn:aws:s3:::workshop-aws-luis-2024/*"`
6. Haga clic en **Guardar cambios**
7. Debe ver un mensaje: "Se editó correctamente la política de bucket"

**✅ Checkpoint de Verificación**: La sección **Política de bucket** debe mostrar "Público" con un ícono de advertencia naranja.

**⏱️ Tiempo estimado**: 3 minutos

---

### Paso 15: Verificar el Sitio Web

Ahora verificaremos que el sitio web funciona correctamente.

1. Abra una nueva pestaña en su navegador
2. Pegue el **Punto de enlace del sitio web** que copió en el Paso 11
   - Ejemplo: `http://workshop-aws-luis-2024.s3-website-us-east-1.amazonaws.com`
3. Debe ver la página principal del sitio web con:
   - Header azul oscuro con el título "Workshop AWS"
   - Logo del workshop
   - Tres tarjetas con información de los laboratorios
   - Botones naranjas
   - Footer con información del workshop

**✅ Checkpoint de Verificación - CSS Funcionando**:
- El header debe tener fondo azul oscuro (#232F3E)
- Los botones y enlaces deben ser naranjas (#FF9900)
- Las tarjetas deben estar organizadas en un diseño de grid
- El texto debe estar bien formateado y espaciado

**⏱️ Tiempo estimado**: 2 minutos

---

### Paso 16: Verificar JavaScript

Vamos a verificar que el JavaScript se está ejecutando correctamente.

1. En la página del sitio web, presione **F12** para abrir las herramientas de desarrollador
2. Haga clic en la pestaña **Console** (Consola)
3. Debe ver los siguientes mensajes:
   ```
   Workshop AWS - JavaScript loaded successfully
   Site hosted on Amazon S3
   ==================================================
   Workshop AWS - Día 1
   Laboratorio 1.3: Almacenamiento EBS y S3
   JavaScript ejecutándose correctamente
   ==================================================
   ```
4. Si ve estos mensajes, el JavaScript está funcionando correctamente

**✅ Checkpoint de Verificación**: La consola del navegador debe mostrar los mensajes de JavaScript sin errores.

**⏱️ Tiempo estimado**: 1 minuto

---

### Paso 17: Verificar Navegación entre Páginas

Vamos a verificar que la navegación entre páginas funciona correctamente.

1. En el sitio web, haga clic en el enlace **Acerca de** en el menú de navegación
2. Debe cargar la página `nosotros.html` con información detallada del workshop
3. Haga clic en el enlace **Contacto** en el menú
4. Debe cargar la página `contacto.html` con un formulario de contacto
5. Haga clic en el enlace **Inicio** para volver a la página principal

**✅ Checkpoint de Verificación**: Todas las páginas deben cargar correctamente con estilos CSS aplicados.

**⏱️ Tiempo estimado**: 1 minuto

---

### Paso 18: Verificar Página de Error

Vamos a verificar que la página de error personalizada funciona.

1. En la barra de direcciones del navegador, agregue `/pagina-inexistente` al final de la URL
   - Ejemplo: `http://workshop-aws-luis-2024.s3-website-us-east-1.amazonaws.com/pagina-inexistente`
2. Presione **Enter**
3. Debe ver la página de error personalizada `error.html` con:
   - Mensaje "Error 404 - Página No Encontrada"
   - Diseño consistente con el resto del sitio
   - Enlace para volver a la página principal

**✅ Checkpoint de Verificación**: Las rutas inexistentes deben mostrar la página `error.html` personalizada, no un error genérico de S3.

**⏱️ Tiempo estimado**: 1 minuto

---

## Resumen del Laboratorio

¡Felicitaciones! Ha completado exitosamente el Laboratorio 1.3. En este laboratorio ha:

### Parte A - EBS:
- ✅ Creado un volumen EBS gp3 de 1 GB
- ✅ Adjuntado el volumen a una instancia EC2
- ✅ Formateado el volumen con sistema de archivos ext4
- ✅ Montado el volumen en `/mnt/data_logs`
- ✅ Configurado montaje permanente en `/etc/fstab`
- ✅ Verificado el funcionamiento con un archivo de prueba

### Parte B - S3:
- ✅ Creado un bucket S3 con nombre único
- ✅ Habilitado hosting de sitio web estático
- ✅ Cargado archivos HTML, CSS, JavaScript y assets
- ✅ Aplicado política de bucket para acceso público
- ✅ Verificado el renderizado de CSS y JavaScript
- ✅ Verificado la navegación entre páginas
- ✅ Verificado la página de error personalizada

---

## Solución de Problemas

Si encuentras problemas durante este laboratorio, consulta la [Guía de Troubleshooting del Día 1](../TROUBLESHOOTING.md) para soluciones a errores comunes.

Los problemas más frecuentes en este laboratorio incluyen:
- No poder adjuntar el volumen EBS a la instancia
- Error "device is busy" al montar el volumen
- Error "Access Denied" al acceder al sitio web S3
- El nombre del bucket ya existe (no es único)
- CSS/JS no cargan correctamente en el sitio web
- La página de error personalizada no se muestra

Para soluciones detalladas, consulta la sección **Lab 1.3 - Almacenamiento** en la [Guía de Troubleshooting](../TROUBLESHOOTING.md).

---

## Gestión del Ciclo de Vida de Recursos

### Recursos que DEBEN mantenerse para el Día 2:

- ✅ **Volumen EBS** (`ebs-data-{su-nombre}`): Mantener adjunto a la instancia
- ✅ **Instancia EC2** (del Lab 1.2): Mantener en ejecución
- ✅ **Subredes** (del Lab 1.1): No eliminar
- ✅ **Tabla de ruteo** (del Lab 1.1): No eliminar
- ✅ **Security Group** (del Lab 1.2): No eliminar
- ✅ **Par de claves** (del Lab 1.2): No eliminar

### Recursos que PUEDEN eliminarse (OPCIONAL):

- 🗑️ **Bucket S3** (`workshop-aws-{su-nombre}-{numero}`): Puede eliminarse si desea liberar espacio
  - Nota: Si elimina el bucket, deberá recrearlo en laboratorios futuros si es necesario

### Recursos que NO DEBE modificar o eliminar:

- ⛔ **VPC** (`Lab-VPC`): Creada por el instructor, NO ELIMINAR
- ⛔ **Internet Gateway** (`Lab-IGW`): Creado por el instructor, NO ELIMINAR
- ⛔ Cualquier recurso sin su sufijo de participante

---

## Recursos Adicionales

- [Documentación de Amazon EBS](https://docs.aws.amazon.com/es_es/AWSEC2/latest/UserGuide/AmazonEBS.html)
- [Documentación de Amazon S3](https://docs.aws.amazon.com/es_es/AmazonS3/latest/userguide/Welcome.html)
- [Hosting de sitios web estáticos en S3](https://docs.aws.amazon.com/es_es/AmazonS3/latest/userguide/WebsiteHosting.html)
- [Políticas de bucket S3](https://docs.aws.amazon.com/es_es/AmazonS3/latest/userguide/bucket-policies.html)

---

## Próximos Pasos

Ha completado todos los laboratorios del Día 1. Puede:

1. Revisar los conceptos aprendidos en cada laboratorio
2. Explorar las opciones adicionales de EBS y S3 en la consola
3. Consultar la documentación adicional proporcionada
4. Prepararse para los laboratorios del Día 2

**¡Excelente trabajo!** 🎉

