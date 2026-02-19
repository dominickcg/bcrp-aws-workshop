# 🧹 Limpieza de Recursos - Día 4 (Opcional)

**Nota**: Esta limpieza es opcional ya que este es el último día del workshop. Sin embargo, es importante realizarla para evitar costos innecesarios en su cuenta de AWS.

⚠️ **Advertencia de Costos**: Los recursos de SageMaker Canvas y las instancias EC2 de Bedrock pueden generar costos si se mantienen activos. Se recomienda eliminarlos al finalizar el workshop.

---

## Orden de Eliminación

Es importante seguir este orden para evitar errores de dependencias entre recursos.

### 1. Eliminar Pila de CloudFormation

La pila de CloudFormation eliminará automáticamente la instancia EC2 y el rol IAM asociado.

1. Abra la consola de **CloudFormation**
2. En el panel izquierdo, haga clic en **Pilas**
3. Seleccione la pila `pila-genai-{nombre-participante}`
4. Haga clic en el botón **Eliminar**
5. Confirme la eliminación haciendo clic en **Eliminar pila**

⏱️ **Nota**: La eliminación puede tardar 2-3 minutos.

**✓ Verificación**: Después de unos minutos, confirme que:
- El estado de la pila es `DELETE_COMPLETE`
- La instancia EC2 asociada ya no aparece en la consola de EC2
- El rol IAM asociado ya no aparece en la consola de IAM

---

### 2. Eliminar Guardrail de Bedrock

1. Abra la consola de **Amazon Bedrock**
2. En el panel izquierdo, haga clic en **Guardrails**
3. Seleccione el Guardrail `guardrail-bcrp-{nombre-participante}`
4. Haga clic en el botón **Eliminar**
5. Confirme la eliminación escribiendo el nombre del Guardrail

**✓ Verificación**: Confirme que el Guardrail ya no aparece en la lista.

---

### 3. Eliminar Recursos de SageMaker Canvas

#### 3.1 Eliminar Modelo

1. Abra la consola de **Amazon SageMaker**
2. En el panel izquierdo, haga clic en **Canvas**
3. Acceda a su aplicación de Canvas
4. En la sección **Modelos**, seleccione el modelo de predicción de crédito
5. Haga clic en **Eliminar modelo**
6. Confirme la eliminación

**✓ Verificación**: Confirme que el modelo ya no aparece en la lista de modelos.

#### 3.2 Eliminar Dataset

1. En SageMaker Canvas, navegue a la sección **Datasets**
2. Seleccione el dataset `bcrp-credit-risk`
3. Haga clic en **Eliminar dataset**
4. Confirme la eliminación

**✓ Verificación**: Confirme que el dataset ya no aparece en la lista.

#### 3.3 Cerrar Aplicación de Canvas (Opcional)

Si no planea usar SageMaker Canvas en el futuro cercano:

1. En la consola de SageMaker, navegue a **Canvas**
2. Haga clic en **Cerrar aplicación** o **Stop app**
3. Confirme la acción

⚠️ **Nota**: Cerrar la aplicación de Canvas detiene los costos asociados, pero puede tardar varios minutos en reiniciarse si la necesita nuevamente.

---

## Recursos Compartidos - NO Modificar

⚠️ **IMPORTANTE**: Los siguientes recursos son compartidos y provistos por el instructor. **NO los elimine**:

- Dataset `bcrp-credit-risk.csv` (si está en S3 compartido)
- Plantilla CloudFormation `genai-app.yaml` (si está en S3 compartido)
- Políticas IAM base del workshop
- Roles IAM compartidos
- Modelos de Bedrock (no se pueden eliminar, solo desactivar el acceso)

Si eliminó accidentalmente un recurso compartido, notifique al instructor de inmediato.

---

## Verificación Final

Después de completar todos los pasos de limpieza, verifique que:

- [ ] La pila de CloudFormation está en estado `DELETE_COMPLETE`
- [ ] No hay instancias EC2 con su nombre de participante en estado "En ejecución"
- [ ] El Guardrail de Bedrock fue eliminado
- [ ] Los modelos y datasets de SageMaker Canvas fueron eliminados
- [ ] La aplicación de SageMaker Canvas está cerrada (opcional)

---

## Nota Final

Al finalizar el workshop completo (Días 1-4), puede eliminar todos los recursos creados durante los cuatro días. Consulte las guías de limpieza de cada día:

- [Limpieza Día 1](../../dia-1/limpieza/README.md)
- [Limpieza Día 2](../../dia-2/limpieza/README.md)
- [Limpieza Día 3](../../dia-3/limpieza/README.md)
- Limpieza Día 4 (este documento)

Si tiene dudas sobre qué recursos eliminar, consulte con el instructor antes de proceder.
