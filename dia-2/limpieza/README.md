# 🧹 Limpieza de Recursos - Día 2 (Opcional)

## Índice

- [Introducción](#introducción)
- [Importante: Recursos Compartidos](#importante-recursos-compartidos)
- [Orden de Eliminación](#orden-de-eliminación)
- [Paso 1: Eliminar Alarma de CloudWatch](#paso-1-eliminar-alarma-de-cloudwatch)
- [Paso 2: Eliminar Pila de CloudFormation](#paso-2-eliminar-pila-de-cloudformation)
- [Paso 3: Eliminar Target Group](#paso-3-eliminar-target-group)
- [Paso 4: Eliminar Application Load Balancer](#paso-4-eliminar-application-load-balancer)
- [Paso 5: Eliminar Instancia RDS](#paso-5-eliminar-instancia-rds)
- [Paso 6: Eliminar Grupo de Subredes de RDS](#paso-6-eliminar-grupo-de-subredes-de-rds)
- [Paso 7: Eliminar Security Groups](#paso-7-eliminar-security-groups)
- [Paso 8: Vaciar y Eliminar Bucket S3](#paso-8-vaciar-y-eliminar-bucket-s3)
- [Paso 9: Desmontar y Eliminar Volumen EBS](#paso-9-desmontar-y-eliminar-volumen-ebs)
- [Verificación Final](#verificación-final)

---

## Introducción

Esta guía proporciona instrucciones para eliminar todos los recursos creados durante el Día 2 del Workshop BCRP de AWS.

**⚠️ Nota Importante**: Esta limpieza es **opcional**. El Día 2 es el último día del workshop, por lo que puede optar por mantener los recursos para práctica adicional o eliminarlos para evitar costos.

**Estimación de tiempo**: 20-30 minutos

---

## Importante: Recursos Compartidos

⚠️ **CRÍTICO**: NO elimine recursos que no tengan su sufijo de participante `{nombre-participante}`.

**Recursos compartidos del instructor que NO debe eliminar**:
- VPC principal del workshop
- Internet Gateway
- Subredes públicas y privadas compartidas
- Tablas de enrutamiento compartidas
- Roles IAM del workshop

**Solo elimine recursos que contengan su nombre de participante en el nombre.**

---

## Orden de Eliminación

Es importante seguir este orden para evitar errores de dependencias:

1. Alarma de CloudWatch
2. Pila de CloudFormation (elimina ASG y Launch Template automáticamente)
3. Target Group
4. Application Load Balancer
5. Instancia RDS
6. Grupo de subredes de RDS
7. Security Groups (RDS, Web y ALB - en ese orden)
8. Bucket S3
9. Volumen EBS

---

## Paso 1: Eliminar Alarma de CloudWatch

1. Abra la consola de **CloudWatch**
2. En el panel de navegación izquierdo, haga clic en **Alarmas** > **Todas las alarmas**
3. Busque la alarma con su nombre: `alarm-cpu-asg-{nombre-participante}`
4. Seleccione la alarma marcando la casilla
5. Haga clic en el botón **Acciones** > **Eliminar**
6. Confirme la eliminación haciendo clic en **Eliminar**

**✓ Verificación**: La alarma ya no aparece en la lista de alarmas.

---

## Paso 2: Eliminar Pila de CloudFormation

⏱️ **Nota**: Este paso puede tardar 5-10 minutos. CloudFormation eliminará automáticamente el Auto Scaling Group y el Launch Template.

1. Abra la consola de **CloudFormation**
2. En la lista de pilas, busque: `stack-web-{nombre-participante}`
3. Seleccione la pila marcando la casilla
4. Haga clic en el botón **Eliminar**
5. En el cuadro de diálogo de confirmación, haga clic en **Eliminar pila**
6. Espere a que el estado cambie a **DELETE_COMPLETE**
   - Puede hacer clic en la pestaña **Eventos** para ver el progreso
   - CloudFormation eliminará automáticamente:
     - Auto Scaling Group
     - Launch Template
     - Instancias EC2 asociadas

**✓ Verificación**: 
- La pila muestra estado **DELETE_COMPLETE** o desaparece de la lista
- En la consola de EC2 > Auto Scaling Groups, el grupo ya no existe
- En la consola de EC2 > Instancias, las instancias del ASG están terminadas

---

## Paso 3: Eliminar Target Group

1. Abra la consola de **EC2**
2. En el panel de navegación izquierdo, desplácese hasta **Balanceo de carga** > **Grupos de destino**
3. Busque el Target Group: `tg-web-{nombre-participante}`
4. Seleccione el Target Group marcando la casilla
5. Haga clic en el botón **Acciones** > **Eliminar**
6. En el cuadro de diálogo, escriba `confirm` para confirmar
7. Haga clic en **Eliminar**

**✓ Verificación**: El Target Group ya no aparece en la lista.

---

## Paso 4: Eliminar Application Load Balancer

⏱️ **Nota**: Este paso puede tardar 2-3 minutos.

1. En la consola de **EC2**, vaya a **Balanceo de carga** > **Balanceadores de carga**
2. Busque el ALB: `alb-web-{nombre-participante}`
3. Seleccione el ALB marcando la casilla
4. Haga clic en el botón **Acciones** > **Eliminar balanceador de carga**
5. En el cuadro de diálogo, escriba `confirm` para confirmar
6. Haga clic en **Eliminar**
7. Espere a que el estado cambie a **eliminado**

**✓ Verificación**: El ALB ya no aparece en la lista de balanceadores de carga.

---

## Paso 5: Eliminar Instancia RDS

⏱️ **Nota**: Este paso puede tardar 5-10 minutos.

1. Abra la consola de **RDS**
2. En el panel de navegación izquierdo, haga clic en **Bases de datos**
3. Busque la instancia: `rds-mysql-{nombre-participante}`
4. Seleccione la instancia haciendo clic en su nombre
5. Haga clic en el botón **Acciones** > **Eliminar**
6. En el cuadro de diálogo de confirmación:
   - **Desmarque** la opción "Crear snapshot final" (no es necesario para el workshop)
   - **Marque** la casilla "Reconozco que al eliminar esta instancia..."
   - Escriba `delete me` en el campo de confirmación
7. Haga clic en **Eliminar**
8. Espere a que el estado cambie a **eliminando** y luego desaparezca

**✓ Verificación**: La instancia RDS ya no aparece en la lista de bases de datos.

---

## Paso 6: Eliminar Grupo de Subredes de RDS

1. En la consola de **RDS**, vaya a **Grupos de subredes**
2. Busque el grupo: `rds-subnet-group-{nombre-participante}`
3. Seleccione el grupo marcando la casilla
4. Haga clic en el botón **Eliminar**
5. Confirme la eliminación haciendo clic en **Eliminar** en el cuadro de diálogo

**✓ Verificación**: El grupo de subredes ya no aparece en la lista.

---

## Paso 7: Eliminar Security Groups

**Importante**: Elimine los Security Groups en el siguiente orden: primero RDS, luego Web, y finalmente ALB.

### 7.1 Eliminar Security Group de RDS

1. Abra la consola de **EC2**
2. En el panel de navegación izquierdo, haga clic en **Red y seguridad** > **Grupos de seguridad**
3. Busque el Security Group: `rds-sg-{nombre-participante}`
4. Seleccione el Security Group marcando la casilla
5. Haga clic en el botón **Acciones** > **Eliminar grupos de seguridad**
6. Confirme haciendo clic en **Eliminar**

**✓ Verificación**: El Security Group de RDS ya no aparece en la lista.

### 7.2 Eliminar Security Group de Web

1. En la misma pantalla de **Grupos de seguridad**
2. Busque el Security Group: `web-sg-{nombre-participante}`
3. Seleccione el Security Group marcando la casilla
4. Haga clic en el botón **Acciones** > **Eliminar grupos de seguridad**
5. Confirme haciendo clic en **Eliminar**

**✓ Verificación**: El Security Group de Web ya no aparece en la lista.

### 7.3 Eliminar Security Group de ALB

1. En la misma pantalla de **Grupos de seguridad**
2. Busque el Security Group: `alb-sg-{nombre-participante}`
3. Seleccione el Security Group marcando la casilla
4. Haga clic en el botón **Acciones** > **Eliminar grupos de seguridad**
5. Confirme haciendo clic en **Eliminar**

**✓ Verificación**: El Security Group de ALB ya no aparece en la lista.

**Nota**: Si recibe un error indicando que el Security Group está en uso, espere unos minutos y reintente. Puede que algún recurso aún esté terminando.

---

## Paso 8: Vaciar y Eliminar Bucket S3

**Importante**: Primero debe vaciar el bucket antes de poder eliminarlo.

### 8.1 Vaciar el Bucket

1. Abra la consola de **S3**
2. En la lista de buckets, busque: `workshop-aws-{nombre-participante}-{numero}`
3. Haga clic en el nombre del bucket para abrirlo
4. Haga clic en el botón **Vaciar**
5. En el cuadro de diálogo:
   - Escriba `vaciar permanentemente` en el campo de confirmación
   - Haga clic en **Vaciar**
6. Espere a que se complete el proceso

**✓ Verificación**: El bucket muestra "0 objetos".

### 8.2 Eliminar el Bucket

1. Regrese a la lista de buckets haciendo clic en **Amazon S3** en la parte superior
2. Seleccione el bucket marcando la casilla
3. Haga clic en el botón **Eliminar**
4. En el cuadro de diálogo:
   - Escriba el nombre completo del bucket para confirmar
   - Haga clic en **Eliminar bucket**

**✓ Verificación**: El bucket ya no aparece en la lista de buckets.

---

## Paso 9: Desmontar y Eliminar Volumen EBS

### 9.1 Desmontar el Volumen de la Instancia EC2

1. Conéctese por SSH a su instancia EC2 del Día 1
2. Desmonte el volumen:
   ```bash
   sudo umount /mnt/data_logs
   ```
3. Edite el archivo `/etc/fstab` para eliminar la entrada del volumen:
   ```bash
   sudo nano /etc/fstab
   ```
4. Elimine la línea que contiene `/mnt/data_logs`
5. Guarde el archivo (Ctrl+O, Enter, Ctrl+X)

**✓ Verificación**: Ejecute `df -h` y confirme que `/mnt/data_logs` ya no aparece.

### 9.2 Desasociar el Volumen

1. Abra la consola de **EC2**
2. En el panel de navegación izquierdo, haga clic en **Elastic Block Store** > **Volúmenes**
3. Busque el volumen: `ebs-data-{nombre-participante}`
4. Seleccione el volumen marcando la casilla
5. Haga clic en el botón **Acciones** > **Desasociar volumen**
6. Confirme haciendo clic en **Desasociar**
7. Espere a que el estado cambie a **disponible**

**✓ Verificación**: El estado del volumen es **disponible** (no **en uso**).

### 9.3 Eliminar el Volumen

1. Con el volumen aún seleccionado
2. Haga clic en el botón **Acciones** > **Eliminar volumen**
3. Confirme haciendo clic en **Eliminar**

**✓ Verificación**: El volumen ya no aparece en la lista de volúmenes.

---

## Verificación Final

Revise que todos los recursos con su nombre de participante han sido eliminados:

### Checklist de Recursos Eliminados

- [ ] Alarma de CloudWatch: `alarm-cpu-asg-{nombre-participante}`
- [ ] Pila de CloudFormation: `stack-web-{nombre-participante}`
- [ ] Auto Scaling Group (eliminado automáticamente por CloudFormation)
- [ ] Launch Template (eliminado automáticamente por CloudFormation)
- [ ] Target Group: `tg-web-{nombre-participante}`
- [ ] Application Load Balancer: `alb-web-{nombre-participante}`
- [ ] Instancia RDS: `rds-mysql-{nombre-participante}`
- [ ] Grupo de subredes de RDS: `rds-subnet-group-{nombre-participante}`
- [ ] Security Group RDS: `rds-sg-{nombre-participante}`
- [ ] Security Group Web: `web-sg-{nombre-participante}`
- [ ] Security Group ALB: `alb-sg-{nombre-participante}`
- [ ] Bucket S3: `workshop-aws-{nombre-participante}-{numero}`
- [ ] Volumen EBS: `ebs-data-{nombre-participante}`

### Recursos del Día 1 (Opcional)

Si también desea eliminar los recursos del Día 1, consulte la guía de limpieza del Día 1:
- [Limpieza de Recursos - Día 1](../../dia-1/limpieza/README.md)

⚠️ **Recuerde**: NO elimine recursos compartidos del instructor (VPC, subredes compartidas, Internet Gateway, etc.)

---

## ¡Felicitaciones!

Ha completado exitosamente la limpieza de recursos del Día 2 del Workshop BCRP de AWS.

**Costos evitados**: Al eliminar estos recursos, evita costos continuos de:
- RDS (~$0.017/hora)
- ALB (~$0.0225/hora)
- Instancias EC2 del ASG (~$0.0104/hora por instancia)
- Almacenamiento EBS y S3

**Próximos pasos**:
- Revise los conceptos aprendidos en el workshop
- Practique creando arquitecturas similares en su propia cuenta de AWS
- Explore servicios adicionales de AWS no cubiertos en el workshop

¡Gracias por participar en el Workshop BCRP de AWS!
