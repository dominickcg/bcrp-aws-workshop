# 🔧 Guía de Solución de Problemas - Día 2

## Introducción

Este documento proporciona soluciones a los errores más comunes que pueden ocurrir durante los laboratorios del Día 2 del Workshop BCRP de AWS. Cada error incluye el síntoma, las causas posibles y la solución recomendada.

Si encuentra un error que no está listado aquí o si las soluciones propuestas no resuelven su problema, notifique al instructor para recibir asistencia.

---

## Laboratorio 2.1 - Almacenamiento

### Error: No se puede adjuntar el volumen EBS

**Síntoma**: Al intentar adjuntar el volumen EBS a la instancia EC2, aparece un error o el volumen no se adjunta.

**Causas posibles**:
1. El volumen EBS y la instancia EC2 están en diferentes zonas de disponibilidad (AZ)
2. El volumen ya está adjunto a otra instancia
3. La instancia EC2 está detenida o en estado incorrecto

**Solución**:
1. Verifique que el volumen EBS y la instancia EC2 están en la misma zona de disponibilidad:
   - En la consola de EC2, seleccione el volumen
   - En la pestaña **Detalles**, verifique el campo **Zona de disponibilidad**
   - Compare con la zona de disponibilidad de su instancia EC2
2. Si están en diferentes AZs, debe crear un nuevo volumen en la misma AZ que su instancia
3. Verifique que el volumen tiene estado **Disponible** (no **En uso**)
4. Verifique que su instancia EC2 está en estado **En ejecución**

---

### Error: "device is busy" al montar

**Síntoma**: Al intentar montar el volumen con el comando `mount`, aparece el error "device is busy" o "el dispositivo está ocupado".

**Causas posibles**:
1. El dispositivo ya está montado en otro punto de montaje
2. Hay procesos usando el dispositivo
3. El punto de montaje ya tiene otro dispositivo montado

**Solución**:
1. Verifique si el dispositivo ya está montado:
   ```bash
   df -h
   ```
2. Si aparece montado, desmóntelo primero:
   ```bash
   sudo umount /dev/xvdf
   ```
3. Verifique que no hay procesos usando el dispositivo:
   ```bash
   sudo lsof /dev/xvdf
   ```
4. Si hay procesos, deténgalos antes de montar
5. Verifique que el punto de montaje `/mnt/data_logs` existe y está vacío:
   ```bash
   ls -la /mnt/data_logs
   ```

---

### Error: Bucket S3 ya existe

**Síntoma**: Al intentar crear el bucket S3, aparece el error "Bucket name already exists" o "El nombre del bucket ya existe".

**Causas posibles**:
1. El nombre del bucket ya está en uso por otro usuario de AWS (los nombres de bucket son únicos globalmente)
2. Otro participante del workshop ya usó ese nombre

**Solución**:
1. Agregue un número aleatorio al final del nombre del bucket:
   - Ejemplo: `workshop-aws-luis-12345` en lugar de `workshop-aws-luis`
2. Puede usar la fecha actual como sufijo:
   - Ejemplo: `workshop-aws-luis-20240115`
3. Intente crear el bucket nuevamente con el nuevo nombre

---

### Error: "Access Denied" al acceder al sitio web S3

**Síntoma**: Al intentar acceder a la URL del sitio web S3, aparece el error "Access Denied" o "403 Forbidden".

**Causas posibles**:
1. La configuración "Block all public access" está habilitada
2. La política de bucket no está aplicada correctamente
3. El archivo index.html no está en la raíz del bucket
4. La política de bucket tiene el nombre de bucket incorrecto

**Solución**:
1. Verifique que "Block all public access" está deshabilitado:
   - En la consola de S3, seleccione su bucket
   - Vaya a la pestaña **Permisos**
   - En la sección **Bloquear acceso público (configuración del bucket)**, verifique que todas las opciones están deshabilitadas
2. Verifique la política de bucket:
   - En la pestaña **Permisos**, desplácese a **Política de bucket**
   - Verifique que el nombre del bucket en el ARN es correcto
   - El ARN debe ser: `arn:aws:s3:::NOMBRE-DE-SU-BUCKET/*` (con `/*` al final)
3. Verifique que el archivo `index.html` está en la raíz del bucket (no dentro de una carpeta)
4. Intente acceder directamente al archivo: `http://NOMBRE-BUCKET.s3-website-REGION.amazonaws.com/index.html`

---

### Error: CSS/JS no cargan en el sitio web

**Síntoma**: El sitio web S3 se muestra pero sin estilos (CSS) o sin funcionalidad de JavaScript.

**Causas posibles**:
1. Los archivos CSS y JS no están en las carpetas correctas
2. Las rutas en el HTML son incorrectas
3. Los archivos CSS y JS no se cargaron al bucket

**Solución**:
1. Verifique la estructura de carpetas en el bucket:
   - Archivos HTML deben estar en la raíz del bucket
   - Archivos CSS deben estar en la carpeta `/css`
   - Archivos JS deben estar en la carpeta `/js`
   - Imágenes deben estar en la carpeta `/assets`
2. Verifique las rutas en el archivo `index.html`:
   - Deben ser rutas relativas: `css/styles.css`, `js/script.js`
   - No deben tener `/` al inicio
3. Verifique que todos los archivos se cargaron correctamente:
   - En la consola de S3, navegue dentro de las carpetas `/css` y `/js`
   - Confirme que los archivos están presentes
4. Abra la consola del navegador (F12) y verifique si hay errores 404

---

### Error: Página de error no se muestra

**Síntoma**: Al acceder a una URL inexistente en el sitio web S3, no se muestra la página de error personalizada `error.html`.

**Causas posibles**:
1. El archivo `error.html` no está en la raíz del bucket
2. La configuración de hosting estático no tiene el documento de error configurado
3. El archivo se llama diferente (ej: `404.html` en lugar de `error.html`)

**Solución**:
1. Verifique que el archivo `error.html` está en la raíz del bucket
2. Verifique la configuración de hosting estático:
   - En la consola de S3, seleccione su bucket
   - Vaya a la pestaña **Propiedades**
   - Desplácese a **Alojamiento de sitios web estáticos**
   - Verifique que el campo **Documento de error** contiene `error.html`
3. Si el campo está vacío, haga clic en **Editar** y agregue `error.html`
4. Guarde los cambios e intente acceder a una URL inexistente nuevamente

---

## Laboratorio 2.2 - RDS Multi-AZ

### Error: No se puede crear el Grupo de subredes

**Síntoma**: Al intentar crear el Grupo de subredes de RDS, aparece un error o no se puede completar la creación.

**Causas posibles**:
1. Las subredes seleccionadas están en la misma zona de disponibilidad
2. No se seleccionaron al menos 2 subredes
3. Las subredes no pertenecen a la misma VPC

**Solución**:
1. Verifique que está seleccionando al menos 2 subredes en diferentes zonas de disponibilidad:
   - Subred privada del participante (del Día 1)
   - Subred privada de respaldo del instructor
2. Verifique las zonas de disponibilidad de cada subred:
   - En la consola de VPC, vaya a **Subredes**
   - Verifique la columna **Zona de disponibilidad** de cada subred
   - Deben ser diferentes (ej: us-east-1a y us-east-1b)
3. Verifique que ambas subredes pertenecen a la misma VPC
4. Si las subredes están en la misma AZ, consulte al instructor para obtener una subred en una AZ diferente

---

### Error: RDS no inicia (estado "failed")

**Síntoma**: La instancia RDS entra en estado "failed" o "error" en lugar de "Disponible".

**Causas posibles**:
1. El Grupo de subredes no tiene subredes en al menos 2 zonas de disponibilidad
2. Hay un problema con los límites de cuota de AWS
3. El Security Group tiene configuración incorrecta
4. Hay un problema con la configuración de red

**Solución**:
1. Revise los eventos de la instancia RDS:
   - En la consola de RDS, seleccione su instancia
   - Vaya a la pestaña **Eventos**
   - Lea el mensaje de error específico
2. Si el error menciona "subnet group", verifique que el Grupo de subredes tiene al menos 2 subredes en 2 AZs diferentes
3. Si el error menciona "quota" o "límite", notifique al instructor inmediatamente
4. Si el error persiste, elimine la instancia RDS y créela nuevamente verificando todos los parámetros

---

### Error: No se puede conectar a RDS desde EC2

**Síntoma**: Al intentar conectar a la base de datos RDS desde una instancia EC2 o desde la aplicación web, aparece un error de conexión o timeout.

**Causas posibles**:
1. El Security Group de RDS no permite tráfico desde el Security Group de EC2
2. La instancia RDS está en una subred sin ruta a la instancia EC2
3. El endpoint de RDS es incorrecto
4. Las credenciales (usuario/contraseña) son incorrectas

**Solución**:
1. Verifique las reglas del Security Group de RDS:
   - En la consola de EC2, vaya a **Grupos de seguridad**
   - Seleccione el Security Group de RDS (`sg-rds-{nombre-participante}`)
   - Verifique que hay una regla de entrada:
     - **Tipo**: MySQL/Aurora
     - **Puerto**: 3306
     - **Origen**: ID del Security Group de las instancias web
2. Verifique que el endpoint de RDS es correcto:
   - En la consola de RDS, seleccione su instancia
   - Copie el **Punto de enlace** de la sección **Conectividad y seguridad**
3. Verifique las credenciales:
   - Usuario maestro debe ser el que configuró al crear la instancia
   - Contraseña debe ser la que configuró (distingue mayúsculas/minúsculas)
4. Intente conectar desde una instancia EC2 usando el comando:
   ```bash
   mysql -h ENDPOINT-RDS -u USUARIO -p
   ```

---

### Error: Timeout al crear instancia RDS

**Síntoma**: La creación de la instancia RDS tarda más de 20 minutos o parece estar "congelada".

**Causas posibles**:
1. La creación de RDS Multi-AZ puede tardar 10-15 minutos (esto es normal)
2. Hay un problema con la configuración de red que está causando demoras
3. AWS está experimentando problemas de servicio

**Solución**:
1. Si han pasado menos de 15 minutos, espere pacientemente. La creación de RDS Multi-AZ es un proceso lento
2. Verifique el estado en la consola:
   - Estado **Creando** es normal
   - Estado **Modificando** es normal
   - Estado **Disponible** significa que está listo
3. Si han pasado más de 20 minutos y el estado no cambia:
   - Verifique la pestaña **Eventos** para mensajes de error
   - Notifique al instructor si el problema persiste
4. Mientras espera, puede continuar con la configuración de Security Groups y otros pasos preparatorios

---

### Error: Security Group no permite conexión

**Síntoma**: El Security Group de RDS no permite agregar el Security Group de EC2 como origen, o la conexión falla después de configurar las reglas.

**Causas posibles**:
1. El Security Group de origen no existe o fue eliminado
2. Los Security Groups están en diferentes VPCs
3. La regla de entrada tiene el puerto incorrecto
4. La regla de entrada tiene el protocolo incorrecto

**Solución**:
1. Verifique que el Security Group de las instancias web existe:
   - En la consola de EC2, vaya a **Grupos de seguridad**
   - Busque `sg-web-{nombre-participante}`
   - Si no existe, créelo siguiendo las instrucciones del laboratorio
2. Verifique que ambos Security Groups están en la misma VPC:
   - Seleccione cada Security Group
   - Verifique el campo **VPC** en los detalles
3. Verifique la regla de entrada del Security Group de RDS:
   - **Tipo**: MySQL/Aurora (esto configura automáticamente el puerto 3306)
   - **Protocolo**: TCP
   - **Intervalo de puertos**: 3306
   - **Origen**: Seleccione "Personalizado" y busque el ID del Security Group web
4. Guarde los cambios y espere unos segundos para que se apliquen

---

## Laboratorio 2.3 - HA/ELB/ASG

### Error: CloudFormation stack en estado ROLLBACK_COMPLETE

**Síntoma**: La pila de CloudFormation entra en estado "ROLLBACK_COMPLETE" en lugar de "CREATE_COMPLETE".

**Causas posibles**:
1. Uno o más parámetros son incorrectos (endpoint RDS, ARN del Target Group, ID del Security Group)
2. El Launch Template no puede crear instancias (problema con AMI, tipo de instancia, o permisos)
3. El User Data tiene errores de sintaxis
4. Las subredes especificadas no existen o no son accesibles

**Solución**:
1. Revise los eventos de la pila:
   - En la consola de CloudFormation, seleccione su pila
   - Vaya a la pestaña **Eventos**
   - Identifique el recurso que causó el error (aparecerá con estado "CREATE_FAILED")
   - Lea el mensaje de error específico
2. Errores comunes y soluciones:
   - **"Invalid parameter"**: Verifique que todos los parámetros son correctos
     - Endpoint RDS debe tener formato: `nombre-instancia.xxxxxx.region.rds.amazonaws.com`
     - ARN del Target Group debe empezar con: `arn:aws:elasticloadbalancing:`
     - ID del Security Group debe empezar con: `sg-`
   - **"Subnet not found"**: Verifique que los IDs de subredes son correctos
   - **"Security group not found"**: Verifique que el Security Group existe
3. Elimine la pila fallida:
   - Seleccione la pila
   - Haga clic en **Eliminar**
   - Espere a que se complete la eliminación
4. Corrija los parámetros y cree la pila nuevamente

---

### Error: Instancias no pasan health check del ALB

**Síntoma**: Las instancias del Auto Scaling Group aparecen como "unhealthy" o "no saludables" en el Target Group.

**Causas posibles**:
1. El servidor web (Apache) no está ejecutándose en las instancias
2. El Security Group no permite tráfico HTTP (puerto 80) desde el ALB
3. La aplicación web tiene errores y no responde
4. El health check del Target Group está configurado incorrectamente

**Solución**:
1. Verifique el estado de las instancias en el Target Group:
   - En la consola de EC2, vaya a **Grupos de destino**
   - Seleccione su Target Group
   - Vaya a la pestaña **Destinos**
   - Verifique el estado de cada instancia
2. Si el estado es "unhealthy", conéctese por SSH a una de las instancias:
   ```bash
   ssh -i su-clave.pem ec2-user@IP-PUBLICA-INSTANCIA
   ```
3. Verifique que Apache está ejecutándose:
   ```bash
   sudo systemctl status httpd
   ```
4. Si Apache no está ejecutándose, revise los logs del User Data:
   ```bash
   sudo cat /var/log/cloud-init-output.log
   ```
5. Verifique el Security Group de las instancias:
   - Debe permitir tráfico HTTP (puerto 80) desde el Security Group del ALB
6. Verifique la configuración del health check:
   - En el Target Group, vaya a la pestaña **Comprobaciones de estado**
   - Verifique que el **Puerto** es 80 y el **Protocolo** es HTTP
   - Verifique que la **Ruta** es `/` o `/index.php`

---

### Error: No se puede acceder a la aplicación web

**Síntoma**: Al intentar acceder al DNS del ALB en el navegador, aparece un error de timeout, "no se puede acceder al sitio" o "ERR_CONNECTION_TIMED_OUT".

**Causas posibles**:
1. El ALB no tiene instancias saludables en el Target Group
2. El Security Group del ALB no permite tráfico HTTP desde internet
3. El DNS del ALB es incorrecto
4. El ALB está en estado "provisioning" o "inactive"

**Solución**:
1. Verifique el estado del ALB:
   - En la consola de EC2, vaya a **Balanceadores de carga**
   - Seleccione su ALB
   - Verifique que el **Estado** es "active" (activo)
2. Verifique el DNS del ALB:
   - Copie el **Nombre de DNS** de la sección **Descripción**
   - Debe tener formato: `alb-web-nombre-xxxxxxxxx.region.elb.amazonaws.com`
3. Verifique que hay instancias saludables:
   - En la pestaña **Grupos de destino**, verifique que al menos una instancia está "healthy"
   - Si no hay instancias saludables, vea la sección anterior
4. Verifique el Security Group del ALB:
   - En la pestaña **Seguridad**, verifique el Security Group
   - Debe tener una regla de entrada:
     - **Tipo**: HTTP
     - **Puerto**: 80
     - **Origen**: 0.0.0.0/0 (cualquier origen)
5. Espere 1-2 minutos después de que las instancias estén saludables antes de intentar acceder

---

### Error: Formulario no guarda datos en RDS

**Síntoma**: El formulario de la aplicación web se muestra correctamente, pero al enviar datos no se guardan en la base de datos o aparece un error.

**Causas posibles**:
1. La aplicación no puede conectar a RDS (problema de conectividad)
2. Las credenciales de RDS son incorrectas
3. La base de datos `workshopdb` no existe
4. La tabla `messages` no fue creada
5. El usuario de RDS no tiene permisos para escribir

**Solución**:
1. Verifique los logs de la aplicación:
   - Conéctese por SSH a una instancia del Auto Scaling Group
   - Revise los logs de Apache:
     ```bash
     sudo tail -f /var/log/httpd/error_log
     ```
2. Verifique la conexión a RDS:
   - Desde la instancia EC2, intente conectar a RDS:
     ```bash
     mysql -h ENDPOINT-RDS -u USUARIO -p
     ```
   - Si no puede conectar, vea la sección "No se puede conectar a RDS desde EC2" en el Laboratorio 2.2
3. Verifique que la base de datos existe:
   ```sql
   SHOW DATABASES;
   ```
   - Si `workshopdb` no aparece, créela:
     ```sql
     CREATE DATABASE workshopdb;
     ```
4. Verifique que la tabla existe:
   ```sql
   USE workshopdb;
   SHOW TABLES;
   ```
   - Si la tabla `messages` no existe, el script de inicialización no se ejecutó correctamente
5. Verifique los parámetros de CloudFormation:
   - En la consola de CloudFormation, seleccione su pila
   - Vaya a la pestaña **Parámetros**
   - Verifique que el endpoint, usuario y contraseña son correctos

---

### Error: Auto Scaling no lanza instancias

**Síntoma**: El Auto Scaling Group no lanza instancias o lanza menos instancias de las esperadas (capacidad deseada es 2 pero solo hay 1 o 0 instancias).

**Causas posibles**:
1. El Launch Template tiene errores de configuración
2. No hay suficientes direcciones IP disponibles en las subredes
3. Hay límites de cuota de instancias EC2 alcanzados
4. Las subredes especificadas no existen o no son accesibles

**Solución**:
1. Verifique el estado del Auto Scaling Group:
   - En la consola de EC2, vaya a **Grupos de Auto Scaling**
   - Seleccione su Auto Scaling Group
   - Vaya a la pestaña **Actividad**
   - Revise los mensajes de error en el historial de actividades
2. Errores comunes:
   - **"Insufficient capacity"**: AWS no tiene capacidad en esa AZ, intente con otra subred
   - **"Subnet not found"**: Verifique los IDs de subredes en los parámetros de CloudFormation
   - **"Security group not found"**: Verifique el ID del Security Group
   - **"You have exceeded your quota"**: Notifique al instructor inmediatamente
3. Verifique el Launch Template:
   - En la consola de EC2, vaya a **Plantillas de lanzamiento**
   - Seleccione la plantilla creada por CloudFormation
   - Verifique que la AMI, tipo de instancia y Security Group son correctos
4. Verifique las subredes:
   - En la consola de VPC, vaya a **Subredes**
   - Verifique que las subredes públicas del Día 1 existen y tienen direcciones IP disponibles

---

### Error: Alarma de CloudWatch no se activa

**Síntoma**: La alarma de CloudWatch no cambia a estado "ALARM" aunque la CPU de las instancias esté alta, o no dispara la política de escalado.

**Causas posibles**:
1. La métrica está configurada incorrectamente
2. El umbral es demasiado alto (>70%)
3. El período de evaluación es muy largo
4. El Auto Scaling Group no tiene el nombre correcto en la métrica
5. La política de escalado no está vinculada a la alarma

**Solución**:
1. Verifique la configuración de la alarma:
   - En la consola de CloudWatch, vaya a **Alarmas**
   - Seleccione su alarma
   - Verifique los siguientes parámetros:
     - **Métrica**: CPUUtilization
     - **Espacio de nombres**: AWS/EC2
     - **Dimensión**: AutoScalingGroupName = nombre-de-su-asg
     - **Estadística**: Average (Promedio)
     - **Período**: 5 minutos
     - **Umbral**: >= 70
2. Verifique el nombre del Auto Scaling Group:
   - En la consola de EC2, vaya a **Grupos de Auto Scaling**
   - Copie el nombre exacto de su Auto Scaling Group
   - Verifique que coincide con el nombre en la dimensión de la alarma
3. Verifique que la política de escalado existe:
   - En el Auto Scaling Group, vaya a la pestaña **Escalado automático**
   - Verifique que hay una política de tipo "Seguimiento de destino"
4. Para probar la alarma, puede generar carga en las instancias:
   ```bash
   # Conéctese por SSH a una instancia
   # Instale stress (herramienta de prueba de carga)
   sudo yum install -y stress
   # Genere carga de CPU
   stress --cpu 2 --timeout 600
   ```
5. Espere 5-10 minutos y verifique si la alarma cambia a estado "ALARM"

---

## Errores que Requieren Asistencia del Instructor

Los siguientes errores **NO** deben ser solucionados por el participante. Si encuentra alguno de estos errores, notifique al instructor de inmediato:

### Errores de Permisos IAM

⚠️ **Síntoma**: Aparece un mensaje de error que menciona "not authorized", "access denied", "insufficient permissions" o "no tiene permisos".

**Acción**: Notifique al instructor inmediatamente. No intente solucionar este error por su cuenta.

**Ejemplos**:
- "You are not authorized to perform this operation"
- "User: arn:aws:iam::xxxx:user/nombre is not authorized to perform: ec2:RunInstances"
- "Access Denied"

---

### Errores de Límites de Cuota de AWS

⚠️ **Síntoma**: Aparece un mensaje de error que menciona "quota", "limit exceeded", "maximum number" o "límite alcanzado".

**Acción**: Notifique al instructor inmediatamente. El instructor debe solicitar un aumento de cuota a AWS.

**Ejemplos**:
- "You have exceeded your quota for instances"
- "InstanceLimitExceeded"
- "Maximum number of addresses has been reached"
- "DBInstanceQuotaExceeded"

---

### Errores de Recursos Compartidos

⚠️ **Síntoma**: No puede acceder a recursos compartidos del instructor (VPC, subredes, Internet Gateway, etc.) o aparecen errores al intentar usarlos.

**Acción**: Notifique al instructor. Puede haber un problema con los permisos o la configuración de los recursos compartidos.

**Ejemplos**:
- "Subnet subnet-xxxxx does not exist"
- "VPC vpc-xxxxx not found"
- "You do not have permission to access this resource"

---

### Errores de Servicio de AWS

⚠️ **Síntoma**: Aparece un mensaje de error que menciona "service unavailable", "internal error", "service error" o "error del servicio".

**Acción**: Notifique al instructor. Puede haber un problema temporal con los servicios de AWS en la región.

**Ejemplos**:
- "Service Unavailable"
- "Internal Server Error"
- "We encountered an internal error. Please try again."

---

## Recursos Adicionales

Si después de revisar esta guía aún tiene problemas:

1. **Revise los logs**: Muchos servicios de AWS proporcionan logs detallados que pueden ayudar a identificar el problema
   - CloudFormation: Pestaña **Eventos**
   - EC2: `/var/log/cloud-init-output.log`
   - Apache: `/var/log/httpd/error_log`
   - RDS: Pestaña **Eventos** y **Logs**

2. **Verifique la documentación del laboratorio**: Asegúrese de haber seguido todos los pasos en el orden correcto

3. **Consulte con otros participantes**: Es posible que otro participante haya encontrado y resuelto el mismo problema

4. **Notifique al instructor**: Si ninguna de las soluciones funciona, el instructor puede ayudarle a diagnosticar y resolver el problema

---

**Última actualización**: Enero 2024
