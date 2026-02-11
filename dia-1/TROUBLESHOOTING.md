# Troubleshooting - Día 1

## Introducción

Este documento contiene soluciones a errores comunes que pueden ocurrir durante los laboratorios del Día 1 del Workshop AWS para el BCRP. Si encuentras un problema durante cualquiera de los laboratorios, consulta la sección correspondiente para encontrar la solución.

Si el problema persiste después de intentar las soluciones sugeridas, notifica al instructor inmediatamente.

---

## Lab 1.1 - VPC y Subredes

### Problema: CIDR overlaps
**Descripción:** Al intentar crear una subred, aparece el error "CIDR overlaps with existing subnet".

**Solución:**
- Verifica el número único (X) que te asignó el instructor
- Asegúrate de usar el CIDR correcto:
  - Subred pública: `10.0.{X*2}.0/24`
  - Subred privada: `10.0.{X*2+1}.0/24`
- Ejemplo: Si tu número es 5, usa 10.0.10.0/24 (pública) y 10.0.11.0/24 (privada)

### Problema: Route already exists
**Descripción:** Al agregar la ruta 0.0.0.0/0 hacia el IGW, aparece el error "Route already exists".

**Solución:**
- Verifica que estás editando la tabla de ruteo correcta (la que creaste con tu sufijo de participante)
- No intentes modificar la tabla de ruteo principal (Main)
- Si la ruta ya existe en tu tabla personalizada, verifica que apunta al IGW correcto (Lab-IGW)

### Problema: No se puede crear subred
**Descripción:** La creación de la subred falla sin mensaje de error claro.

**Solución:**
- Verifica que estás en la región correcta designada por el instructor
- Confirma que estás seleccionando la zona de disponibilidad correcta (AZ-a)
- Asegúrate de que el CIDR está dentro del rango de la VPC (10.0.0.0/16)
- Verifica que no hay conflictos con otras subredes ya creadas

---

## Lab 1.2 - EC2

### Problema: No puedo conectarme por SSH
**Descripción:** No puedo establecer conexión SSH a la instancia EC2.

**Solución:**
- Verifica que el Security Group tiene una regla de entrada para el puerto 22 (SSH)
- Confirma que la regla SSH permite tu dirección IP específica (no 0.0.0.0/0)
- Asegúrate de que la instancia tiene una IP pública asignada
- Verifica que la instancia está en estado "Running"
- Confirma que las comprobaciones de estado muestran 2/2 Passed

### Problema: No puedo acceder al servidor web por HTTP
**Descripción:** Al navegar a la IP pública de la instancia, no se muestra la página web.

**Solución:**
- Verifica que el Security Group tiene una regla de entrada para el puerto 80 (HTTP)
- Confirma que la regla HTTP permite tráfico desde 0.0.0.0/0
- Asegúrate de que el Security Group está asociado a la instancia EC2
- Verifica que las comprobaciones de estado muestran 2/2 Passed
- Espera 2-3 minutos después del lanzamiento para que el User Data se ejecute completamente
- Usa `http://` en la URL, no `https://`

### Problema: Status Checks failed
**Descripción:** Las comprobaciones de estado no pasan (0/2 o 1/2).

**Solución:**
- Espera 3-5 minutos adicionales, las comprobaciones pueden tardar
- Verifica que el script de User Data no tiene errores de sintaxis
- Si persiste después de 10 minutos, notifica al instructor

### Problema: Instance launch failed
**Descripción:** El lanzamiento de la instancia falla con error de cuota o límite.

**Solución:**
- **NO intentes soluciones alternativas**
- Notifica al instructor inmediatamente
- El instructor ajustará los límites de cuota en la cuenta

---

## Lab 1.3 - Almacenamiento

### Problema: Access Denied en S3
**Descripción:** Al intentar acceder al sitio web estático, aparece "Access Denied".

**Solución:**
- Verifica que desactivaste "Block all public access" en la configuración del bucket
- Confirma que aplicaste la Bucket Policy correctamente
- Asegúrate de reemplazar `NOMBRE-DEL-BUCKET` en la política con el nombre real de tu bucket
- Verifica que la política JSON no tiene errores de sintaxis
- Usa el endpoint de "Static website hosting", no la URL de objeto S3

### Problema: Device busy al montar volumen EBS
**Descripción:** Al intentar formatear o montar el volumen, aparece "device is busy".

**Solución:**
- Verifica que no hay procesos usando el dispositivo con: `lsof /dev/xvdf`
- Asegúrate de que el volumen está adjunto a la instancia (estado "attached")
- Usa el nombre de dispositivo correcto mostrado por `lsblk`
- Si persiste, desadjunta y vuelve a adjuntar el volumen

### Problema: CSS/JS no cargan en el sitio S3
**Descripción:** El sitio web se muestra sin estilos o el JavaScript no funciona.

**Solución:**
- Verifica que mantuviste la estructura de carpetas al cargar los archivos:
  - `/css/styles.css`
  - `/js/main.js`
  - `/assets/` (con imágenes)
- Asegúrate de cargar todos los archivos en una sola operación para preservar las rutas relativas
- Verifica que los archivos HTML referencian correctamente las rutas (ej: `css/styles.css`, no `/css/styles.css`)
- Usa el navegador en modo incógnito para evitar problemas de caché

### Problema: error.html no se muestra
**Descripción:** Al acceder a una ruta inexistente, no se muestra la página de error personalizada.

**Solución:**
- Verifica que habilitaste "Static website hosting" en la configuración del bucket
- Confirma que especificaste `error.html` como documento de error
- Asegúrate de que el archivo `error.html` está en la raíz del bucket (no en una carpeta)
- Usa el endpoint de "Static website hosting" para probar

---

## Errores Generales

### Problema: Error de permisos
**Descripción:** Aparece un error indicando que no tienes permisos para realizar una acción.

**Solución:**
- **NO intentes soluciones alternativas**
- Notifica al instructor inmediatamente
- El instructor verificará y ajustará los permisos de tu usuario IAM

### Problema: Error de límite de cuota
**Descripción:** No puedes crear un recurso porque se alcanzó el límite de cuota.

**Solución:**
- **NO intentes soluciones alternativas**
- Notifica al instructor inmediatamente
- El instructor solicitará un aumento de cuota o liberará recursos

### Problema: Región incorrecta
**Descripción:** No encuentras los recursos que creaste o el instructor creó.

**Solución:**
- Verifica que estás en la región correcta designada por el instructor
- Busca el selector de región en la esquina superior derecha de la consola
- Cambia a la región correcta (ejemplo: US East (N. Virginia) us-east-1)
- Los recursos de AWS son específicos por región

---

## Contacto con el Instructor

Si después de intentar las soluciones anteriores el problema persiste:

1. Anota el mensaje de error exacto (toma una captura de pantalla si es posible)
2. Anota qué paso del laboratorio estabas realizando
3. Notifica al instructor con esta información
4. No continúes al siguiente paso hasta resolver el problema

El instructor está disponible para ayudarte durante todo el workshop.
