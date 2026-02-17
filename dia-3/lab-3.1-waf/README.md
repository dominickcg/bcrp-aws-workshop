# 🛡️ Laboratorio 3.1 - Protección Perimetral con AWS WAF

## Índice

- [Descripción General](#descripción-general)
- [Objetivos de Aprendizaje](#objetivos-de-aprendizaje)
- [Duración Estimada](#duración-estimada)
- [Prerequisitos](#prerequisitos)
- [Instrucciones](#instrucciones)
  - [Paso 1: Verificación de Región](#paso-1-verificación-de-región)
  - [Paso 2: Navegar a la Consola de AWS WAF](#paso-2-navegar-a-la-consola-de-aws-waf)
  - [Paso 3: Crear Web ACL](#paso-3-crear-web-acl)
  - [Paso 4: Asociar Web ACL al Application Load Balancer](#paso-4-asociar-web-acl-al-application-load-balancer)
  - [Paso 5: Configurar Acción por Defecto](#paso-5-configurar-acción-por-defecto)
  - [Paso 6: Agregar Reglas Gestionadas](#paso-6-agregar-reglas-gestionadas)
  - [Paso 7: Probar WAF con Tráfico Normal](#paso-7-probar-waf-con-tráfico-normal)
  - [Paso 8: Probar Bloqueo con Inyección SQL](#paso-8-probar-bloqueo-con-inyección-sql)
  - [Paso 9: Revisar Métricas de WAF (Opcional)](#paso-9-revisar-métricas-de-waf-opcional)
- [Resumen del Laboratorio](#resumen-del-laboratorio)
- [Solución de Problemas](#solución-de-problemas)
- [Gestión del Ciclo de Vida de Recursos](#gestión-del-ciclo-de-vida-de-recursos)

## Descripción General

AWS WAF (Web Application Firewall) es un firewall de aplicaciones web que te ayuda a proteger tus aplicaciones contra exploits web comunes que podrían afectar la disponibilidad de la aplicación, comprometer la seguridad o consumir recursos excesivos. A diferencia de los Security Groups que operan en las capas 3 y 4 del modelo OSI (red y transporte), AWS WAF opera en la capa 7 (aplicación), inspeccionando el contenido de las solicitudes HTTP/HTTPS.

En este laboratorio implementarás AWS WAF en el Application Load Balancer que creaste en el Día 2, configurarás reglas gestionadas por AWS para proteger contra ataques comunes, y probarás la efectividad del WAF simulando un ataque de inyección SQL.

## Objetivos de Aprendizaje

Al completar este laboratorio, serás capaz de:

- Crear y configurar una lista de control de acceso web (Web ACL) en AWS WAF
- Asociar un Web ACL a un Application Load Balancer
- Implementar reglas gestionadas de AWS para protección contra amenazas comunes
- Comprender la diferencia entre protección de red (Security Groups) y protección de aplicación (WAF)
- Probar y validar el bloqueo de ataques de inyección SQL

## Duración Estimada

⏱️ **50 minutos**

## Prerequisitos

Para completar este laboratorio necesitas:

- **Application Load Balancer**: Creado en el Laboratorio 2.3 del Día 2
- **ALB en estado activo**: El balanceador debe estar en estado "active" (activo)
- **DNS del ALB**: Necesitarás el nombre DNS del ALB para las pruebas

## Instrucciones

### Paso 1: Verificación de Región

**⏱️ Tiempo estimado: 2 minutos**

1. En la esquina superior derecha de la Consola de AWS, localiza el selector de región
2. Verifica que la región mostrada coincide con la región designada por el instructor
3. Si la región es incorrecta, haz clic en el selector y elige la región correcta

⚠️ **Importante**: AWS WAF debe configurarse en la misma región que tu Application Load Balancer.

### Paso 2: Navegar a la Consola de AWS WAF

**⏱️ Tiempo estimado: 2 minutos**

1. En la barra de búsqueda global de AWS (parte superior), escribe **WAF**
2. Selecciona **WAF y Shield** de los resultados
3. En el panel de navegación de la izquierda, haz clic en **Listas de control de acceso web (ACL web)**

### Paso 3: Crear Web ACL

**⏱️ Tiempo estimado: 8 minutos**

1. Haz clic en el botón naranja **Crear ACL web** en la esquina superior derecha

2. En la página **Describir ACL web y asociar recursos de AWS**:
   - **Nombre**: `waf-web-{nombre-participante}`
   - **Descripción**: `WAF para protección del ALB`
   - **Tipo de recurso**: Selecciona **Regional**
   - **Región**: Verifica que sea la región del workshop
   - Haz clic en **Siguiente**

3. En la página **Asociar recursos de AWS**:
   - Haz clic en **Agregar recursos de AWS**
   - En el cuadro de diálogo, selecciona **Application Load Balancer**
   - Busca y selecciona tu ALB del Lab 2.3 (debe contener tu nombre de participante)
   - Haz clic en **Agregar**
   - Haz clic en **Siguiente**

**✓ Verificación**: Confirme que:
- El nombre del Web ACL incluye tu identificador de participante
- El tipo de recurso es "Regional"
- Tu Application Load Balancer aparece en la lista de recursos asociados

### Paso 4: Asociar Web ACL al Application Load Balancer

**⏱️ Tiempo estimado: 3 minutos**

Este paso ya se completó en el Paso 3 al agregar recursos de AWS. Verifica que tu ALB esté correctamente asociado:

1. En la lista de recursos asociados, confirma que aparece tu Application Load Balancer
2. Verifica que el nombre del ALB contiene tu identificador de participante

**✓ Verificación**: El ALB debe aparecer en la sección "Recursos de AWS asociados" con estado "Asociado".

### Paso 5: Configurar Acción por Defecto

**⏱️ Tiempo estimado: 3 minutos**

1. En la página **Agregar reglas y grupos de reglas**:
   - En la sección **Acción predeterminada para solicitudes que no coinciden con ninguna regla**
   - Selecciona **Permitir** (Allow)
   - Esto permite que el tráfico legítimo pase si no coincide con ninguna regla de bloqueo

2. Haz clic en **Agregar reglas** para comenzar a agregar reglas gestionadas

**Nota educativa**: La acción por defecto "Permitir" es una práctica recomendada. Las reglas específicas bloquearán tráfico malicioso, mientras que el tráfico legítimo que no coincida con ninguna regla será permitido.

### Paso 6: Agregar Reglas Gestionadas

**⏱️ Tiempo estimado: 12 minutos**

Las reglas gestionadas de AWS son conjuntos de reglas predefinidas y mantenidas por el equipo de seguridad de AWS.

1. En la página **Agregar reglas y grupos de reglas**:
   - Haz clic en el botón **Agregar reglas**
   - Selecciona **Agregar grupos de reglas administradas**

2. **Agregar Core Rule Set**:
   - Expande la sección **Grupos de reglas administradas de AWS**
   - Busca **Core rule set** (Conjunto de reglas principales)
   - Marca la casilla junto a **Core rule set**
   - Este conjunto protege contra vulnerabilidades comunes como OWASP Top 10

3. **Agregar SQL Database Rule Set**:
   - En la misma sección de grupos de reglas administradas de AWS
   - Busca **SQL database** (Base de datos SQL)
   - Marca la casilla junto a **SQL database**
   - Este conjunto protege específicamente contra ataques de inyección SQL

4. Haz clic en **Agregar reglas** en la parte inferior

5. Revisa la capacidad de WCU (Web ACL Capacity Units):
   - En la parte superior derecha, verás el uso de capacidad
   - Core rule set: ~700 WCU
   - SQL database: ~200 WCU
   - Total aproximado: ~900 WCU de 1500 disponibles

⚠️ **Advertencia sobre límite de WCU**: Cada Web ACL tiene un límite de 1500 WCU. Si agregas demasiadas reglas complejas, podrías exceder este límite. Para este laboratorio, las dos reglas seleccionadas están dentro del límite.

6. Haz clic en **Siguiente**

7. En la página **Establecer prioridad de reglas**:
   - Las reglas se evalúan en orden de prioridad
   - Deja el orden predeterminado (Core rule set primero, luego SQL database)
   - Haz clic en **Siguiente**

8. En la página **Configurar métricas**:
   - Deja las métricas de CloudWatch habilitadas (configuración predeterminada)
   - Haz clic en **Siguiente**

9. En la página **Revisar y crear ACL web**:
   - Revisa toda la configuración
   - Verifica que la acción predeterminada sea "Permitir"
   - Verifica que ambas reglas gestionadas estén agregadas
   - Haz clic en **Crear ACL web**

⏱️ **Nota**: La creación del Web ACL puede tardar 1-2 minutos.

**✓ Verificación**: Confirme que:
- El Web ACL se creó exitosamente
- El estado es "Activo"
- Las dos reglas gestionadas aparecen en la lista de reglas
- El ALB está asociado al Web ACL

### Paso 7: Probar WAF con Tráfico Normal

**⏱️ Tiempo estimado: 5 minutos**

Ahora probaremos que el WAF permite el tráfico legítimo.

1. Navega a la consola de EC2
2. En el panel de navegación izquierdo, haz clic en **Balanceadores de carga**
3. Selecciona tu Application Load Balancer
4. En la pestaña **Descripción**, copia el **Nombre de DNS** (ejemplo: `alb-web-luis-123456789.us-east-1.elb.amazonaws.com`)

5. Abre una nueva pestaña en tu navegador web
6. Pega el nombre DNS del ALB en la barra de direcciones
7. Presiona Enter

**✓ Verificación**: Confirme que:
- La página web se carga correctamente
- Recibes un código de respuesta HTTP 200 OK
- El contenido de la aplicación web se muestra normalmente
- No hay errores de bloqueo

**Nota educativa**: El tráfico HTTP normal pasa a través del WAF sin problemas porque no coincide con ninguna regla de bloqueo. El WAF solo bloquea solicitudes que contienen patrones maliciosos.

### Paso 8: Probar Bloqueo con Inyección SQL

**⏱️ Tiempo estimado: 8 minutos**

Ahora simularemos un ataque de inyección SQL para verificar que el WAF bloquea tráfico malicioso.

1. En tu navegador, modifica la URL del ALB agregando un parámetro de consulta malicioso:

   ```
   http://[TU-ALB-DNS]/?id=1' OR '1'='1
   ```

   **Ejemplo completo**:
   ```
   http://alb-web-luis-123456789.us-east-1.elb.amazonaws.com/?id=1' OR '1'='1
   ```

2. Presiona Enter para enviar la solicitud

**✓ Verificación**: Confirme que:
- Recibes un error **403 Forbidden**
- La página muestra un mensaje de acceso denegado
- El WAF bloqueó la solicitud antes de que llegara a tu aplicación

**Nota educativa**: La cadena `' OR '1'='1` es un patrón clásico de inyección SQL. El conjunto de reglas SQL database de AWS WAF detectó este patrón malicioso y bloqueó la solicitud automáticamente.

3. **Diferencia entre Security Groups y WAF**:
   - **Security Groups**: Operan en capa 3/4 (red/transporte). Bloquean basándose en direcciones IP, puertos y protocolos. No pueden inspeccionar el contenido de las solicitudes HTTP.
   - **AWS WAF**: Opera en capa 7 (aplicación). Inspecciona el contenido de las solicitudes HTTP/HTTPS, incluyendo parámetros de consulta, encabezados y cuerpo de la solicitud. Puede detectar y bloquear ataques específicos de aplicación como inyección SQL y XSS.

### Paso 9: Revisar Métricas de WAF (Opcional)

**⏱️ Tiempo estimado: 7 minutos**

Si tienes tiempo, puedes revisar las métricas y logs del WAF.

1. Regresa a la consola de AWS WAF
2. Selecciona tu Web ACL
3. Haz clic en la pestaña **Información general**
4. Desplázate hacia abajo para ver las métricas de CloudWatch:
   - **Solicitudes permitidas**: Tráfico legítimo que pasó
   - **Solicitudes bloqueadas**: Tráfico malicioso bloqueado
   - **Solicitudes contadas**: Solicitudes que coincidieron con reglas en modo "count"

5. Haz clic en la pestaña **Solicitudes muestreadas**:
   - Aquí puedes ver ejemplos de solicitudes recientes
   - Busca la solicitud con el parámetro de inyección SQL
   - Verifica que la acción fue "Block" (Bloquear)
   - Revisa qué regla específica bloqueó la solicitud

**✓ Verificación**: Confirme que:
- Las métricas muestran al menos una solicitud bloqueada
- La solicitud con inyección SQL aparece en las muestras
- La regla que bloqueó fue del grupo "SQL database"

## Resumen del Laboratorio

En este laboratorio has:

- Creado una lista de control de acceso web (Web ACL) en AWS WAF
- Asociado el Web ACL a tu Application Load Balancer
- Configurado reglas gestionadas de AWS para protección contra amenazas comunes
- Comprendido la diferencia entre protección de red (Security Groups) y protección de aplicación (WAF)
- Probado y validado el bloqueo de ataques de inyección SQL
- Revisado métricas de seguridad en CloudWatch

AWS WAF proporciona una capa adicional de seguridad para tus aplicaciones web, complementando la protección de red que ofrecen los Security Groups. Las reglas gestionadas de AWS se actualizan automáticamente para proteger contra nuevas amenazas, reduciendo la carga operativa de mantener reglas de seguridad personalizadas.

## Solución de Problemas

Si encuentra dificultades durante este laboratorio, consulte la [Guía de Solución de Problemas](../TROUBLESHOOTING.md) que contiene soluciones a errores comunes.

**Errores que requieren asistencia del instructor:**
- Errores de permisos IAM
- Errores de límites de cuota de AWS

## Gestión del Ciclo de Vida de Recursos

⚠️ **Importante**: NO elimine el Web ACL al finalizar este laboratorio. Este recurso se utilizará en el Día 4 del workshop.

Si necesita eliminar el Web ACL más adelante, consulte la [Guía de Limpieza de Recursos](../limpieza/README.md) para instrucciones detalladas sobre cómo desasociar y eliminar correctamente el Web ACL.

---

**¡Felicitaciones!** Has completado el Laboratorio 3.1. Continúa con el [Laboratorio 3.2: Gestión de Identidades y Acceso Seguro](../lab-3.2-iam-ssm/README.md).
