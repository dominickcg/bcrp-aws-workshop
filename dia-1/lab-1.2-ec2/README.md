# 💻 Laboratorio 1.2 - Despliegue de EC2

## Objetivo

En este laboratorio aprenderás a desplegar una instancia EC2 (Elastic Compute Cloud) con un servidor web Apache configurado automáticamente. Crearás un grupo de seguridad para controlar el tráfico de red, generarás un par de claves para acceso seguro, y lanzarás una instancia que servirá contenido HTTP en tu subred pública.

**Duración estimada:** 50 minutos

## Archivos de Soporte

Este laboratorio utiliza el siguiente archivo de soporte:

- **[`user-data.sh`](./user-data.sh)** - Script de inicialización que instala y configura Apache automáticamente al lanzar la instancia EC2

---

## Paso 1: Verificación de Región

**⏱️ Tiempo estimado: 2 minutos**

Antes de comenzar, es fundamental verificar que estás trabajando en la región correcta.

1. En la esquina superior derecha de la consola de AWS, verifica que la región seleccionada sea **US East (N. Virginia) us-east-1**
2. Si no es la región correcta, haz clic en el nombre de la región y selecciona **US East (N. Virginia)**

✅ **Checkpoint:** La región mostrada en la esquina superior derecha debe ser "N. Virginia"

---

## Paso 2: Crear Grupo de Seguridad

**⏱️ Tiempo estimado: 8 minutos**

Un grupo de seguridad actúa como un firewall virtual que controla el tráfico de entrada y salida de tu instancia EC2.

1. En la barra de búsqueda global de AWS (parte superior), escribe **EC2** y selecciona el servicio
2. En el panel de navegación de la izquierda, busca la sección **Red y seguridad** y haz clic en **Grupos de seguridad**
3. Haz clic en el botón naranja **Crear grupo de seguridad** (esquina superior derecha)
4. Configura los siguientes valores:

   **Detalles básicos:**
   - **Nombre del grupo de seguridad:** `Web-Server-SG-{tu-nombre}` (reemplaza `{tu-nombre}` con tu sufijo de participante)
   - **Descripción:** `Security group para servidor web del participante {tu-nombre}`
   - **VPC:** Selecciona la VPC **Lab-VPC** creada por el instructor en el Lab 1.1

   **Reglas de entrada:**
   
   Haz clic en **Agregar regla** y configura la primera regla:
   - **Tipo:** HTTP
   - **Protocolo:** TCP
   - **Intervalo de puertos:** 80
   - **Origen:** Personalizado → `0.0.0.0/0`
   - **Descripción:** `Acceso HTTP desde Internet`

   Haz clic en **Agregar regla** nuevamente para la segunda regla:
   - **Tipo:** SSH
   - **Protocolo:** TCP
   - **Intervalo de puertos:** 22
   - **Origen:** Mi IP (AWS detectará automáticamente tu IP pública)
   - **Descripción:** `Acceso SSH desde mi ubicación`

   **Reglas de salida:**
   - Deja la regla predeterminada que permite todo el tráfico saliente

   **Etiquetas:**
   
   Haz clic en **Agregar nueva etiqueta** dos veces y configura:
   - **Clave:** `Owner` → **Valor:** `{tu-nombre}`
   - **Clave:** `Project` → **Valor:** `Workshop-BCRP`

5. Haz clic en **Crear grupo de seguridad** (botón naranja en la parte inferior)

✅ **Checkpoint:** Debes ver tu nuevo grupo de seguridad en la lista con 2 reglas de entrada (HTTP y SSH)

---

## Paso 3: Crear Par de Claves

**⏱️ Tiempo estimado: 5 minutos**

El par de claves te permitirá conectarte de forma segura a tu instancia EC2 mediante SSH.

1. En el panel de navegación de la izquierda, busca la sección **Red y seguridad** y haz clic en **Pares de claves**
2. Haz clic en el botón naranja **Crear par de claves** (esquina superior derecha)
3. Configura los siguientes valores:
   - **Nombre:** `ec2-keypair-{tu-nombre}` (reemplaza `{tu-nombre}` con tu sufijo de participante)
   - **Tipo de par de claves:** RSA
   - **Formato de archivo de clave privada:** .pem
   
   **Etiquetas:**
   
   Haz clic en **Agregar nueva etiqueta** dos veces y configura:
   - **Clave:** `Owner` → **Valor:** `{tu-nombre}`
   - **Clave:** `Project` → **Valor:** `Workshop-BCRP`

4. Haz clic en **Crear par de claves**
5. El archivo `.pem` se descargará automáticamente a tu computadora. **Guárdalo en un lugar seguro**, lo necesitarás si deseas conectarte por SSH

✅ **Checkpoint:** El archivo `.pem` debe estar descargado en tu carpeta de descargas

---

## Paso 4: Lanzar Instancia EC2

**⏱️ Tiempo estimado: 25 minutos**

Ahora lanzarás una instancia EC2 con Amazon Linux 2023 y un servidor web Apache preconfigurado.

1. En el panel de navegación de la izquierda, haz clic en **Instancias** (bajo la sección **Instancias**)
2. Haz clic en el botón naranja **Lanzar instancias** (esquina superior derecha)

### 4.1 Nombre y etiquetas

- **Nombre:** `ec2-webserver-{tu-nombre}` (reemplaza `{tu-nombre}` con tu sufijo de participante)
- Haz clic en **Agregar etiquetas adicionales** y configura:
  - **Clave:** `Owner` → **Valor:** `{tu-nombre}`
  - **Clave:** `Project` → **Valor:** `Workshop-BCRP`
  - **Tipos de recursos:** Asegúrate de que estén seleccionados **Instancias** y **Volúmenes**

### 4.2 Imágenes de aplicaciones y sistemas operativos (Amazon Machine Image)

- En la sección **Inicio rápido**, selecciona **Amazon Linux**
- Verifica que esté seleccionada la AMI **Amazon Linux 2023 AMI**
- Arquitectura: **64 bits (x86)**

### 4.3 Tipo de instancia

- Selecciona **t3.micro** (si no está disponible, selecciona **t2.micro**)

### 4.4 Par de claves (inicio de sesión)

- Selecciona el par de claves que creaste en el Paso 3: `ec2-keypair-{tu-nombre}`

### 4.5 Configuración de red

Haz clic en **Editar** en la sección de configuración de red:

- **VPC:** Selecciona **Lab-VPC** (la VPC creada por el instructor)
- **Subred:** Selecciona tu subred pública creada en el Lab 1.1: `Subnet-Public-{tu-nombre}` (debe estar en la zona de disponibilidad **us-east-1a**)
- **Asignar IP pública automáticamente:** Habilitar
- **Firewall (grupos de seguridad):** Seleccionar grupo de seguridad existente
- **Grupos de seguridad comunes:** Selecciona el grupo `Web-Server-SG-{tu-nombre}` que creaste en el Paso 2

### 4.6 Configurar almacenamiento

- Deja la configuración predeterminada: **8 GiB gp3** (volumen raíz)

### 4.7 Detalles avanzados

Desplázate hacia abajo y expande la sección **Detalles avanzados**:

- Desplázate hasta el final de la sección hasta encontrar **Datos de usuario**
- En el campo de texto, copia y pega el contenido del script User Data

**📄 Archivo de soporte:** El script completo está disponible en [`user-data.sh`](./user-data.sh) en esta misma carpeta del laboratorio.

**Contenido del script User Data:**

```bash
#!/bin/bash
dnf update -y
dnf install -y httpd
systemctl start httpd
systemctl enable httpd
HOSTNAME=$(hostname)
echo "<html><head><title>Workshop AWS</title></head><body><h1>Bienvenido al Workshop AWS</h1><p>Servidor: $HOSTNAME</p></body></html>" > /var/www/html/index.html
```

### 4.8 Resumen

- Revisa el resumen en el panel derecho
- Verifica que el número de instancias sea **1**
- Haz clic en el botón naranja **Lanzar instancia**

✅ **Checkpoint:** Debes ver un mensaje de éxito "Se lanzó correctamente la instancia"

---

## Paso 5: Verificar Estado de la Instancia

**⏱️ Tiempo estimado: 5 minutos**

Ahora esperarás a que la instancia complete sus verificaciones de estado.

1. Haz clic en el enlace del ID de instancia en el mensaje de éxito (o navega a **Instancias** en el panel izquierdo)
2. Selecciona tu instancia `ec2-webserver-{tu-nombre}`
3. Observa la columna **Estado de la instancia**. Debe cambiar de **Pendiente** a **En ejecución** (esto toma aproximadamente 1-2 minutos)
4. Una vez que el estado sea **En ejecución**, observa la columna **Verificaciones de estado**
5. Espera hasta que muestre **2/2 verificaciones aprobadas** (esto puede tomar 2-3 minutos adicionales)

✅ **Checkpoint:** La instancia debe mostrar:
- **Estado de la instancia:** En ejecución (círculo verde)
- **Verificaciones de estado:** 2/2 verificaciones aprobadas (marca verde)

---

## Paso 6: Probar Conectividad HTTP

**⏱️ Tiempo estimado: 3 minutos**

Finalmente, verificarás que el servidor web Apache está funcionando correctamente.

1. Con tu instancia seleccionada, busca en el panel inferior la pestaña **Detalles**
2. Localiza el campo **Dirección IPv4 pública** y copia la dirección IP
3. Abre una nueva pestaña en tu navegador web
4. Pega la dirección IP en la barra de direcciones (asegúrate de usar `http://` y no `https://`)
   
   Ejemplo: `http://54.123.45.67`

5. Presiona Enter

✅ **Checkpoint:** Debes ver una página web con el mensaje:
- **Título:** "Bienvenido al Workshop AWS"
- **Contenido:** "Servidor: [nombre-del-host]"

---

## Solución de Problemas

Si encuentras problemas durante este laboratorio, consulta la [Guía de Troubleshooting del Día 1](../TROUBLESHOOTING.md) para soluciones a errores comunes.

Los problemas más frecuentes en este laboratorio incluyen:
- No poder conectarse por SSH a la instancia
- No poder acceder al servidor web por HTTP
- Comprobaciones de estado (Status Checks) que fallan
- Errores al lanzar la instancia (límites de cuota)
- Problemas con el Security Group

Para soluciones detalladas, consulta la sección **Lab 1.2 - EC2** en la [Guía de Troubleshooting](../TROUBLESHOOTING.md).

---

## Ciclo de Vida de Recursos

**IMPORTANTE:** Los siguientes recursos deben **mantenerse activos** para el Día 2 del workshop:

- ✅ Instancia EC2: `ec2-webserver-{tu-nombre}`
- ✅ Grupo de seguridad: `Web-Server-SG-{tu-nombre}`
- ✅ Par de claves: `ec2-keypair-{tu-nombre}`
- ✅ Volumen EBS raíz (adjunto a la instancia)

**NO ELIMINES** estos recursos al finalizar el laboratorio de hoy. Los necesitarás para las actividades del Día 2.

Si deseas detener temporalmente la instancia para ahorrar costos (opcional):
1. Selecciona la instancia
2. Haz clic en **Estado de la instancia** → **Detener instancia**
3. Para reanudarla mañana: **Estado de la instancia** → **Iniciar instancia**

---

## Resumen

¡Felicitaciones! Has completado el Laboratorio 1.2. En este laboratorio has:

- ✅ Creado un grupo de seguridad con reglas para HTTP y SSH
- ✅ Generado un par de claves para acceso seguro
- ✅ Lanzado una instancia EC2 con Amazon Linux 2023
- ✅ Configurado un servidor web Apache mediante User Data
- ✅ Verificado la conectividad HTTP a tu servidor web

**Tiempo total:** ~50 minutos

---

[← Anterior: Lab 1.1 - VPC](../lab-1.1-vpc/README.md) | [Volver al Día 1](../README.md) | [Siguiente: Lab 1.3 - Almacenamiento →](../lab-1.3-storage/README.md)
