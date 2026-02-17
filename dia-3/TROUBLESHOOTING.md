# Solución de Problemas - Día 3

Esta guía contiene soluciones a los problemas más comunes que pueden surgir durante los laboratorios del Día 3 del Workshop BCRP de AWS. Los problemas están organizados por laboratorio para facilitar su búsqueda.

Si encuentra un error que no está listado aquí o requiere asistencia del instructor (marcado con ⚠️), no dude en solicitar ayuda.

## Índice

- [Laboratorio 3.1 - AWS WAF](#laboratorio-31---aws-waf)
- [Laboratorio 3.2 - IAM y Session Manager](#laboratorio-32---iam-y-session-manager)
- [Laboratorio 3.3 - Gobernanza y Auditoría](#laboratorio-33---gobernanza-y-auditoría)
- [Errores Generales](#errores-generales)

---

## Laboratorio 3.1 - AWS WAF

### Error: No puedo asociar Web ACL al ALB

**Síntoma**: Al intentar asociar el Web ACL al Application Load Balancer, aparece un error o el ALB no aparece en la lista de recursos disponibles.

**Causas posibles**:
1. El Web ACL se creó con tipo "CloudFront" en lugar de "Regional"
2. El Web ACL y el ALB están en regiones diferentes
3. El ALB no existe o fue eliminado

**Solución**:
1. Verifique que el Web ACL sea de tipo **Regional**:
   - Navegue a AWS WAF > Web ACLs
   - Seleccione su Web ACL
   - En la pestaña **Información general**, verifique que el tipo sea "Regional"
   - Si es "CloudFront", debe eliminar el Web ACL y crear uno nuevo de tipo Regional

2. Verifique que ambos recursos estén en la misma región:
   - Confirme la región en la esquina superior derecha de la consola
   - Navegue a EC2 > Balanceadores de carga y verifique que su ALB existe en esa región

3. Si el problema persiste, verifique que el ALB del Lab 2.3 no haya sido eliminado accidentalmente

---

### Error: Excedí el límite de WCU (Web ACL Capacity Units)

**Síntoma**: Al intentar agregar una regla gestionada al Web ACL, aparece un error indicando que se excedió el límite de capacidad (WCU).

**Causas posibles**:
1. Se agregaron demasiadas reglas gestionadas
2. Las reglas seleccionadas son muy complejas y consumen muchas WCU
3. El límite por defecto de WCU (1500) fue alcanzado

**Solución**:
1. Revise las reglas agregadas y remueva las que no son esenciales:
   - Para este laboratorio, solo necesita:
     - Core rule set (AWS-AWSManagedRulesCommonRuleSet)
     - SQL database rule set (AWS-AWSManagedRulesSQLiRuleSet)

2. Si agregó reglas adicionales, elimínelas:
   - Navegue a su Web ACL > pestaña **Reglas**
   - Seleccione las reglas innecesarias y haga clic en **Eliminar**

3. Verifique el consumo actual de WCU:
   - En la pestaña **Información general** del Web ACL
   - Confirme que está por debajo de 1500 WCU

---

### Error: WAF no bloquea el tráfico malicioso

**Síntoma**: Al probar la inyección SQL (`?id=1' OR '1'='1`), el sitio web responde normalmente en lugar de mostrar un error 403 Forbidden.

**Causas posibles**:
1. La regla SQL database no está activada
2. La acción de la regla está configurada como "Count" en lugar de "Block"
3. La prioridad de las reglas es incorrecta
4. El Web ACL no está correctamente asociado al ALB

**Solución**:
1. Verifique que la regla SQL esté activada:
   - Navegue a su Web ACL > pestaña **Reglas**
   - Confirme que "AWS-AWSManagedRulesSQLiRuleSet" aparece en la lista
   - Verifique que el estado sea **Habilitado**

2. Verifique la acción de la regla:
   - Seleccione la regla SQL
   - Confirme que la acción sea **Block** (no "Count" ni "Allow")

3. Verifique la asociación del Web ACL:
   - En la pestaña **Recursos asociados**
   - Confirme que su ALB aparece en la lista
   - Si no aparece, vuelva a asociarlo

4. Espere 1-2 minutos para que los cambios se propaguen y vuelva a probar

---

### Error: WAF bloquea todo el tráfico legítimo (403 en todas las solicitudes)

**Síntoma**: Al acceder a la URL normal del ALB (sin parámetros maliciosos), recibe un error 403 Forbidden.

**Causas posibles**:
1. La acción por defecto del Web ACL está configurada como "Block"
2. Una regla está configurada de manera muy estricta
3. La configuración de una regla gestionada es incorrecta

**Solución**:
1. Verifique la acción por defecto:
   - Navegue a su Web ACL > pestaña **Información general**
   - En la sección **Acción predeterminada para solicitudes web**, confirme que sea **Allow**
   - Si es "Block", haga clic en **Editar** y cámbiela a "Allow"

2. Revise las reglas individuales:
   - En la pestaña **Reglas**, verifique que las acciones sean apropiadas
   - Las reglas gestionadas de AWS deben tener acción "Block" solo para tráfico malicioso

3. Temporalmente, cambie las reglas a modo "Count" para diagnóstico:
   - Esto permitirá que el tráfico pase pero registrará las coincidencias
   - Revise las métricas para identificar qué regla está bloqueando el tráfico legítimo

---

## Laboratorio 3.2 - IAM y Session Manager

### Error: No puedo conectarme por Session Manager

**Síntoma**: El botón **Conectar** en Session Manager está deshabilitado, o al hacer clic aparece un error indicando que la instancia no está disponible.

**Causas posibles**:
1. El rol IAM no está asignado a la instancia
2. La política AmazonSSMManagedInstanceCore no está adjunta al rol
3. El agente SSM no está instalado o no está en ejecución (poco probable en Amazon Linux 2023)
4. La instancia se lanzó recientemente y el agente SSM aún no se ha registrado

**Solución**:
1. Verifique que la instancia tenga el rol IAM asignado:
   - Navegue a EC2 > Instancias
   - Seleccione la instancia nueva del ASG
   - En la pestaña **Detalles**, busque **Perfil de instancia de IAM**
   - Debe mostrar `role-ec2-s3readonly-{nombre-participante}`
   - Si no tiene rol, la instancia es antigua y debe terminarla para que el ASG lance una nueva

2. Verifique que el rol tenga la política SSM:
   - Navegue a IAM > Roles
   - Seleccione su rol
   - En la pestaña **Permisos**, confirme que **AmazonSSMManagedInstanceCore** está adjunta

3. Espere 3-5 minutos después del lanzamiento de la instancia:
   - El agente SSM necesita tiempo para registrarse con el servicio
   - Refresque la página de Session Manager periódicamente

4. Verifique el estado del agente SSM:
   - Navegue a Systems Manager > Administración de flotas > Instancias administradas
   - Su instancia debe aparecer en la lista con estado **En línea**
   - Si no aparece después de 5 minutos, hay un problema con el rol o la política

---

### Error: La instancia no tiene el rol IAM asignado

**Síntoma**: Al revisar los detalles de la instancia EC2, el campo **Perfil de instancia de IAM** está vacío o muestra un rol diferente.

**Causas posibles**:
1. El Launch Template no fue actualizado correctamente
2. La instancia es antigua y se lanzó antes de agregar el rol al Launch Template
3. La versión del Launch Template no se estableció como predeterminada en el ASG

**Solución**:
1. Verifique la versión del Launch Template:
   - Navegue a EC2 > Plantillas de lanzamiento
   - Seleccione su Launch Template del Lab 2.3
   - Verifique que la versión más reciente incluya el perfil de instancia de IAM
   - Confirme que esta versión sea la **Predeterminada**

2. Verifique la configuración del ASG:
   - Navegue a EC2 > Grupos de Auto Scaling
   - Seleccione su ASG
   - En la pestaña **Detalles**, verifique que use la versión correcta del Launch Template

3. Termine la instancia antigua para forzar el lanzamiento de una nueva:
   - Navegue a EC2 > Instancias
   - Seleccione la instancia sin rol
   - Acciones > Estado de la instancia > Terminar instancia
   - Espere 3-5 minutos a que el ASG lance una nueva instancia con el rol correcto

---

### Error: El comando AWS CLI falla con "Access Denied"

**Síntoma**: Al ejecutar `aws s3 ls s3://NOMBRE-DEL-BUCKET` en la sesión de Session Manager, aparece un error "Access Denied" o "An error occurred (AccessDenied)".

**Causas posibles**:
1. El ARN del bucket en la política IAM es incorrecto
2. La política inline no está adjunta al rol
3. El nombre del bucket es incorrecto
4. El bucket no existe o fue eliminado

**Solución**:
1. Verifique el ARN del bucket en la política IAM:
   - Navegue a IAM > Roles > su rol
   - En la pestaña **Permisos**, expanda la política inline
   - Verifique que el ARN sea: `arn:aws:s3:::s3-sitio-web-{nombre-participante}`
   - Verifique también el ARN para objetos: `arn:aws:s3:::s3-sitio-web-{nombre-participante}/*`

2. Confirme que la política inline está adjunta:
   - En la pestaña **Permisos** del rol
   - Debe aparecer una política inline con nombre similar a "S3ReadOnlyPolicy"

3. Verifique que el bucket existe:
   - Navegue a S3
   - Busque el bucket `s3-sitio-web-{nombre-participante}`
   - Si no existe, debe crearlo nuevamente (Lab 2.1)

4. Verifique el nombre del bucket en el comando:
   - Asegúrese de usar el nombre exacto del bucket
   - Ejemplo: `aws s3 ls s3://s3-sitio-web-luis`

---

### Error: No puedo crear el rol IAM

**Síntoma**: Al intentar crear el rol IAM, aparece un error de permisos indicando que no tiene autorización para realizar la acción `iam:CreateRole`.

**Causas posibles**:
1. Su usuario IAM no tiene permisos suficientes para crear roles
2. Hay una política de control de servicios (SCP) que restringe la creación de roles

**Solución**:

⚠️ **Este error requiere asistencia del instructor inmediatamente**. No intente solucionar este error por su cuenta, ya que involucra permisos de IAM que solo el instructor puede modificar.

Notifique al instructor proporcionando:
- Su nombre de participante
- El mensaje de error exacto
- La acción que estaba intentando realizar

---

### Error: El Auto Scaling Group no lanza una nueva instancia

**Síntoma**: Después de terminar una instancia del ASG, no se lanza una nueva instancia automáticamente.

**Causas posibles**:
1. El ASG ya está en su capacidad máxima
2. El Launch Template tiene una configuración inválida
3. Hay un problema con la cuota de instancias EC2

**Solución**:
1. Verifique la capacidad del ASG:
   - Navegue a EC2 > Grupos de Auto Scaling
   - Seleccione su ASG
   - En la pestaña **Detalles**, verifique:
     - **Capacidad deseada**: debe ser 2
     - **Capacidad mínima**: debe ser 2
     - **Capacidad máxima**: debe ser 4
   - Si la capacidad deseada es menor que 2, edítela a 2

2. Verifique el historial de actividades del ASG:
   - En la pestaña **Actividad**
   - Busque mensajes de error que indiquen por qué no se lanzó la instancia
   - Errores comunes: Launch Template inválida, límite de cuota

3. Verifique el Launch Template:
   - Navegue a EC2 > Plantillas de lanzamiento
   - Seleccione su Launch Template
   - Verifique que la versión predeterminada sea válida

4. Si el error menciona límites de cuota:
   - ⚠️ Notifique al instructor inmediatamente

---

## Laboratorio 3.3 - Gobernanza y Auditoría

### Error: No veo eventos en CloudTrail

**Síntoma**: Al acceder al historial de eventos de CloudTrail, la lista aparece vacía o no muestra los eventos esperados.

**Causas posibles**:
1. Está trabajando en una región diferente a donde se crearon los recursos
2. El rango de tiempo seleccionado no incluye los eventos
3. Los filtros aplicados son muy restrictivos
4. Los eventos son muy antiguos (CloudTrail solo muestra los últimos 90 días en el historial)

**Solución**:
1. Verifique la región:
   - Confirme en la esquina superior derecha que está en la región correcta
   - CloudTrail muestra eventos por región (excepto eventos globales de IAM)

2. Ajuste el rango de tiempo:
   - En la consola de CloudTrail, expanda el filtro de tiempo
   - Seleccione un rango más amplio (por ejemplo, "Últimas 24 horas" o "Hoy")

3. Remueva o ajuste los filtros:
   - Si aplicó filtros por nombre de recurso o tipo de evento, pruebe removerlos temporalmente
   - Haga clic en **Borrar filtros** para ver todos los eventos

4. Verifique que los recursos se crearon recientemente:
   - CloudTrail solo retiene eventos en el historial por 90 días
   - Si el Web ACL se creó hace más de 90 días, no aparecerá

---

### Error: No encuentro el evento CreateWebACL

**Síntoma**: Al buscar en el historial de eventos de CloudTrail, no aparece el evento "CreateWebACL" correspondiente a la creación del Web ACL en el Lab 3.1.

**Causas posibles**:
1. El Web ACL se creó en una región diferente
2. El nombre del evento es incorrecto en el filtro
3. El evento es muy antiguo
4. Está buscando por el nombre de usuario incorrecto

**Solución**:
1. Verifique la región:
   - AWS WAF regional registra eventos en la región donde se creó el recurso
   - Confirme que está en la misma región donde creó el Web ACL

2. Busque por nombre de recurso en lugar de tipo de evento:
   - En el filtro de CloudTrail, seleccione **Nombre del recurso**
   - Ingrese el nombre de su Web ACL: `waf-web-{nombre-participante}`
   - Esto mostrará todos los eventos relacionados con ese recurso

3. Amplíe el rango de tiempo:
   - Si creó el Web ACL hace varias horas, amplíe el rango a "Últimas 24 horas"

4. Busque eventos relacionados:
   - Si no encuentra "CreateWebACL", busque otros eventos como:
     - "AssociateWebACL" (asociación al ALB)
     - "UpdateWebACL" (actualización de reglas)

---

### Error: No puedo acceder a Trusted Advisor

**Síntoma**: Al intentar acceder a la consola de Trusted Advisor, aparece un error de permisos o la página no carga.

**Causas posibles**:
1. Su usuario IAM no tiene permisos para acceder a Trusted Advisor
2. Hay una política que restringe el acceso al servicio

**Solución**:

⚠️ **Este error requiere asistencia del instructor inmediatamente**. No intente solucionar este error por su cuenta, ya que involucra permisos de IAM que solo el instructor puede modificar.

Notifique al instructor proporcionando:
- Su nombre de participante
- El mensaje de error exacto
- La URL a la que intentaba acceder

---

### Error: Trusted Advisor no muestra recomendaciones o muestra muy pocas

**Síntoma**: Al acceder al dashboard de Trusted Advisor, aparecen muy pocas recomendaciones o algunas categorías están vacías.

**Causas posibles**:
1. La cuenta de AWS tiene un plan de soporte básico (Basic Support)
2. La cuenta es muy nueva y aún no hay suficientes datos
3. Los recursos creados en el workshop son muy recientes

**Solución**:
1. Comprenda las limitaciones del plan de soporte:
   - **Basic Support**: Solo muestra 7 verificaciones básicas de Trusted Advisor
   - **Developer Support**: Muestra las mismas 7 verificaciones básicas
   - **Business/Enterprise Support**: Muestra todas las verificaciones (más de 50)

2. Verificaciones disponibles en Basic Support:
   - Límites de servicio
   - Grupos de seguridad con puertos sin restricciones
   - Uso de IAM
   - MFA en cuenta raíz
   - Snapshots públicos de EBS
   - Snapshots públicos de RDS
   - Buckets S3 con permisos abiertos

3. Si no ve recomendaciones en estas categorías básicas:
   - Es posible que sus recursos cumplan con las mejores prácticas
   - O que los recursos sean muy recientes y aún no se hayan analizado

4. Para este laboratorio:
   - Enfóquese en las verificaciones de seguridad disponibles
   - Busque específicamente hallazgos sobre Security Groups con puerto 80 abierto
   - Si no aparecen hallazgos, es aceptable para propósitos educativos

---

## Errores Generales

### Error: Estoy trabajando en la región incorrecta

**Síntoma**: No puedo ver mis recursos (instancias EC2, ALB, buckets S3, etc.) en la consola de AWS.

**Causas posibles**:
1. Cambió accidentalmente de región
2. Está trabajando en una región diferente a la especificada por el instructor

**Solución**:
1. Verifique la región actual:
   - Mire la esquina superior derecha de la consola de AWS
   - Verá el nombre de la región actual (por ejemplo, "N. Virginia" o "Ohio")

2. Cambie a la región correcta:
   - Haga clic en el nombre de la región
   - Seleccione la región especificada por el instructor
   - Espere a que la consola recargue

3. Verifique que sus recursos ahora sean visibles:
   - Navegue al servicio correspondiente (EC2, S3, etc.)
   - Confirme que ve sus recursos con el sufijo `{nombre-participante}`

**Nota**: Algunos servicios son globales (IAM, CloudFront) y no dependen de la región.

---

### Error: Límite de cuota de servicio excedido

**Síntoma**: Al intentar crear un recurso (instancia EC2, ALB, etc.), aparece un error indicando que se excedió el límite de cuota del servicio.

**Causas posibles**:
1. La cuenta de AWS alcanzó el límite de recursos permitidos para ese servicio
2. Hay recursos huérfanos de laboratorios anteriores que no fueron eliminados

**Solución**:

⚠️ **Este error requiere asistencia del instructor inmediatamente**. Los límites de cuota solo pueden ser ajustados por el administrador de la cuenta.

Notifique al instructor proporcionando:
- Su nombre de participante
- El servicio que estaba intentando usar (EC2, VPC, etc.)
- El mensaje de error exacto
- El tipo de recurso que intentaba crear

**Mientras espera**:
- Verifique si tiene recursos duplicados o innecesarios que pueda eliminar
- Revise las guías de limpieza de días anteriores

---

### Error: Modifiqué o eliminé recursos de otro participante

**Síntoma**: Accidentalmente seleccionó y modificó/eliminó un recurso que no tiene su sufijo de participante.

**Causas posibles**:
1. No verificó el nombre del recurso antes de modificarlo
2. Seleccionó el recurso incorrecto en una lista
3. No utilizó filtros para mostrar solo sus recursos

**Solución**:
1. **Detenga inmediatamente** cualquier acción adicional

2. Identifique el recurso afectado:
   - Anote el nombre completo del recurso
   - Identifique a qué participante pertenece (por el sufijo)

3. Si modificó un recurso:
   - Intente revertir los cambios si es posible
   - Documente qué cambios realizó

4. Si eliminó un recurso:
   - No intente recrearlo
   - Documente qué recurso eliminó

5. ⚠️ **Notifique al instructor inmediatamente** proporcionando:
   - Su nombre de participante
   - El nombre del recurso afectado
   - El nombre del participante propietario del recurso
   - La acción que realizó (modificación o eliminación)
   - Los cambios específicos que hizo

**Prevención**:
- **Siempre** verifique que el nombre del recurso incluya su sufijo de participante antes de modificarlo
- Use filtros en las consolas para mostrar solo sus recursos
- Tenga especial cuidado al trabajar con recursos compartidos marcados como "Recurso compartido - NO modificar"

---

### Error: No puedo eliminar un recurso

**Síntoma**: Al intentar eliminar un recurso (Web ACL, rol IAM, etc.), aparece un error indicando que el recurso está en uso o tiene dependencias.

**Causas posibles**:
1. El recurso está asociado a otros recursos
2. Hay dependencias que deben eliminarse primero
3. El recurso está siendo utilizado activamente

**Solución**:
1. Para Web ACL:
   - Primero debe desasociar el Web ACL del ALB
   - Navegue a AWS WAF > su Web ACL > pestaña **Recursos asociados**
   - Seleccione el ALB y haga clic en **Desasociar**
   - Luego podrá eliminar el Web ACL

2. Para roles IAM:
   - Primero debe remover el rol del Launch Template
   - Luego debe desasociar todas las políticas (gestionadas e inline)
   - Finalmente podrá eliminar el rol

3. Para otros recursos:
   - Consulte la guía de limpieza correspondiente al día
   - Siga el orden de eliminación especificado

4. Si el error persiste:
   - Verifique que no haya recursos ocultos o en otras regiones
   - Espere unos minutos y vuelva a intentar (algunos recursos tardan en desasociarse)

---

## Contacto con el Instructor

Si encuentra un error que no está cubierto en esta guía, o si un error está marcado con ⚠️ indicando que requiere asistencia del instructor, no dude en solicitar ayuda.

**Información a proporcionar al instructor**:
- Su nombre de participante
- El laboratorio y paso específico donde ocurrió el error
- El mensaje de error completo (captura de pantalla si es posible)
- Las acciones que realizó antes del error
- Las soluciones que ya intentó

El instructor está disponible para ayudarle a resolver cualquier problema y asegurar que pueda completar exitosamente todos los laboratorios del workshop.
