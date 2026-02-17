# 🧹 Limpieza de Recursos - Día 3 (Opcional)

**Nota**: Esta limpieza es opcional. Solo realícela si no continuará con el Día 4 del workshop.

## Orden de Eliminación

Siga este orden para evitar errores de dependencias entre recursos.

### 1. Desasociar y Eliminar Web ACL (AWS WAF)

⚠️ **Importante**: Debe desasociar el Web ACL del Application Load Balancer antes de eliminarlo.

1. Abra la consola de **AWS WAF**
2. En el panel de navegación izquierdo, haga clic en **Web ACLs**
3. Seleccione el Web ACL `waf-web-{nombre-participante}`
4. Haga clic en la pestaña **Associated AWS resources**
5. Seleccione el Application Load Balancer asociado
6. Haga clic en **Disassociate**
7. Confirme la desasociación
8. ⏱️ **Espere 1-2 minutos** para que la desasociación se complete
9. Regrese a la lista de Web ACLs
10. Seleccione el Web ACL `waf-web-{nombre-participante}`
11. Haga clic en **Delete**
12. Escriba `delete` para confirmar
13. Haga clic en **Delete**

**✓ Verificación**: El Web ACL ya no aparece en la lista de Web ACLs.

### 2. Desasociar y Eliminar Rol IAM

⚠️ **Importante**: Debe eliminar el rol IAM de la plantilla de lanzamiento antes de eliminarlo.

#### 2.1. Eliminar Rol de la Plantilla de Lanzamiento

1. Abra la consola de **EC2**
2. En el panel de navegación izquierdo, haga clic en **Plantillas de lanzamiento**
3. Seleccione la plantilla de lanzamiento asociada a su Auto Scaling Group del Lab 2.3
4. Haga clic en **Acciones** > **Modificar plantilla (Crear nueva versión)**
5. En la sección **Configuración avanzada**, desplácese hasta **Perfil de instancia de IAM**
6. Seleccione **Ninguno**
7. Haga clic en **Crear versión de plantilla**
8. Seleccione la nueva versión de la plantilla
9. Haga clic en **Acciones** > **Establecer versión predeterminada**

#### 2.2. Eliminar Rol IAM

1. Abra la consola de **IAM**
2. En el panel de navegación izquierdo, haga clic en **Roles**
3. En la barra de búsqueda, escriba `role-ec2-s3readonly-{nombre-participante}`
4. Seleccione el rol
5. Haga clic en **Eliminar**
6. Escriba el nombre del rol para confirmar
7. Haga clic en **Eliminar**

**✓ Verificación**: El rol ya no aparece en la lista de roles de IAM.

### 3. Recursos del Día 2

Si desea realizar una limpieza completa, consulte la [Guía de Limpieza del Día 2](../../dia-2/limpieza/README.md) para eliminar:
- Auto Scaling Group
- Plantilla de lanzamiento
- Application Load Balancer
- Target Group
- Instancias EC2
- Base de datos RDS
- Grupos de seguridad
- Bucket S3

## Recursos que NO Requieren Limpieza

Los siguientes servicios no generan costos o son recursos compartidos:

### CloudTrail
- El trail configurado en el Laboratorio 3.3 no genera costos significativos
- El bucket S3 asociado tiene un volumen mínimo de datos
- **No elimine el trail** si desea mantener el historial de auditoría

### Trusted Advisor
- Es un servicio de AWS sin costo adicional
- Las recomendaciones no generan cargos
- **No requiere limpieza**

### AWS Health Dashboard
- Es un servicio de AWS sin costo adicional
- **No requiere limpieza**

## Recursos Compartidos - NO Modificar

⚠️ **CRÍTICO**: Los siguientes recursos son compartidos por todos los participantes. **NO los elimine ni modifique**:

- VPC del workshop
- Subredes públicas y privadas
- Internet Gateway
- NAT Gateway
- Tablas de rutas compartidas
- Roles IAM creados por el instructor
- Políticas IAM administradas compartidas

**Solo elimine recursos que incluyan su nombre de participante en el nombre del recurso.**

## Verificación Final

Después de completar la limpieza, verifique que no quedan recursos activos:

1. **EC2**: Navegue a EC2 > Instancias y confirme que no hay instancias con su nombre
2. **RDS**: Navegue a RDS > Bases de datos y confirme que no hay bases de datos con su nombre
3. **S3**: Navegue a S3 y confirme que no hay buckets con su nombre
4. **IAM**: Navegue a IAM > Roles y confirme que no hay roles con su nombre
5. **WAF**: Navegue a AWS WAF > Web ACLs y confirme que no hay Web ACLs con su nombre

⚠️ **Nota sobre facturación**: Algunos recursos pueden generar cargos mínimos durante las primeras horas después de la eliminación debido al ciclo de facturación de AWS. Esto es normal y los cargos cesarán completamente después de 24 horas.
