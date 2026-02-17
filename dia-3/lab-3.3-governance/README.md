# 📊 Laboratorio 3.3 - Gobernanza y Auditoría

## Índice

- [Descripción General](#descripción-general)
- [Objetivos de Aprendizaje](#objetivos-de-aprendizaje)
- [Duración Estimada](#duración-estimada)
- [Prerequisitos](#prerequisitos)
- [Instrucciones](#instrucciones)
  - [Paso 1: Verificación de Región](#paso-1-verificación-de-región)
  - [Paso 2: Acceder a CloudTrail](#paso-2-acceder-a-cloudtrail)
  - [Paso 3: Filtrar Eventos](#paso-3-filtrar-eventos)
  - [Paso 4: Identificar Evento CreateWebACL](#paso-4-identificar-evento-createwebacl)
  - [Paso 5: Revisar Detalles del Evento](#paso-5-revisar-detalles-del-evento)
  - [Paso 6: Acceder a Trusted Advisor](#paso-6-acceder-a-trusted-advisor)
  - [Paso 7: Revisar Categoría Seguridad](#paso-7-revisar-categoría-seguridad)
  - [Paso 8: Revisar Categoría Optimización de Costos](#paso-8-revisar-categoría-optimización-de-costos)
  - [Paso 9: Revisar Otras Categorías (Opcional)](#paso-9-revisar-otras-categorías-opcional)
- [Resumen del Laboratorio](#resumen-del-laboratorio)
- [Solución de Problemas](#solución-de-problemas)
- [Gestión del Ciclo de Vida de Recursos](#gestión-del-ciclo-de-vida-de-recursos)

## Descripción General

AWS CloudTrail es un servicio que registra todas las llamadas a la API de AWS realizadas en tu cuenta, proporcionando un historial completo de actividades para auditoría, cumplimiento y análisis de seguridad. Cada acción que realizas en la consola de AWS, CLI o SDK genera un evento en CloudTrail que incluye quién realizó la acción, cuándo, desde dónde y qué recursos fueron afectados.

AWS Trusted Advisor es un servicio que analiza tu entorno de AWS y proporciona recomendaciones en tiempo real para ayudarte a seguir las mejores prácticas de AWS. Trusted Advisor evalúa tu cuenta en cinco categorías: optimización de costos, rendimiento, seguridad, tolerancia a fallos y límites de servicio.

En este laboratorio utilizarás CloudTrail para rastrear las acciones que has realizado durante el workshop, identificarás eventos específicos como la creación del Web ACL, y utilizarás Trusted Advisor para revisar recomendaciones de seguridad y optimización de costos en tu entorno.

## Objetivos de Aprendizaje

Al completar este laboratorio, serás capaz de:

- Utilizar CloudTrail para auditar acciones realizadas en tu cuenta de AWS
- Filtrar y buscar eventos específicos en el historial de CloudTrail
- Interpretar los detalles de un evento de CloudTrail incluyendo usuario, timestamp y parámetros
- Acceder a Trusted Advisor y comprender sus categorías de recomendaciones
- Identificar hallazgos de seguridad y oportunidades de optimización de costos

## Duración Estimada

⏱️ **50 minutos**

## Prerequisitos

Para completar este laboratorio necesitas:

- **Web ACL de AWS WAF**: Creado en el Laboratorio 3.1 del Día 3
- **Rol IAM**: Creado en el Laboratorio 3.2 del Día 3
- **Application Load Balancer**: Creado en el Laboratorio 2.3 del Día 2
- **Auto Scaling Group**: Creado en el Laboratorio 2.3 del Día 2
- **Bucket S3**: Creado en el Laboratorio 2.1 del Día 2

## Instrucciones

### Paso 1: Verificación de Región

**⏱️ Tiempo estimado: 2 minutos**

1. En la esquina superior derecha de la Consola de AWS, localiza el selector de región
2. Verifica que la región mostrada coincide con la región designada por el instructor
3. Si la región es incorrecta, haz clic en el selector y elige la región correcta

⚠️ **Importante**: CloudTrail registra eventos por región. Debes estar en la misma región donde creaste tus recursos para ver los eventos correspondientes.

### Paso 2: Acceder a CloudTrail

**⏱️ Tiempo estimado: 3 minutos**

1. En la barra de búsqueda global de AWS (parte superior), escribe **CloudTrail**
2. Selecciona **CloudTrail** de los resultados
3. En el panel de navegación de la izquierda, haz clic en **Historial de eventos**

**✓ Verificación**: Confirme que:
- Está en la página "Historial de eventos"
- Puede ver una lista de eventos recientes
- La región mostrada en la parte superior es la correcta

**Nota educativa**: CloudTrail registra automáticamente los últimos 90 días de eventos de gestión en el historial de eventos sin necesidad de crear un trail. Para retención a largo plazo o eventos de datos (como accesos a objetos S3), necesitarías crear un trail que almacene logs en S3.

### Paso 3: Filtrar Eventos

**⏱️ Tiempo estimado: 5 minutos**

Ahora filtraremos los eventos para encontrar acciones específicas que realizaste durante el workshop.

1. En la página "Historial de eventos", localiza la sección de filtros en la parte superior

2. Haz clic en el menú desplegable **Atributo de búsqueda**
3. Selecciona **Nombre del recurso**

4. En el campo de texto que aparece, escribe el nombre de tu Web ACL:
   ```
   waf-web-{nombre-participante}
   ```
   **Reemplaza** `{nombre-participante}` con tu identificador real.

5. Haz clic en el botón **Buscar** o presiona Enter

6. Ajusta el rango de tiempo si es necesario:
   - Haz clic en el selector de rango de tiempo (por defecto muestra las últimas 12 horas)
   - Selecciona **Hoy** o **Últimas 24 horas**

**✓ Verificación**: Confirme que:
- La lista de eventos se filtró para mostrar solo eventos relacionados con tu Web ACL
- Puede ver eventos como "CreateWebACL", "AssociateWebACL", etc.
- Los eventos tienen timestamps de hoy

**Nota educativa**: CloudTrail permite filtrar eventos por múltiples atributos: nombre de evento, nombre de recurso, nombre de usuario, ID de recurso, entre otros. Esto facilita la auditoría de acciones específicas o el rastreo de cambios en recursos particulares.


### Paso 4: Identificar Evento CreateWebACL

**⏱️ Tiempo estimado: 5 minutos**

1. En la lista de eventos filtrados, busca el evento con nombre **CreateWebACL**
   - Este evento corresponde al momento en que creaste el Web ACL en el Laboratorio 3.1

2. Haz clic en el nombre del evento **CreateWebACL** para expandir los detalles

**✓ Verificación**: Confirme que:
- El evento "CreateWebACL" aparece en la lista
- El timestamp corresponde aproximadamente al momento en que realizó el Lab 3.1
- El nombre del recurso coincide con tu Web ACL

**Nota educativa**: Cada acción que realizas en la consola de AWS genera uno o más eventos en CloudTrail. Por ejemplo, cuando creaste el Web ACL, se generaron eventos como CreateWebACL, AssociateWebACL, y posiblemente otros relacionados con la configuración de reglas.

### Paso 5: Revisar Detalles del Evento

**⏱️ Tiempo estimado: 8 minutos**

Ahora analizaremos los detalles completos del evento para comprender qué información proporciona CloudTrail.

1. Con el evento **CreateWebACL** expandido, localiza el botón **Ver evento**
2. Haz clic en **Ver evento**

3. Se abrirá una ventana modal mostrando el registro JSON completo del evento

4. Revisa los siguientes campos importantes:

   **a) Identidad del usuario (userIdentity)**:
   - Busca la sección `"userIdentity"`
   - Identifica el campo `"principalId"` o `"userName"`: Este es el usuario IAM que realizó la acción
   - Verifica el campo `"arn"`: ARN completo del usuario

   **b) Timestamp del evento (eventTime)**:
   - Busca el campo `"eventTime"`
   - Formato: `"2024-01-15T14:30:45Z"` (UTC)
   - Este es el momento exacto en que se realizó la acción

   **c) Dirección IP de origen (sourceIPAddress)**:
   - Busca el campo `"sourceIPAddress"`
   - Esta es la dirección IP desde donde se realizó la acción

   **d) Región (awsRegion)**:
   - Busca el campo `"awsRegion"`
   - Confirma que es la región del workshop

   **e) Parámetros de la solicitud (requestParameters)**:
   - Busca la sección `"requestParameters"`
   - Aquí puedes ver los parámetros exactos que se enviaron al crear el Web ACL
   - Incluye el nombre del Web ACL, reglas configuradas, acción por defecto, etc.

   **f) Respuesta (responseElements)**:
   - Busca la sección `"responseElements"`
   - Contiene la respuesta de AWS, incluyendo el ARN del Web ACL creado

5. **Opcional**: Copia el contenido JSON completo y pégalo en un editor de texto para analizarlo con más detalle

**✓ Verificación**: Confirme que:
- Puede identificar el usuario IAM que creó el Web ACL
- El timestamp corresponde al momento en que realizó el Lab 3.1
- La dirección IP de origen es visible
- Los parámetros de la solicitud incluyen el nombre de tu Web ACL

**Nota educativa**: CloudTrail proporciona un registro de auditoría completo que responde las preguntas: ¿Quién? (userIdentity), ¿Qué? (eventName), ¿Cuándo? (eventTime), ¿Dónde? (sourceIPAddress, awsRegion), y ¿Cómo? (requestParameters). Esta información es crucial para:
- **Auditoría de seguridad**: Identificar acciones no autorizadas
- **Cumplimiento**: Demostrar quién realizó cambios y cuándo
- **Troubleshooting**: Rastrear cambios que causaron problemas
- **Análisis forense**: Investigar incidentes de seguridad

6. Cierra la ventana modal del evento


### Paso 6: Acceder a Trusted Advisor

**⏱️ Tiempo estimado: 3 minutos**

Ahora cambiaremos a Trusted Advisor para revisar recomendaciones sobre tu entorno de AWS.

1. En la barra de búsqueda global de AWS (parte superior), escribe **Trusted Advisor**
2. Selecciona **Trusted Advisor** de los resultados

3. Llegarás al panel principal de Trusted Advisor que muestra un resumen de las cinco categorías:
   - **Optimización de costos** (naranja)
   - **Rendimiento** (morado)
   - **Seguridad** (rojo)
   - **Tolerancia a fallos** (azul)
   - **Límites de servicio** (verde)

**✓ Verificación**: Confirme que:
- Está en el panel principal de Trusted Advisor
- Puede ver las cinco categorías con contadores de recomendaciones
- Cada categoría muestra el número de elementos con alertas (rojo), advertencias (amarillo) y sin problemas (verde)

**Nota educativa**: Trusted Advisor evalúa tu cuenta de AWS automáticamente y proporciona recomendaciones basadas en las mejores prácticas de AWS. Las recomendaciones se clasifican en:
- **Alerta (rojo)**: Acción recomendada
- **Advertencia (amarillo)**: Investigación recomendada
- **Sin problemas (verde)**: No se detectaron problemas

⚠️ **Importante sobre planes de soporte**: Algunas verificaciones de Trusted Advisor están disponibles solo con planes de soporte Business o Enterprise. Con el plan básico (gratuito), verás un subconjunto de verificaciones, principalmente en las categorías de seguridad y límites de servicio.

### Paso 7: Revisar Categoría Seguridad

**⏱️ Tiempo estimado: 10 minutos**

1. En el panel principal de Trusted Advisor, haz clic en la categoría **Seguridad**

2. Se mostrará una lista de verificaciones de seguridad. Busca las siguientes verificaciones comunes:

   **a) Grupos de seguridad - Puertos sin restricción**:
   - Busca la verificación "Grupos de seguridad - Puertos sin restricción" o similar
   - Haz clic en el nombre de la verificación para expandir los detalles
   - Es probable que veas una alerta (rojo) o advertencia (amarillo) relacionada con tu Security Group del ALB

3. Revisa los detalles de la verificación:
   - **Descripción**: Explica por qué es un problema de seguridad
   - **Recursos afectados**: Lista de Security Groups con puertos abiertos a internet (0.0.0.0/0)
   - **Acción recomendada**: Restringir el acceso a direcciones IP específicas cuando sea posible

4. Identifica tu Security Group del ALB en la lista:
   - Busca el Security Group que contiene tu nombre de participante
   - Verifica que el puerto 80 (HTTP) está abierto a 0.0.0.0/0

5. **Comprende el hallazgo**:
   - **¿Es un problema real?**: En este caso, NO es un problema crítico porque:
     - El ALB está diseñado para ser accesible públicamente
     - El puerto 80 (HTTP) debe estar abierto para que los usuarios accedan a tu aplicación web
     - AWS WAF proporciona protección adicional en la capa de aplicación
   - **¿Cuándo sería un problema?**: Si el puerto 22 (SSH) o 3389 (RDP) estuvieran abiertos a 0.0.0.0/0, sería un riesgo de seguridad significativo

6. **Mejora recomendada** (no implementarás esto ahora, solo para conocimiento):
   - En producción, deberías usar HTTPS (puerto 443) en lugar de HTTP (puerto 80)
   - Configurar un certificado SSL/TLS en el ALB
   - Redirigir automáticamente HTTP a HTTPS

**✓ Verificación**: Confirme que:
- Puede ver la lista de verificaciones de seguridad
- Identificó al menos una verificación relacionada con Security Groups
- Comprende por qué Trusted Advisor marca el puerto 80 abierto como hallazgo
- Comprende que no todos los hallazgos requieren acción inmediata (depende del contexto)

**Nota educativa**: Trusted Advisor proporciona recomendaciones generales basadas en mejores prácticas, pero debes evaluar cada hallazgo en el contexto de tu arquitectura específica. Un puerto abierto a internet puede ser:
- **Apropiado**: Para un ALB o CloudFront que debe ser públicamente accesible
- **Riesgo de seguridad**: Para una instancia EC2 con SSH abierto a todo internet


### Paso 8: Revisar Categoría Optimización de Costos

**⏱️ Tiempo estimado: 8 minutos**

1. Regresa al panel principal de Trusted Advisor haciendo clic en el logo de Trusted Advisor o en el botón "Atrás"

2. Haz clic en la categoría **Optimización de costos**

3. Revisa las verificaciones disponibles. Algunas verificaciones comunes incluyen:

   **a) Instancias EC2 subutilizadas**:
   - Busca la verificación "Instancias de Amazon EC2 subutilizadas" o similar
   - Haz clic para expandir los detalles
   - Esta verificación identifica instancias EC2 con baja utilización de CPU (<10% durante 14 días)

4. Revisa los detalles:
   - **Descripción**: Explica que instancias con baja utilización pueden reducirse a tipos de instancia más pequeños
   - **Recursos afectados**: Lista de instancias con baja utilización
   - **Ahorro estimado**: Trusted Advisor puede mostrar el ahorro mensual estimado

5. **Comprende el hallazgo**:
   - Es posible que tus instancias del workshop aparezcan aquí si tienen baja utilización
   - En un entorno de producción, esto indicaría una oportunidad de optimización
   - Podrías cambiar de t2.micro a t2.nano, o usar instancias spot para cargas de trabajo no críticas

   **b) Volúmenes EBS no adjuntos** (si aplica):
   - Busca la verificación "Volúmenes de Amazon EBS no adjuntos"
   - Identifica volúmenes EBS que no están conectados a ninguna instancia
   - Estos volúmenes generan costos sin proporcionar valor

   **c) Direcciones IP elásticas no asociadas** (si aplica):
   - Busca la verificación "Direcciones IP elásticas no asociadas"
   - Las Elastic IPs no asociadas a instancias en ejecución generan cargos

6. **Nota sobre disponibilidad de verificaciones**:
   - Si tienes el plan de soporte básico, es posible que veas un mensaje indicando que algunas verificaciones requieren un plan Business o Enterprise
   - Las verificaciones básicas de optimización de costos están disponibles para todos los planes

**✓ Verificación**: Confirme que:
- Puede ver la lista de verificaciones de optimización de costos
- Comprende cómo Trusted Advisor identifica oportunidades de ahorro
- Entiende que las recomendaciones se basan en patrones de uso histórico

**Nota educativa**: La optimización de costos es un proceso continuo en AWS. Trusted Advisor te ayuda a identificar:
- **Recursos infrautilizados**: Instancias EC2, RDS, etc. con baja utilización
- **Recursos huérfanos**: Volúmenes EBS, snapshots, IPs elásticas sin usar
- **Oportunidades de ahorro**: Reserved Instances, Savings Plans, instancias spot
- **Configuraciones ineficientes**: Balanceadores de carga sin targets, etc.

En un entorno de producción, deberías revisar Trusted Advisor regularmente (mensualmente) para identificar oportunidades de optimización.

### Paso 9: Revisar Otras Categorías (Opcional)

**⏱️ Tiempo estimado: 6 minutos**

Si tienes tiempo, explora brevemente las otras categorías de Trusted Advisor:

1. **Rendimiento**:
   - Haz clic en la categoría **Rendimiento**
   - Busca verificaciones como:
     - "Instancias EC2 con alto uso de CPU"
     - "Throughput de volúmenes EBS"
     - "Configuración de CloudFront"
   - Estas verificaciones identifican cuellos de botella de rendimiento

2. **Tolerancia a fallos**:
   - Haz clic en la categoría **Tolerancia a fallos**
   - Busca verificaciones como:
     - "Instancias EC2 en una sola zona de disponibilidad"
     - "Snapshots de volúmenes EBS"
     - "Balanceadores de carga con una sola zona de disponibilidad"
   - Estas verificaciones identifican puntos únicos de fallo

3. **Límites de servicio**:
   - Haz clic en la categoría **Límites de servicio**
   - Busca verificaciones como:
     - "Límite de instancias EC2"
     - "Límite de VPCs"
     - "Límite de buckets S3"
   - Estas verificaciones te alertan cuando te acercas a los límites de cuota de AWS
   - Útil para planificar aumentos de cuota antes de alcanzar el límite

**✓ Verificación**: Confirme que:
- Exploró al menos una categoría adicional
- Comprende el propósito de cada categoría
- Entiende cómo Trusted Advisor proporciona una vista holística de tu entorno AWS

**Nota educativa**: Las cinco categorías de Trusted Advisor cubren los pilares fundamentales del AWS Well-Architected Framework:
- **Seguridad**: Protección de datos y sistemas
- **Optimización de costos**: Evitar gastos innecesarios
- **Rendimiento**: Usar recursos de manera eficiente
- **Tolerancia a fallos**: Diseñar para la resiliencia
- **Excelencia operativa**: Monitorear y mejorar continuamente (reflejado en límites de servicio)


## Resumen del Laboratorio

En este laboratorio has:

- Utilizado CloudTrail para auditar acciones realizadas en tu cuenta de AWS
- Filtrado y buscado eventos específicos en el historial de CloudTrail
- Interpretado los detalles completos de un evento de CloudTrail incluyendo usuario, timestamp, IP de origen y parámetros
- Comprendido cómo CloudTrail proporciona un registro de auditoría completo para seguridad y cumplimiento
- Accedido a Trusted Advisor y explorado sus cinco categorías de recomendaciones
- Identificado hallazgos de seguridad relacionados con Security Groups
- Revisado oportunidades de optimización de costos en tu entorno
- Comprendido cómo evaluar recomendaciones de Trusted Advisor en el contexto de tu arquitectura

CloudTrail y Trusted Advisor son herramientas fundamentales para la gobernanza en AWS. CloudTrail proporciona visibilidad completa de todas las acciones realizadas en tu cuenta, permitiendo auditoría, cumplimiento y análisis de seguridad. Trusted Advisor actúa como un consultor automatizado que evalúa continuamente tu entorno y proporciona recomendaciones para mejorar seguridad, reducir costos, aumentar rendimiento y mejorar la resiliencia.

## Solución de Problemas

Si encuentra dificultades durante este laboratorio, consulte la [Guía de Solución de Problemas](../TROUBLESHOOTING.md) que contiene soluciones a errores comunes.

**Errores que requieren asistencia del instructor:**
- Errores de permisos IAM al acceder a CloudTrail o Trusted Advisor
- No puede ver eventos en CloudTrail (puede requerir permisos adicionales)

## Gestión del Ciclo de Vida de Recursos

⚠️ **Importante**: CloudTrail y Trusted Advisor son servicios de solo lectura gestionados por AWS. No hay recursos que eliminar al finalizar este laboratorio.

**Notas sobre CloudTrail**:
- El historial de eventos de los últimos 90 días está disponible automáticamente sin costo
- No creamos un trail personalizado en este laboratorio, por lo que no hay recursos de CloudTrail que eliminar
- Si en el futuro creas un trail personalizado, recuerda que almacena logs en S3 que generan costos de almacenamiento

**Notas sobre Trusted Advisor**:
- Trusted Advisor es un servicio gestionado que no requiere configuración ni genera costos adicionales
- Las verificaciones se actualizan automáticamente cada 24 horas
- No hay recursos que eliminar

Si deseas eliminar los recursos creados en los laboratorios anteriores (WAF, IAM, ALB, etc.), consulta la [Guía de Limpieza de Recursos](../limpieza/README.md) para instrucciones detalladas.

---

**¡Felicitaciones!** Has completado el Laboratorio 3.3 y el Día 3 del workshop. Has aprendido sobre seguridad perimetral con AWS WAF, gestión de identidades con IAM y Session Manager, y gobernanza con CloudTrail y Trusted Advisor. Estos servicios son fundamentales para construir arquitecturas seguras, auditables y bien gobernadas en AWS.
