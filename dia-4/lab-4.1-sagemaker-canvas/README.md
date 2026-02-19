# 🧠 Laboratorio 4.1 - Machine Learning con SageMaker Canvas

## Índice

- [Descripción General](#descripción-general)
- [Objetivos de Aprendizaje](#objetivos-de-aprendizaje)
- [Duración Estimada](#duración-estimada)
- [Prerequisitos](#prerequisitos)
- [Nivelación Conceptual](#nivelación-conceptual)
  - [Machine Learning vs Programación Tradicional](#machine-learning-vs-programación-tradicional)
  - [Ciclo de Vida del Machine Learning](#ciclo-de-vida-del-machine-learning)
  - [Generalización vs Sobreajuste (Overfitting)](#generalización-vs-sobreajuste-overfitting)
  - [Roles de los Datos](#roles-de-los-datos)
  - [Clasificación Binaria](#clasificación-binaria)
  - [Métricas de Evaluación](#métricas-de-evaluación)
  - [AutoML e Interpretabilidad](#automl-e-interpretabilidad)
- [Diccionario de Datos](#diccionario-de-datos)
- [Instrucciones](#instrucciones)
  - [Paso 1: Verificación de Región](#paso-1-verificación-de-región)
  - [Paso 2: Navegar a SageMaker Canvas](#paso-2-navegar-a-sagemaker-canvas)
  - [Paso 3: Importar Dataset](#paso-3-importar-dataset)
  - [Paso 4: Perfilado de Datos](#paso-4-perfilado-de-datos)
  - [Paso 5: Ingeniería de Características](#paso-5-ingeniería-de-características)
  - [Paso 6: Entrenamiento Quick Build](#paso-6-entrenamiento-quick-build)
  - [Paso 7: Evaluación del Modelo](#paso-7-evaluación-del-modelo)
  - [Paso 8: Auditoría de Código](#paso-8-auditoría-de-código)
- [Resumen del Laboratorio](#resumen-del-laboratorio)
- [Solución de Problemas](#solución-de-problemas)
- [Gestión del Ciclo de Vida de Recursos](#gestión-del-ciclo-de-vida-de-recursos)

## Descripción General

En este laboratorio, construirá un modelo predictivo de riesgo crediticio utilizando Amazon SageMaker Canvas, una herramienta low-code que automatiza el ciclo completo de Machine Learning. Trabajará con un dataset financiero del BCRP, creará indicadores de solvencia mediante ingeniería de características, entrenará un modelo de clasificación binaria y evaluará su desempeño con métricas críticas para la supervisión bancaria.

SageMaker Canvas le permite construir modelos de ML sin escribir código, pero al final del laboratorio accederá al código Python generado automáticamente para validar la auditabilidad y transparencia del enfoque low-code.

## Objetivos de Aprendizaje

Al completar este laboratorio, usted será capaz de:

- Comprender el paradigma de Machine Learning supervisado y su diferencia con la programación tradicional
- Importar y perfilar datasets financieros en SageMaker Canvas
- Crear variables calculadas (Feature Engineering) con sustento financiero
- Entrenar un modelo de clasificación binaria usando Quick Build
- Interpretar métricas de evaluación críticas (Matriz de Confusión, Falsos Negativos, Feature Importance)
- Acceder al código Python generado para validar la auditabilidad del modelo

## Duración Estimada

⏱️ **50 minutos**

## Prerequisitos

- **Navegación en Consola AWS** (Día 1): Familiaridad con la interfaz de la consola de AWS y búsqueda de servicios
- **Conocimiento de S3** (Día 2): Comprensión básica de almacenamiento de objetos para entender dónde se almacenan los datasets

## Nivelación Conceptual

Antes de iniciar el laboratorio práctico, es fundamental nivelar conceptos clave de Machine Learning que le permitirán comprender qué está sucediendo detrás de la interfaz de SageMaker Canvas.

### Machine Learning vs Programación Tradicional

**Programación Tradicional:**
- Paradigma: Reglas + Datos = Respuestas
- El programador escribe reglas explícitas (if-then-else)
- Ejemplo: "Si mora > 90 días, entonces clasificar como Default"

**Machine Learning:**
- Paradigma: Datos + Respuestas Históricas = Reglas descubiertas
- El algoritmo aprende patrones de datos históricos etiquetados
- Ejemplo: El modelo descubre automáticamente que la combinación de mora, ratio de endeudamiento e historial crediticio predice el incumplimiento

**Aprendizaje Supervisado:**
- El modelo aprende de un dataset etiquetado (con respuestas conocidas)
- En nuestro caso: datos históricos de créditos con su estado final (Normal o Default)

### Ciclo de Vida del Machine Learning

SageMaker Canvas automatiza las 4 fases del ciclo de vida del ML:

1. **Preprocesamiento:**
   - Limpieza de datos (eliminación de duplicados, corrección de errores)
   - Manejo de valores nulos (imputación o eliminación)
   - Normalización de escalas (para que variables con diferentes rangos sean comparables)

2. **Ingeniería de Características (Feature Engineering):**
   - Transformación de datos brutos en atributos matemáticos
   - Creación de variables derivadas con significado financiero
   - Ejemplo: Ratio_Endeudamiento = Monto_Cuota / Ingreso_Mensual

3. **Entrenamiento:**
   - Ajuste iterativo de parámetros del modelo para minimizar el error
   - El algoritmo busca la mejor combinación de pesos para cada variable
   - Proceso automático en SageMaker Canvas

4. **Evaluación:**
   - Medición del desempeño del modelo frente a datos no vistos durante el entrenamiento
   - Uso de métricas específicas para el tipo de problema (clasificación binaria)

### Generalización vs Sobreajuste (Overfitting)

**Generalización:**
- Capacidad del modelo de predecir correctamente en datos nuevos (no vistos durante el entrenamiento)
- Objetivo principal del Machine Learning

**Overfitting (Sobreajuste):**
- El modelo memoriza el ruido y patrones específicos del dataset de entrenamiento
- Desempeño excelente en datos de entrenamiento, pero pobre en datos nuevos
- Equivalente a memorizar respuestas de un examen sin entender los conceptos

**Train/Validation Split:**
- División del dataset en dos conjuntos: 80% entrenamiento, 20% validación
- El modelo se entrena con el 80% y se evalúa con el 20% restante
- Esto permite detectar overfitting y medir la capacidad de generalización

### Roles de los Datos

**Target o Variable Objetivo:**
- La variable que queremos predecir
- En nuestro caso: **Estado_Credito** (0 = Normal, 1 = Default)

**Features o Características:**
- Variables de entrada que el modelo usa para hacer predicciones
- En nuestro caso: Ingreso_Mensual, Monto_Cuota, Dias_Mora, Historial_Crediticio, Ratio_Endeudamiento

**Dataset:**
- Conjunto total de datos históricos con todas las variables
- Cada fila representa un crédito con sus características y su estado final

### Clasificación Binaria

**Definición:**
- Tipo de problema de Machine Learning donde se predice una de dos clases mutuamente excluyentes
- En nuestro caso: Normal (0) o Default (1)

**Diferencias con otros tipos:**
- **Regresión:** Predice números continuos (ej: monto de pérdida esperada)
- **Clasificación Multiclase:** Predice una de múltiples categorías (ej: riesgo Bajo, Medio, Alto)

### Métricas de Evaluación

**Matriz de Confusión:**
- Tabla 2x2 que compara las predicciones del modelo con la realidad
- Filas: Realidad (Normal, Default)
- Columnas: Predicción (Normal, Default)

**Tipos de Error:**
- **Falso Negativo (FN):** El modelo predice Normal, pero en realidad es Default
  - **Error más peligroso para supervisión bancaria:** Riesgo invisible que puede comprometer la estabilidad del sistema financiero
- **Falso Positivo (FP):** El modelo predice Default, pero en realidad es Normal
  - Menos crítico: genera costos de análisis adicional, pero no oculta riesgo

**Accuracy (Exactitud):**
- Porcentaje de predicciones correctas sobre el total
- **Métrica potencialmente engañosa en datasets desbalanceados**
- Ejemplo: Si el 95% de los créditos son normales, un modelo que siempre predice "Normal" tendría 95% de accuracy, pero sería inútil

**F1 Score:**
- Métrica estándar de equilibrio entre Precision y Recall
- Más confiable que Accuracy en datasets desbalanceados
- Rango: 0 (peor) a 1 (mejor)

### AutoML e Interpretabilidad

**AutoML (Automated Machine Learning):**
- SageMaker Canvas prueba múltiples algoritmos automáticamente
- Selecciona el mejor modelo según las métricas de evaluación
- Realiza el Train/Validate Split automáticamente (80/20)

**Feature Importance (Importancia de Variables):**
- Peso porcentual de cada variable en la decisión del modelo
- Indica qué características son más relevantes para la predicción
- **Vital para explicabilidad regulatoria:** Permite justificar decisiones ante supervisores

## Diccionario de Datos

El archivo `bcrp-credit-risk.csv` (provisto por el instructor) contiene datos históricos de créditos con las siguientes variables:

| Variable | Tipo | Descripción | Relevancia Financiera |
|----------|------|-------------|----------------------|
| **ID_Prestamo** | Entero | Identificador técnico único del crédito | Identificación administrativa, no tiene poder predictivo |
| **Ingreso_Mensual** | Numérico | Flujo de caja neto mensual del deudor (soles) | Capacidad de generación de recursos para cumplir obligaciones |
| **Monto_Cuota** | Numérico | Obligación periódica de pago del crédito (soles) | Carga de la deuda, debe compararse con el ingreso |
| **Dias_Mora** | Entero | Días de retraso en el pago a la fecha de corte | Variable de comportamiento, indicador temprano de deterioro |
| **Historial_Crediticio** | Categórico | Calificación cualitativa del comportamiento pasado (Bueno, Regular, Malo) | Resumen del desempeño histórico en el sistema financiero |
| **Estado_Credito** | Binario | Variable objetivo: 0 = Normal, 1 = Default (incumplimiento) | Lo que queremos predecir |

**Contexto de Negocio:**

El Banco Central de Reserva del Perú (BCRP) utiliza modelos predictivos de riesgo crediticio para realizar **Pruebas de Estrés** del sistema financiero. Estas pruebas evalúan si un aumento en el incumplimiento (Default) podría comprometer la estabilidad del sistema financiero peruano.

Identificar correctamente los créditos en riesgo de incumplimiento permite:
- Anticipar necesidades de provisiones de capital
- Evaluar la resiliencia del sistema ante choques económicos
- Diseñar políticas macroprudenciales preventivas

## Instrucciones

### Paso 1: Verificación de Región

Antes de iniciar, es crítico verificar que está trabajando en la región correcta:

1. En la esquina superior derecha de la consola de AWS, verifique la región actual
2. Confirme que dice la región estipulada por el instructor
3. Si no es correcta, haga clic en el selector de región y seleccione la región indicada

**✓ Verificación:** La región mostrada en la esquina superior derecha coincide con la región del workshop.

### Paso 2: Navegar a SageMaker Canvas

1. En la barra de búsqueda global (parte superior de la consola), escriba `SageMaker`
2. Haga clic en **Amazon SageMaker** en los resultados
3. En el panel de navegación de la izquierda, desplácese hacia abajo hasta la sección **Machine Learning**
4. Haga clic en **Canvas**
5. Si es la primera vez que accede a SageMaker Canvas, el sistema puede solicitar crear un dominio:
   - Haga clic en el botón naranja **Configuración rápida** (Quick Setup)
   - Espere 3-5 minutos mientras se aprovisiona el dominio
6. Una vez que el dominio esté listo, haga clic en **Abrir Canvas** (Open Canvas)

**✓ Verificación:** La interfaz de SageMaker Canvas se ha abierto en una nueva pestaña del navegador.

### Paso 3: Importar Dataset

1. En la interfaz de SageMaker Canvas, haga clic en **Datasets** en el panel izquierdo
2. Haga clic en el botón **Importar** (Import)
3. Seleccione la opción **Cargar desde el equipo** (Upload from local)
4. Haga clic en **Seleccionar archivos** y navegue hasta el archivo `bcrp-credit-risk.csv` ubicado en esta carpeta del repositorio (`dia-4/lab-4.1-sagemaker-canvas/bcrp-credit-risk.csv`)
5. Haga clic en **Importar**
6. Espere a que la importación se complete (barra de progreso al 100%)

**✓ Verificación:** El dataset `bcrp-credit-risk.csv` aparece en la lista de datasets con estado "Listo" (Ready). Al hacer clic en el dataset, puede ver una vista previa de las columnas: ID_Prestamo, Ingreso_Mensual, Monto_Cuota, Dias_Mora, Historial_Crediticio, Estado_Credito.

### Paso 4: Perfilado de Datos

1. Haga clic en el dataset `bcrp-credit-risk.csv` para abrirlo
2. SageMaker Canvas mostrará automáticamente el perfilado de datos (Data Profile)
3. Verifique los tipos de datos reconocidos:
   - **Numéricos:** Ingreso_Mensual, Monto_Cuota, Dias_Mora
   - **Categóricos:** Historial_Crediticio, Estado_Credito
   - **Identificador:** ID_Prestamo
4. En la sección de **Calidad de Datos** (Data Quality), verifique que no hay valores nulos en columnas críticas:
   - Monto_Cuota: 0% valores nulos
   - Ingreso_Mensual: 0% valores nulos
5. Desplácese hasta la sección de **Correlaciones** (Correlations)
6. Observe la relación estadística entre **Dias_Mora** y **Estado_Credito**
   - Una correlación positiva indica que a mayor mora, mayor probabilidad de Default

**✓ Verificación:** El perfilado muestra que todas las columnas críticas tienen 0% de valores nulos. La matriz de correlación muestra una relación positiva entre Dias_Mora y Estado_Credito, confirmando la viabilidad técnica del modelo.

### Paso 5: Ingeniería de Características

Ahora crearemos un indicador financiero clave mediante una fórmula calculada:

1. En la vista del dataset, haga clic en el botón **Agregar columna calculada** (Add calculated column)
2. En el campo **Nombre de la columna**, ingrese: `Ratio_Endeudamiento`
3. En el campo **Fórmula**, ingrese exactamente: `Monto_Cuota / Ingreso_Mensual`
4. Haga clic en **Vista previa** (Preview) para verificar que la fórmula es correcta
5. Haga clic en **Crear** (Create)

**Sustento Técnico-Financiero del Ratio de Endeudamiento (DTI - Debt to Income):**

El Ratio de Endeudamiento mide qué porción del ingreso mensual se destina al servicio de la deuda. Es un indicador crítico de solvencia:

- **Ratio < 0.30 (30%):** Nivel saludable de endeudamiento
- **Ratio 0.30 - 0.40:** Zona de precaución, capacidad de pago ajustada
- **Ratio > 0.40 (40%):** Vulnerabilidad alta, el deudor destina una porción excesiva de sus ingresos al servicio de la deuda, aumentando exponencialmente su vulnerabilidad ante choques económicos (pérdida de empleo, enfermedad, inflación)

Este ratio es ampliamente utilizado por entidades financieras y supervisores para evaluar la capacidad de pago de los deudores.

**✓ Verificación:** La nueva columna `Ratio_Endeudamiento` aparece en el dataset. Al revisar los valores, puede observar que son decimales entre 0 y 1 (ej: 0.24 representa 24% del ingreso destinado a la cuota).

### Paso 6: Entrenamiento Quick Build

1. En la interfaz de SageMaker Canvas, haga clic en **Modelos** (Models) en el panel izquierdo
2. Haga clic en el botón naranja **Nuevo modelo** (New model)
3. Ingrese el nombre del modelo: `modelo-riesgo-credito-{nombre-participante}`
4. Seleccione el tipo de problema: **Clasificación binaria** (Binary classification)
5. Haga clic en **Crear** (Create)
6. En la pantalla de configuración del modelo:
   - Seleccione el dataset: `bcrp-credit-risk.csv`
   - Seleccione la variable objetivo (Target): **Estado_Credito**
7. SageMaker Canvas analizará automáticamente el dataset y sugerirá el tipo de modelo
8. Haga clic en **Quick Build** (Compilación rápida)
9. Haga clic en **Iniciar** (Start)

⏱️ **Nota:** El entrenamiento Quick Build puede tardar entre 2 y 15 minutos dependiendo de la complejidad del dataset. SageMaker Canvas está ejecutando automáticamente las 4 fases del ciclo de vida del ML: preprocesamiento, ingeniería de características, entrenamiento y evaluación.

**✓ Verificación:** El estado del modelo cambia a "Entrenando" (Training). Una vez completado, el estado cambiará a "Listo" (Ready) y podrá ver las métricas de evaluación.

### Paso 7: Evaluación del Modelo

Una vez que el entrenamiento se complete:

1. Haga clic en la pestaña **Analizar** (Analyze) del modelo
2. Revise las métricas generales:
   - **F1 Score:** Métrica principal de equilibrio (valor entre 0 y 1, más alto es mejor)
   - **Accuracy:** Porcentaje de predicciones correctas (tenga en cuenta que puede ser engañosa en datasets desbalanceados)
3. Desplácese hasta la sección **Matriz de Confusión** (Confusion Matrix)
4. Identifique los **Falsos Negativos (FN):**
   - Fila: Default (1)
   - Columna: Predicción Normal (0)
   - **Interpretación crítica:** Estos son créditos en incumplimiento que el modelo clasificó como sanos. Este es el error de mayor impacto negativo para la supervisión de estabilidad, ya que representa riesgo invisible.
5. Compare con los **Falsos Positivos (FP):**
   - Fila: Normal (0)
   - Columna: Predicción Default (1)
   - Menos crítico: genera costos de análisis adicional, pero no oculta riesgo
6. Desplácese hasta la sección **Importancia de Variables** (Feature Importance)
7. Verifique que el **Ratio_Endeudamiento** creado en el Paso 5 tiene un peso relevante en la decisión del modelo
8. Observe las otras variables con mayor importancia (probablemente Dias_Mora e Historial_Crediticio)

**✓ Verificación:** La Matriz de Confusión muestra la distribución de predicciones correctas e incorrectas. El análisis de importancia de variables muestra que Ratio_Endeudamiento tiene un peso significativo (generalmente entre 15% y 30%), validando que la ingeniería de características fue efectiva.

### Paso 8: Auditoría de Código

SageMaker Canvas genera automáticamente código Python auditable. Accederemos a él para validar la transparencia del enfoque low-code:

1. En la vista del modelo, haga clic en el botón **Ver notebook** (View Notebook) en la esquina superior derecha
2. Se abrirá un Jupyter Notebook con el código Python generado automáticamente
3. Desplácese por el notebook hasta encontrar la sección de **Ingeniería de Características** (Feature Engineering)
4. Localice el código de la librería Pandas que ejecuta la fórmula del ratio de endeudamiento
5. Verifique que el código refleja la fórmula: `df['Ratio_Endeudamiento'] = df['Monto_Cuota'] / df['Ingreso_Mensual']`

**Importancia de la Auditabilidad:**

Este paso demuestra que el enfoque low-code de SageMaker Canvas es completamente auditable y convertible en código de producción. Para entidades reguladas como el BCRP, es fundamental poder:
- Explicar cada transformación de datos
- Reproducir el modelo en entornos de producción
- Cumplir con requisitos de transparencia regulatoria

**✓ Verificación:** El notebook muestra código Python legible con la fórmula exacta del Ratio_Endeudamiento. Puede ver las librerías utilizadas (Pandas, Scikit-learn) y el flujo completo del preprocesamiento.

## Resumen del Laboratorio

Ha completado exitosamente el laboratorio de Machine Learning con SageMaker Canvas. Los logros principales incluyen:

- Comprensión del paradigma de Machine Learning supervisado y su diferencia con la programación tradicional
- Importación y perfilado de un dataset financiero de riesgo crediticio
- Creación de una variable calculada (Ratio_Endeudamiento) con sustento financiero
- Entrenamiento de un modelo de clasificación binaria usando Quick Build
- Interpretación de la Matriz de Confusión y comprensión del impacto de los Falsos Negativos en supervisión bancaria
- Validación de la importancia de variables y efectividad de la ingeniería de características
- Acceso al código Python generado para validar la auditabilidad del modelo

## Solución de Problemas

Si encuentra dificultades durante este laboratorio, consulte la [Guía de Solución de Problemas](../TROUBLESHOOTING.md) que contiene soluciones a errores comunes.

**Errores que requieren asistencia del instructor:**
- Errores de permisos IAM al acceder a SageMaker Canvas
- Errores de límites de cuota de SageMaker
- Problemas de aprovisionamiento del dominio de SageMaker

## Gestión del Ciclo de Vida de Recursos

⚠️ **Importante:** NO elimine el modelo de SageMaker Canvas ni el dominio al finalizar este laboratorio si planea continuar con otros laboratorios del Día 4 o si el instructor indica mantener los recursos activos.

**Advertencia de costos:** Los recursos de SageMaker Canvas (dominio y modelos) generan costos mientras están activos. Si no continuará con el workshop, consulte la [Guía de Limpieza de Recursos](../limpieza/README.md) para instrucciones de eliminación.

**Recursos compartidos - NO modificar:**
- Dataset `bcrp-credit-risk.csv` provisto por el instructor
- Políticas IAM base para SageMaker

**Recursos propios (pueden eliminarse al finalizar el workshop):**
- Modelo: `modelo-riesgo-credito-{nombre-participante}`
- Dominio de SageMaker Canvas (si fue creado durante este laboratorio)
