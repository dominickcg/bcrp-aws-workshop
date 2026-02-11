# 🧹 Limpieza Opcional - Día 1

## ⚠️ IMPORTANTE: Esta sección es OPCIONAL

**Esta guía de limpieza es completamente OPCIONAL.** Los recursos creados durante el Día 1 están diseñados para ser utilizados en los días siguientes del workshop. Solo debes seguir estas instrucciones si:

- El instructor te indica específicamente que elimines recursos
- Has decidido no continuar con el workshop y deseas limpiar tu cuenta AWS
- Deseas practicar el proceso de eliminación de recursos

**Si planeas continuar con el Día 2 del workshop, NO sigas estas instrucciones.**

---

## Recursos a Mantener para el Día 2

Los siguientes recursos **DEBEN permanecer activos** para las actividades del Día 2:

| Recurso | Nombre | Razón |
|---------|--------|-------|
| **VPC** | Lab-VPC | Recurso compartido del instructor - NO ELIMINAR |
| **Internet Gateway** | Lab-IGW | Recurso compartido del instructor - NO ELIMINAR |
| **Subred Pública** | Subnet-Public-{tu-nombre} | Necesaria para laboratorios del Día 2 |
| **Subred Privada** | Subnet-Private-{tu-nombre} | Necesaria para laboratorios del Día 2 |
| **Tabla de Ruteo** | RTB-Public-{tu-nombre} | Necesaria para conectividad de red |
| **Grupo de Seguridad** | Web-Server-SG-{tu-nombre} | Necesario para instancia EC2 |
| **Instancia EC2** | ec2-webserver-{tu-nombre} | Necesaria para laboratorios del Día 2 |
| **Par de Claves** | ec2-keypair-{tu-nombre} | Necesario para acceso SSH |
| **Volumen EBS** | ebs-data-{tu-nombre} | Necesario para laboratorios del Día 2 |
| **Volumen EBS Raíz** | (adjunto a EC2) | Necesario para funcionamiento de EC2 |

---

## Recursos que Pueden Eliminarse (Opcional)

Los siguientes recursos pueden eliminarse de forma segura si lo deseas, ya que no son necesarios para el Día 2:

| Recurso | Nombre | Impacto de Eliminación |
|---------|--------|------------------------|
| **Bucket S3** | s3-sitio-{tu-nombre}-* | Bajo - El sitio web estático no se usará en Día 2 |
| **Objetos S3** | Archivos dentro del bucket | Bajo - Se eliminan automáticamente con el bucket |

**Nota:** La eliminación del bucket S3 es completamente opcional. Puedes mantenerlo si deseas conservar tu sitio web estático como referencia.

---

## Pasos para Eliminación Segura (Solo si es Necesario)

Si decides eliminar recursos, **DEBES seguir este orden** para respetar las dependencias entre recursos. Eliminar en orden incorrecto puede causar errores.

### Opción 1: Eliminar Solo el Bucket S3 (Recomendado)

Si solo deseas eliminar el bucket S3 para liberar espacio:

#### Paso 1: Vaciar el Bucket S3

**⏱️ Tiempo estimado: 3 minutos**

1. En la barra de búsqueda global de AWS, escribe **S3** y selecciona el servicio
2. Localiza tu bucket `s3-sitio-{tu-nombre}-*` en la lista
3. Selecciona el bucket haciendo clic en el círculo a la izquierda del nombre (NO hagas clic en el nombre)
4. Haz clic en el botón **Vaciar** en la parte superior
5. En la ventana de confirmación, escribe `vaciar permanentemente` en el campo de texto
6. Haz clic en **Vaciar**
7. Espera a que se complete el proceso (verás un mensaje de éxito)

✅ **Checkpoint:** El bucket debe mostrar "0 objetos" en la columna de objetos

#### Paso 2: Eliminar el Bucket S3

**⏱️ Tiempo estimado: 2 minutos**

1. Con el bucket aún seleccionado, haz clic en el botón **Eliminar** en la parte superior
2. En la ventana de confirmación, escribe el nombre completo de tu bucket
3. Haz clic en **Eliminar bucket**

✅ **Checkpoint:** El bucket ya no debe aparecer en la lista de buckets

---

### Opción 2: Eliminación Completa (Solo si NO Continúas con el Workshop)

⚠️ **ADVERTENCIA CRÍTICA:** Solo sigue estos pasos si estás completamente seguro de que NO continuarás con el Día 2 del workshop. Esta acción eliminará TODOS tus recursos del Día 1.

#### Paso 1: Eliminar Bucket S3 (si existe)

Sigue los pasos de la **Opción 1** anterior.

#### Paso 2: Desmontar y Eliminar Volumen EBS Adicional

**⏱️ Tiempo estimado: 8 minutos**

1. **Conectarse a la instancia EC2 por SSH** (si tienes acceso):
   ```bash
   # Desmontar el volumen
   sudo umount /mnt/data_logs
   
   # Editar /etc/fstab para eliminar la entrada del volumen
   sudo nano /etc/fstab
   # Elimina la línea que contiene /mnt/data_logs
   # Guarda y cierra (Ctrl+X, luego Y, luego Enter)
   ```

2. **En la Consola de AWS:**
   - Ve al servicio **EC2**
   - En el panel de navegación izquierdo, haz clic en **Volúmenes** (bajo la sección **Elastic Block Store**)
   - Localiza el volumen `ebs-data-{tu-nombre}` (1 GiB, gp3)
   - Selecciona el volumen
   - Haz clic en **Acciones** → **Desasociar volumen**
   - Confirma la desasociación
   - Espera hasta que el estado cambie a **Disponible** (1-2 minutos)
   - Con el volumen aún seleccionado, haz clic en **Acciones** → **Eliminar volumen**
   - Confirma la eliminación

✅ **Checkpoint:** El volumen EBS adicional ya no debe aparecer en la lista

#### Paso 3: Terminar Instancia EC2

**⏱️ Tiempo estimado: 5 minutos**

⚠️ **ADVERTENCIA:** Terminar una instancia es una acción permanente. No podrás recuperar la instancia después.

1. Ve al servicio **EC2**
2. En el panel de navegación izquierdo, haz clic en **Instancias**
3. Selecciona tu instancia `ec2-webserver-{tu-nombre}`
4. Haz clic en **Estado de la instancia** → **Terminar instancia**
5. Confirma la terminación
6. Espera hasta que el estado cambie a **Terminada** (2-3 minutos)

**Nota:** El volumen EBS raíz se eliminará automáticamente cuando la instancia sea terminada (comportamiento predeterminado).

✅ **Checkpoint:** La instancia debe mostrar el estado "Terminada"

#### Paso 4: Eliminar Par de Claves

**⏱️ Tiempo estimado: 2 minutos**

1. En el panel de navegación izquierdo de EC2, haz clic en **Pares de claves** (bajo **Red y seguridad**)
2. Selecciona tu par de claves `ec2-keypair-{tu-nombre}`
3. Haz clic en **Acciones** → **Eliminar**
4. Confirma la eliminación escribiendo `Eliminar` en el campo de texto

✅ **Checkpoint:** El par de claves ya no debe aparecer en la lista

#### Paso 5: Eliminar Grupo de Seguridad

**⏱️ Tiempo estimado: 2 minutos**

1. En el panel de navegación izquierdo de EC2, haz clic en **Grupos de seguridad** (bajo **Red y seguridad**)
2. Selecciona tu grupo de seguridad `Web-Server-SG-{tu-nombre}`
3. Haz clic en **Acciones** → **Eliminar grupos de seguridad**
4. Confirma la eliminación

✅ **Checkpoint:** El grupo de seguridad ya no debe aparecer en la lista

#### Paso 6: Eliminar Tabla de Ruteo Personalizada

**⏱️ Tiempo estimado: 3 minutos**

1. En la barra de búsqueda global de AWS, escribe **VPC** y selecciona el servicio
2. En el panel de navegación izquierdo, haz clic en **Tablas de ruteo**
3. Localiza tu tabla de ruteo `RTB-Public-{tu-nombre}`
4. **Primero, desasociar la subred:**
   - Selecciona la tabla de ruteo
   - En el panel inferior, haz clic en la pestaña **Asociaciones de subred**
   - Selecciona la asociación con tu subred pública
   - Haz clic en **Editar asociaciones de subred**
   - Deselecciona tu subred
   - Haz clic en **Guardar asociaciones**
5. **Luego, eliminar la tabla de ruteo:**
   - Con la tabla de ruteo aún seleccionada, haz clic en **Acciones** → **Eliminar tabla de ruteo**
   - Confirma la eliminación

✅ **Checkpoint:** Tu tabla de ruteo personalizada ya no debe aparecer en la lista

#### Paso 7: Eliminar Subredes

**⏱️ Tiempo estimado: 3 minutos**

1. En el panel de navegación izquierdo de VPC, haz clic en **Subredes**
2. Selecciona tu subred pública `Subnet-Public-{tu-nombre}`
3. Haz clic en **Acciones** → **Eliminar subred**
4. Confirma la eliminación escribiendo `eliminar` en el campo de texto
5. Repite el proceso para tu subred privada `Subnet-Private-{tu-nombre}`

✅ **Checkpoint:** Tus subredes ya no deben aparecer en la lista

---

## ⛔ Recursos que NO Debes Eliminar NUNCA

Los siguientes recursos son **compartidos por todos los participantes** y fueron creados por el instructor. **NUNCA intentes eliminar estos recursos:**

| Recurso | Nombre | Razón |
|---------|--------|-------|
| **VPC** | Lab-VPC | Recurso compartido - Eliminarla afectaría a todos los participantes |
| **Internet Gateway** | Lab-IGW | Recurso compartido - Eliminarlo dejaría sin conectividad a todos |

**Identificación de recursos del instructor:**
- Los recursos del instructor NO tienen tu sufijo de participante en el nombre
- Los recursos del instructor tienen etiquetas diferentes (Owner: Instructor)

**Si eliminas accidentalmente un recurso del instructor:**
1. Notifica inmediatamente al instructor
2. NO intentes recrear el recurso por tu cuenta
3. El instructor coordinará la recuperación del entorno

---

## Verificación Final

Si completaste la eliminación completa (Opción 2), verifica que:

- [ ] El bucket S3 ya no existe
- [ ] El volumen EBS adicional ya no existe
- [ ] La instancia EC2 está en estado "Terminada"
- [ ] El par de claves ya no existe
- [ ] El grupo de seguridad ya no existe
- [ ] La tabla de ruteo personalizada ya no existe
- [ ] Las subredes (pública y privada) ya no existen
- [ ] La VPC **Lab-VPC** AÚN EXISTE (recurso del instructor)
- [ ] El Internet Gateway **Lab-IGW** AÚN EXISTE (recurso del instructor)

---

## Troubleshooting

### Problema: No puedo eliminar el grupo de seguridad

**Causa:** El grupo de seguridad está asociado a una instancia EC2 en ejecución o terminándose.

**Solución:**
1. Asegúrate de que la instancia EC2 esté completamente terminada (estado "Terminada")
2. Espera 2-3 minutos adicionales después de la terminación
3. Intenta eliminar el grupo de seguridad nuevamente

### Problema: No puedo eliminar la tabla de ruteo

**Causa:** La tabla de ruteo aún tiene subredes asociadas.

**Solución:**
1. Selecciona la tabla de ruteo
2. Ve a la pestaña **Asociaciones de subred**
3. Desasocia todas las subredes antes de eliminar la tabla

### Problema: No puedo eliminar una subred

**Causa:** La subred aún tiene recursos activos (instancias EC2, interfaces de red, etc.).

**Solución:**
1. Asegúrate de que todas las instancias EC2 en esa subred estén terminadas
2. Ve a **Interfaces de red** en el servicio EC2 y verifica que no haya interfaces huérfanas en esa subred
3. Elimina cualquier interfaz de red huérfana antes de eliminar la subred

### Problema: No puedo desasociar el volumen EBS

**Causa:** El volumen está en uso por la instancia EC2.

**Solución:**
1. Conéctate por SSH a la instancia
2. Desmonta el volumen: `sudo umount /mnt/data_logs`
3. Verifica que no hay procesos usando el volumen: `lsof /mnt/data_logs`
4. Intenta desasociar nuevamente desde la consola

### Problema: Eliminé accidentalmente un recurso del instructor

**Solución:**
1. **NO ENTRES EN PÁNICO**
2. Notifica inmediatamente al instructor con los siguientes detalles:
   - Qué recurso eliminaste (VPC, IGW, etc.)
   - A qué hora lo eliminaste
   - Tu nombre de participante
3. NO intentes recrear el recurso
4. El instructor coordinará la recuperación

---

## Resumen

Esta guía de limpieza te ha proporcionado:

- ✅ Lista clara de recursos a mantener para el Día 2
- ✅ Lista de recursos que pueden eliminarse de forma segura
- ✅ Pasos ordenados para eliminación respetando dependencias
- ✅ Advertencias sobre recursos compartidos del instructor
- ✅ Soluciones a problemas comunes de eliminación

**Recuerda:** Esta limpieza es **OPCIONAL**. Si continúas con el Día 2, mantén todos los recursos activos.

---

[← Volver al README principal del Día 1](../README.md)
