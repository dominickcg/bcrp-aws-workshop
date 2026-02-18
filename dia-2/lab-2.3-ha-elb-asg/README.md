# ⚡ Laboratorio 2.3 - Elasticidad y Alta Disponibilidad Integrada

## Índice

- [Descripción General](#descripción-general)
- [Objetivos de Aprendizaje](#objetivos-de-aprendizaje)
- [Duración Estimada](#duración-estimada)
- [Archivos de Soporte](#archivos-de-soporte)
- [Prerequisitos](#prerequisitos)
- [Instrucciones](#instrucciones)
  - [Paso 1: Verificación de Región](#paso-1-verificación-de-región)
  - [PARTE A - BALANCEADOR DE CARGA](#parte-a---balanceador-de-carga)
  - [PARTE B - DESPLIEGUE CON CLOUDFORMATION](#parte-b---despliegue-con-cloudformation)
  - [PARTE C - VERIFICACIÓN Y PRUEBAS](#parte-c---verificación-y-pruebas)
  - [PARTE D - MONITOREO Y ESCALADO](#parte-d---monitoreo-y-escalado)
- [Resumen del Laboratorio](#resumen-del-laboratorio)
- [Solución de Problemas](#solución-de-problemas)
- [Gestión del Ciclo de Vida de Recursos](#gestión-del-ciclo-de-vida-de-recursos)

## Descripción General

En este laboratorio, implementarás una arquitectura web escalable y de alta disponibilidad utilizando servicios avanzados de AWS. Desplegarás un Application Load Balancer (ALB) que distribuye el tráfico entre múltiples instancias EC2 gestionadas por un Auto Scaling Group (ASG). Utilizarás CloudFormation para automatizar el despliegue de la infraestructura como código, y configurarás CloudWatch para monitorear y escalar automáticamente según la demanda.

La aplicación web que desplegarás se conectará a la base de datos RDS Multi-AZ creada en el Laboratorio 2.2, permitiendo almacenar y recuperar datos de forma persistente. Esta arquitectura representa un patrón común en aplicaciones web modernas que requieren elasticidad y alta disponibilidad.

## Objetivos de Aprendizaje

Al completar este laboratorio, serás capaz de:

- Configurar un Application Load Balancer (ALB) para distribuir tráfico entre múltiples instancias
- Utilizar CloudFormation para desplegar infraestructura como código de forma automatizada
- Implementar un Auto Scaling Group que ajusta la capacidad según la demanda
- Configurar alarmas de CloudWatch y políticas de escalado para elasticidad automática

## Duración Estimada

50 minutos

## Archivos de Soporte

- `cloudformation-template.yaml`: Plantilla de CloudFormation que despliega el Auto Scaling Group con la aplicación web

## Prerequisitos

Antes de comenzar este laboratorio, debes tener:

- **Del Día 1 - Lab 1.1**: Subredes públicas en al menos 2 zonas de disponibilidad
- **Del Día 2 - Lab 2.2**: 
  - Instancia RDS MySQL en estado "Disponible"
  - Endpoint de RDS anotado
  - Credenciales de RDS (usuario y contraseña) anotadas
  - Security Group para ALB (`alb-sg-{nombre-participante}`)
  - Security Group para instancias web (`web-sg-{nombre-participante}`)

## Instrucciones

### Paso 1: Verificación de Región

1. Verifique que está trabajando en la región correcta:
   - En la esquina superior derecha de la consola de AWS
   - Confirme que dice la región estipulada por el instructor
   - Si no es correcta, haga clic y seleccione la región indicada

---

## PARTE A - BALANCEADOR DE CARGA

### Paso 2: Crear Application Load Balancer

Un Application Load Balancer (ALB) distribuye automáticamente el tráfico entrante entre múltiples instancias EC2 en diferentes zonas de disponibilidad, mejorando la disponibilidad y tolerancia a fallos de su aplicación.

1. En la barra de búsqueda global (parte superior), escriba **EC2** y haga clic en el servicio
2. En el panel de navegación de la izquierda, desplácese hacia abajo y haga clic en **Balanceadores de carga**
3. Haga clic en el botón naranja **Crear balanceador de carga**
4. En la sección **Application Load Balancer**, haga clic en **Crear**
5. Configure los siguientes parámetros básicos:
   - **Nombre del balanceador de carga**: `alb-web-{nombre-participante}`
   - **Esquema**: Seleccione **Orientado a Internet**
   - **Tipo de dirección IP**: Seleccione **IPv4**

**✓ Verificación**: Confirme que el nombre incluye su identificador de participante y que el esquema es "Orientado a Internet".

### Paso 3: Mapear a Subredes Públicas

1. En la sección **Mapeo de red**:
   - **VPC**: Seleccione la VPC compartida del workshop (proporcionada por el instructor)
   - **Mapeos**: Seleccione al menos **2 zonas de disponibilidad**
   - Para cada zona seleccionada, elija la **subred pública** correspondiente (las que creó en el Lab 1.1 del Día 1)

⚠️ **Importante**: Debe seleccionar al menos 2 zonas de disponibilidad para que el ALB funcione correctamente.

2. En la sección **Grupos de seguridad**:
   - Elimine el grupo de seguridad predeterminado si está seleccionado
   - Seleccione el Security Group **`alb-sg-{nombre-participante}`** que creó en el Lab 2.2

⚠️ **Importante**: Use el Security Group del ALB (`alb-sg-{nombre-participante}`), NO el de las instancias web. El ALB debe aceptar tráfico de internet, mientras que las instancias solo aceptarán tráfico del ALB.

**✓ Verificación**: Confirme que ha seleccionado al menos 2 subredes públicas en diferentes zonas de disponibilidad y el Security Group `alb-sg-{nombre-participante}`.

### Paso 4: Crear Target Group

1. En la sección **Agentes de escucha y enrutamiento**:
   - Verifique que existe un agente de escucha predeterminado en el puerto **80** con protocolo **HTTP**
   - En **Acción predeterminada**, haga clic en **Crear grupo de destino**

2. Se abrirá una nueva pestaña. Configure el Target Group:
   - **Tipo de destino**: Seleccione **Instancias**
   - **Nombre del grupo de destino**: `tg-web-{nombre-participante}`
   - **Protocolo**: **HTTP**
   - **Puerto**: **80**
   - **VPC**: Seleccione la VPC compartida del workshop

3. En la sección **Comprobaciones de estado**:
   - **Ruta de comprobación de estado**: `/health.php`
   - **Intervalo**: **30** segundos
   - **Tiempo de espera**: **5** segundos
   - **Umbral correcto**: **2** comprobaciones consecutivas
   - **Umbral incorrecto**: **3** comprobaciones consecutivas

   **📝 Notas sobre la configuración de health checks:**
   - **Ruta `/health.php`**: Usamos `/health.php` en lugar de `/` para que el health check no dependa de la conexión a RDS. Este endpoint simple solo verifica que Apache y PHP están funcionando correctamente.
   - **Umbral correcto de 2**: Permite que las instancias sean marcadas como healthy más rápido (60 segundos en lugar de 150 segundos con el valor predeterminado de 5).
   - **Umbral incorrecto de 3**: Hace el sistema más tolerante a fallos temporales (90 segundos en lugar de 60 segundos con el valor predeterminado de 2), evitando que instancias saludables sean marcadas como unhealthy por problemas momentáneos de red.

4. Haga clic en **Siguiente**

5. En la página **Registrar destinos**:
   - **NO registre ninguna instancia manualmente** (el Auto Scaling Group las registrará automáticamente)
   - Haga clic en **Crear grupo de destino**

6. Cierre la pestaña del Target Group y regrese a la pestaña del ALB

7. En la sección **Agentes de escucha y enrutamiento**:
   - Haga clic en el icono de actualizar junto a **Acción predeterminada**
   - Seleccione el Target Group `tg-web-{nombre-participante}` que acaba de crear

8. Desplácese hacia abajo y haga clic en **Crear balanceador de carga**

⏱️ **Nota**: El ALB puede tardar 2-3 minutos en estar activo.

**✓ Verificación**: En la lista de balanceadores de carga, confirme que:
- El estado del ALB es **Activo** (puede tardar unos minutos)
- El esquema es **Orientado a Internet**
- Hay al menos 2 zonas de disponibilidad listadas

### Paso 5: Copiar ARN del Target Group

1. En el panel de navegación de la izquierda, haga clic en **Grupos de destino**
2. Seleccione el Target Group `tg-web-{nombre-participante}`
3. En la pestaña **Detalles**, copie el **ARN** completo del Target Group
4. Guarde este ARN en un archivo de texto temporal, lo necesitará en el siguiente paso

**✓ Verificación**: En la lista de grupos de destino, confirme que:
- El nombre es `tg-web-{nombre-participante}`
- El protocolo es HTTP en el puerto 80
- La ruta de comprobación de estado es `/health.php`
- El intervalo es 30 segundos
- El tiempo de espera es 5 segundos
- El umbral correcto es 2 comprobaciones consecutivas
- El umbral incorrecto es 3 comprobaciones consecutivas

**✓ Verificación del ARN**: El ARN debe tener el formato: `arn:aws:elasticloadbalancing:region:account-id:targetgroup/tg-web-{nombre-participante}/...`

---

## PARTE B - DESPLIEGUE CON CLOUDFORMATION

### Paso 6: Abrir Consola de CloudFormation

CloudFormation es un servicio de infraestructura como código que permite definir y aprovisionar recursos de AWS de forma automatizada y repetible. En lugar de crear recursos manualmente uno por uno, CloudFormation utiliza plantillas para desplegar toda la infraestructura de forma consistente.

1. En la barra de búsqueda global (parte superior), escriba **CloudFormation** y haga clic en el servicio
2. Haga clic en el botón naranja **Crear pila**
3. Seleccione **Con recursos nuevos (estándar)**

### Paso 7: Cargar Plantilla de CloudFormation

1. En la sección **Especificar plantilla**:
   - Seleccione **Cargar un archivo de plantilla**
   - Haga clic en **Elegir archivo**
   - Navegue hasta la carpeta de este laboratorio y seleccione el archivo **`cloudformation-template.yaml`**
   - Haga clic en **Siguiente**

### Paso 8: Configurar Parámetros de la Pila

⚠️ **IMPORTANTE - Verificación del Security Group**: Antes de continuar, debe asegurarse de que el Security Group `web-sg-{nombre-participante}` tiene las reglas correctas:

1. Vaya a **EC2** > **Grupos de seguridad**
2. Busque y seleccione `web-sg-{nombre-participante}`
3. En la pestaña **Reglas de entrada**, verifique que tiene:
   - **Tipo**: HTTP
   - **Puerto**: 80
   - **Origen**: `alb-sg-{nombre-participante}` (debe aparecer el ID del Security Group del ALB)

4. Si NO tiene esta regla correctamente configurada, haga clic en **Editar reglas de entrada** y corríjala:
   - Elimine cualquier regla que permita tráfico desde 0.0.0.0/0
   - Haga clic en **Agregar regla**
   - **Tipo**: HTTP
   - **Origen**: Busque y seleccione `alb-sg-{nombre-participante}`
   - Haga clic en **Guardar reglas**

**¿Por qué esta configuración?** Siguiendo el principio de mínimo privilegio, las instancias web solo deben aceptar tráfico del ALB, no directamente de internet. Esto mejora la seguridad al crear una arquitectura en capas donde solo el ALB es accesible públicamente.

**✓ Verificación**: Confirme que el Security Group permite tráfico HTTP en el puerto 80 SOLO desde `alb-sg-{nombre-participante}` (NO desde 0.0.0.0/0).

---

⚠️ **IMPORTANTE - Verificación del Security Group RDS**: También debe verificar que el Security Group de RDS está configurado correctamente para permitir conexiones desde las instancias web:

1. En **EC2** > **Grupos de seguridad**, busque y seleccione `rds-sg-{nombre-participante}`
2. En la pestaña **Reglas de entrada**, verifique que tiene:
   - **Tipo**: MySQL/Aurora
   - **Puerto**: 3306
   - **Origen**: El Security Group `web-sg-{nombre-participante}` (debe aparecer el ID del Security Group, no una IP)

3. Si NO tiene esta regla, haga clic en **Editar reglas de entrada** y agréguela:
   - Haga clic en **Agregar regla**
   - **Tipo**: MySQL/Aurora
   - **Origen**: Busque y seleccione `web-sg-{nombre-participante}`
   - Haga clic en **Guardar reglas**

⚠️ **Advertencia**: Si esta regla no existe, la aplicación no podrá conectarse a RDS y el formulario no funcionará correctamente.

**✓ Verificación**: Confirme que el Security Group de RDS permite conexiones MySQL/Aurora (puerto 3306) desde el Security Group `web-sg-{nombre-participante}`.

---

1. En la página **Especificar detalles de la pila**:
   - **Nombre de la pila**: `stack-web-{nombre-participante}`

2. En la sección **Parámetros**, ingrese los siguientes valores:

   - **DBEndpoint**: Pegue el endpoint de RDS que anotó en el Lab 2.2
     - Ejemplo: `rds-mysql-{nombre-participante}.xxxxxxxxxx.us-east-1.rds.amazonaws.com`
   
   - **DBUser**: Ingrese el usuario maestro de RDS que configuró en el Lab 2.2
     - Ejemplo: `admin`
   
   - **DBPassword**: Ingrese la contraseña de RDS que configuró en el Lab 2.2
   
   - **SecurityGroupId**: Ingrese el ID del Security Group `web-sg-{nombre-participante}` que creó en el Lab 2.2
     - Ejemplo: `sg-0123456789abcdef0`
     - Para encontrarlo: Vaya a EC2 > Grupos de seguridad > Busque su Security Group > Copie el ID
     - **Nota**: Este es el Security Group para las instancias EC2, NO el del ALB. Las instancias solo aceptarán tráfico del ALB gracias a la configuración de Security Groups en capas.
   
   - **TargetGroupArn**: Pegue el ARN del Target Group que copió en el Paso 5
   
   - **VPCId**: Ingrese el ID de la VPC compartida del workshop
     - Para encontrarlo: Vaya a VPC > Sus VPC > Copie el ID de la VPC del workshop
   
   - **SubnetIds**: Ingrese los IDs de las subredes públicas separados por comas (sin espacios)
     - Ejemplo: `subnet-0123456789abcdef0,subnet-0fedcba9876543210`
     - Para encontrarlos: Vaya a VPC > Subredes > Filtre por subredes públicas > Copie los IDs

3. Haga clic en **Siguiente**

4. En la página **Configurar opciones de pila**:
   - Deje todas las opciones por defecto
   - Haga clic en **Siguiente**

5. En la página **Revisar**:
   - Revise todos los parámetros para asegurarse de que son correctos
   - Marque la casilla **Reconozco que AWS CloudFormation podría crear recursos de IAM**
   - Haga clic en **Enviar**

**✓ Verificación**: Confirme que todos los parámetros están correctos antes de enviar, especialmente el endpoint de RDS y las credenciales.

### Paso 9: Esperar a CREATE_COMPLETE

⏱️ **Nota**: La creación de la pila puede tardar 5-10 minutos. CloudFormation creará automáticamente:
- Un Launch Template con la configuración de las instancias
- Un Auto Scaling Group con capacidad mínima 2, deseada 2, máxima 4
- Las instancias EC2 se lanzarán automáticamente y se registrarán en el Target Group

1. En la página de CloudFormation, observe el estado de la pila:
   - Estado inicial: **CREATE_IN_PROGRESS**
   - Estado final esperado: **CREATE_COMPLETE**

2. Haga clic en la pestaña **Eventos** para ver el progreso de la creación de recursos

3. Si la pila falla y muestra **ROLLBACK_COMPLETE**:
   - Revise los eventos para identificar el recurso que causó el error
   - Consulte la sección de Solución de Problemas al final de este documento
   - Elimine la pila fallida y vuelva a crearla con los parámetros corregidos

**✓ Verificación**: Confirme que el estado de la pila es **CREATE_COMPLETE** (verde).

### Paso 10: Verificar Recursos Creados

1. Haga clic en la pestaña **Recursos** de la pila
2. Verifique que se crearon los siguientes recursos:
   - **LaunchTemplate**: Plantilla de lanzamiento con la configuración de las instancias
   - **AutoScalingGroup**: Grupo de Auto Scaling que gestiona las instancias

3. Haga clic en el enlace del **AutoScalingGroup** para ver sus detalles

4. En la consola de Auto Scaling:
   - Verifique que la **Capacidad deseada** es **2**
   - Verifique que la **Capacidad mínima** es **2**
   - Verifique que la **Capacidad máxima** es **4**

5. Haga clic en la pestaña **Actividad de instancia**:
   - Verifique que hay 2 instancias en estado **Successful** o **InService**

⏱️ **Nota**: Las instancias pueden tardar 3-5 minutos adicionales en pasar las comprobaciones de estado del Target Group después de lanzarse.

**✓ Verificación**: Confirme que:
- El Auto Scaling Group tiene 2 instancias en ejecución
- Las instancias están en estado "InService"
- El Launch Template está asociado al Auto Scaling Group

### Paso 10.5: Verificar Estado del Target Group

Antes de intentar acceder a la aplicación web, es fundamental verificar que las instancias del Auto Scaling Group hayan pasado las comprobaciones de estado (health checks) del Target Group. Si las instancias no están en estado "healthy", el ALB no podrá enrutar el tráfico hacia ellas.

1. En la consola de EC2, en el panel de navegación de la izquierda, haga clic en **Grupos de destino**
2. Seleccione su Target Group `tg-web-{nombre-participante}`
3. Haga clic en la pestaña **Destinos**
4. Observe el estado de las instancias registradas:
   - **Estado inicial**: Las instancias aparecerán como **initial** (inicial)
   - **Estado en progreso**: Cambiarán a **unhealthy** (no saludable) mientras se ejecuta el User Data
   - **Estado final esperado**: Deben cambiar a **healthy** (saludable)

⏱️ **Nota**: Las instancias pueden tardar 3-5 minutos en pasar los health checks después de lanzarse. Esto es normal porque el User Data debe:
- Instalar Apache y PHP
- Crear los archivos de la aplicación web
- Iniciar el servidor web Apache
- Responder correctamente a las comprobaciones de estado del ALB

5. Espere hasta que al menos 1 instancia muestre el estado **healthy** antes de continuar al siguiente paso

**✓ Verificación**: Confirme que al menos 1 instancia muestra estado "healthy" en la columna **Estado** antes de continuar. Si después de 10 minutos ninguna instancia está "healthy", consulte la sección de Solución de Problemas.

---

## PARTE C - VERIFICACIÓN Y PRUEBAS

### Paso 11: Acceder a la Aplicación Web

1. Regrese a la consola de EC2
2. En el panel de navegación de la izquierda, haga clic en **Balanceadores de carga**
3. Seleccione su ALB `alb-web-{nombre-participante}`
4. En la pestaña **Descripción**, copie el **Nombre de DNS**
   - Ejemplo: `alb-web-{nombre-participante}-1234567890.us-east-1.elb.amazonaws.com`

5. Abra una nueva pestaña en su navegador web
6. Pegue el nombre de DNS del ALB en la barra de direcciones
7. Presione Enter

⏱️ **Nota**: Si recibe un error de conexión, verifique que completó el Paso 10.5 y que al menos una instancia está en estado "healthy" en el Target Group. Las instancias deben pasar las comprobaciones de estado antes de que el ALB pueda enrutar el tráfico.

**✓ Verificación**: Debe ver la página web con el título "Workshop AWS - Aplicación de Prueba" y un formulario de entrada.

### Paso 12: Probar Formulario de Entrada

1. En la aplicación web, complete el formulario con los siguientes datos:
   - **Nombre**: Ingrese su nombre
   - **Email**: Ingrese un email de prueba
   - **Mensaje**: Ingrese un mensaje de prueba

2. Haga clic en el botón **Enviar**

3. La página se recargará y debe mostrar un mensaje de confirmación

**✓ Verificación**: Debe ver el mensaje "Mensaje guardado exitosamente" o similar.

### Paso 13: Verificar Escritura y Lectura en RDS

1. Desplácese hacia abajo en la página web
2. Debe ver una tabla con los registros almacenados en la base de datos RDS
3. Verifique que su registro aparece en la tabla con:
   - ID (número auto-incrementado)
   - Nombre que ingresó
   - Email que ingresó
   - Mensaje que ingresó
   - Fecha y hora actual

4. Pruebe agregar varios registros más para verificar que la aplicación funciona correctamente

5. Actualice la página (F5) para confirmar que los datos persisten en la base de datos

**✓ Verificación**: Confirme que:
- Los registros se guardan correctamente en RDS
- La tabla muestra todos los registros ingresados
- Los datos persisten después de actualizar la página
- La aplicación web se conecta exitosamente a la base de datos RDS Multi-AZ

---

## PARTE D - MONITOREO Y ESCALADO

### Paso 14: Crear Alarma de CloudWatch

CloudWatch es el servicio de monitoreo de AWS que recopila métricas de rendimiento de sus recursos. Las alarmas de CloudWatch pueden activar acciones automáticas, como escalar el número de instancias, cuando se superan umbrales definidos.

1. En la barra de búsqueda global (parte superior), escriba **CloudWatch** y haga clic en el servicio
2. En el panel de navegación de la izquierda, haga clic en **Alarmas**
3. Haga clic en el botón naranja **Crear alarma**
4. Haga clic en **Seleccionar métrica**

### Paso 15: Configurar Métrica CPUUtilization del ASG

1. En la página de selección de métricas:
   - Haga clic en **EC2**
   - Haga clic en **Por grupo de Auto Scaling**
   - Busque su Auto Scaling Group (el nombre comienza con `stack-web-{nombre-participante}`)
   - Marque la casilla de la métrica **CPUUtilization** para su ASG
   - Haga clic en **Seleccionar métrica**

2. En la página **Especificar métrica y condiciones**:
   - **Estadística**: Seleccione **Promedio**
   - **Período**: Seleccione **5 minutos**

### Paso 16: Establecer Umbral de Alarma

1. En la sección **Condiciones**:
   - **Tipo de umbral**: Seleccione **Estático**
   - **Siempre que CPUUtilization sea...**: Seleccione **Mayor/Igual**
   - **que...**: Ingrese **70**

2. Haga clic en **Siguiente**

3. En la página **Configurar acciones**:
   - En **Estado de alarma**: Seleccione **En alarma**
   - En **Seleccionar un tema de SNS**: Seleccione **Crear tema nuevo** (opcional)
   - O seleccione **Quitar** si no desea notificaciones por email
   - Haga clic en **Siguiente**

4. En la página **Agregar nombre y descripción**:
   - **Nombre de la alarma**: `alarm-cpu-asg-{nombre-participante}`
   - **Descripción**: `Alarma cuando el CPU del ASG supera 70% durante 5 minutos`
   - Haga clic en **Siguiente**

5. En la página **Vista previa y crear**:
   - Revise la configuración
   - Haga clic en **Crear alarma**

**✓ Verificación**: Confirme que la alarma se creó exitosamente y está en estado "Datos insuficientes" (esto es normal al inicio).

### Paso 17: Configurar Política de Escalado

Un Auto Scaling Group puede ajustar automáticamente el número de instancias según la demanda. Las políticas de escalado definen cuándo y cómo escalar (agregar o eliminar instancias).

1. Regrese a la consola de EC2
2. En el panel de navegación de la izquierda, desplácese hacia abajo y haga clic en **Grupos de Auto Scaling**
3. Seleccione su Auto Scaling Group (el nombre comienza con `stack-web-{nombre-participante}`)
4. Haga clic en la pestaña **Escalado automático**
5. En la sección **Políticas de escalado dinámico**, haga clic en **Crear política de escalado dinámico**

6. Configure la política:
   - **Tipo de política**: Seleccione **Escalado de seguimiento de destino**
   - **Nombre de la política de escalado**: `policy-cpu-target-{nombre-participante}`
   - **Tipo de métrica**: Seleccione **Utilización promedio de CPU**
   - **Valor objetivo**: Ingrese **70**
   - **Tiempo de preparación de instancias**: **300** segundos (5 minutos)

7. Haga clic en **Crear**

**✓ Verificación**: Confirme que la política de escalado se creó exitosamente y está activa.

**Explicación**: Esta política de escalado de seguimiento de destino mantendrá automáticamente la utilización promedio de CPU del Auto Scaling Group alrededor del 70%. Si el CPU supera este valor, el ASG lanzará instancias adicionales (hasta el máximo de 4). Si el CPU disminuye, el ASG terminará instancias (hasta el mínimo de 2).

---

## Resumen del Laboratorio

¡Felicitaciones! Has completado el Laboratorio 2.3 de Elasticidad y Alta Disponibilidad Integrada.

En este laboratorio has:

- Configurado un Application Load Balancer (ALB) que distribuye tráfico entre múltiples instancias en diferentes zonas de disponibilidad
- Utilizado CloudFormation para desplegar infraestructura como código, automatizando la creación del Auto Scaling Group y Launch Template
- Desplegado una aplicación web que se conecta a la base de datos RDS Multi-AZ para almacenar y recuperar datos
- Configurado alarmas de CloudWatch para monitorear el rendimiento del Auto Scaling Group
- Implementado políticas de escalado automático que ajustan la capacidad según la demanda de CPU

Esta arquitectura representa un patrón común en aplicaciones web modernas que requieren:
- **Alta disponibilidad**: El ALB distribuye tráfico entre múltiples instancias en diferentes zonas de disponibilidad
- **Elasticidad**: El Auto Scaling Group ajusta automáticamente el número de instancias según la demanda
- **Automatización**: CloudFormation permite desplegar y gestionar la infraestructura como código
- **Monitoreo**: CloudWatch proporciona visibilidad del rendimiento y activa acciones automáticas

## Solución de Problemas

Si encuentra dificultades durante este laboratorio, consulte la [Guía de Solución de Problemas](../TROUBLESHOOTING.md) que contiene soluciones a errores comunes.

**Errores que requieren asistencia del instructor:**
- Errores de permisos IAM al crear recursos
- Errores de límites de cuota de AWS
- Problemas con recursos compartidos de la VPC

## Gestión del Ciclo de Vida de Recursos

⚠️ **Importante**: Este es el último laboratorio del Día 2 del workshop.

**Recursos creados en este laboratorio:**
- Application Load Balancer: `alb-web-{nombre-participante}`
- Target Group: `tg-web-{nombre-participante}`
- Pila de CloudFormation: `stack-web-{nombre-participante}` (incluye Auto Scaling Group y Launch Template)
- Alarma de CloudWatch: `alarm-cpu-asg-{nombre-participante}`
- Política de escalado del ASG

**Opciones al finalizar el workshop:**

1. **Mantener los recursos activos** (si desea seguir experimentando):
   - Los recursos seguirán generando costos mientras estén activos
   - Puede acceder a la aplicación web en cualquier momento usando el DNS del ALB

2. **Eliminar los recursos** (recomendado al finalizar):
   - Consulte la [Guía de Limpieza de Recursos](../limpieza/README.md) para instrucciones detalladas
   - La eliminación debe hacerse en orden específico para evitar errores de dependencias

⚠️ **Recursos compartidos - NO modificar ni eliminar:**
- VPC del workshop (proporcionada por el instructor)
- Internet Gateway (proporcionado por el instructor)
- Subredes de respaldo (proporcionadas por el instructor)
- Cualquier recurso que no tenga su sufijo de participante `{nombre-participante}`
