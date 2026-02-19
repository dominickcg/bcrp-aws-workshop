# 🤖 Día 4 - Inteligencia Artificial y Machine Learning

## Introducción

¡Bienvenidos al Día 4 del Workshop BCRP de AWS! En esta jornada final, exploraremos el fascinante mundo de la Inteligencia Artificial y el Machine Learning, dos tecnologías que están transformando la manera en que las instituciones financieras analizan datos, predicen riesgos y toman decisiones estratégicas.

Durante este día, trabajaremos con dos servicios clave de AWS: **Amazon SageMaker Canvas** para construir modelos de Machine Learning sin escribir código, y **Amazon Bedrock** para experimentar con modelos de IA Generativa de última generación. Aplicaremos estos conocimientos a casos de uso reales del sector financiero, desde la predicción de riesgo crediticio hasta la creación de aplicaciones inteligentes con controles de seguridad robustos.

## Agenda del Día

| Horario | Actividad | Duración |
|---------|-----------|----------|
| 09:00 - 09:10 | Introducción a IA y ML en AWS | 10 minutos |
| 09:10 - 10:00 | Lab 4.1: Machine Learning con SageMaker Canvas | 50 minutos |
| 10:00 - 11:20 | Lab 4.2: IA Generativa con Amazon Bedrock | 80 minutos |
| 11:20 - 11:30 | Revisión y preguntas | 10 minutos |

**Duración total**: 2 horas 30 minutos

## Conceptos Clave

Antes de comenzar con los laboratorios, es importante familiarizarse con estos conceptos fundamentales:

- **Amazon SageMaker Canvas**: Servicio de AWS que permite construir modelos de Machine Learning mediante una interfaz visual sin necesidad de escribir código, automatizando tareas como preprocesamiento de datos, selección de algoritmos y evaluación de modelos.

- **Amazon Bedrock**: Servicio de AWS que proporciona acceso a modelos fundacionales (Foundation Models) de IA Generativa a través de una API unificada, permitiendo experimentar con diferentes modelos de texto, imagen y multimodales sin gestionar infraestructura.

- **Foundation Models (Modelos Fundacionales)**: Modelos de IA de propósito general entrenados con grandes volúmenes de datos que pueden adaptarse a múltiples tareas mediante técnicas de prompting, sin necesidad de reentrenamiento.

- **Machine Learning Tradicional vs IA Generativa**: El ML tradicional aprende patrones de datos históricos para hacer predicciones (clasificación, regresión), mientras que la IA Generativa crea contenido nuevo (texto, imágenes, código) basándose en patrones aprendidos de grandes corpus de datos.

## Laboratorios

### 🧠 [Laboratorio 4.1: Machine Learning con SageMaker Canvas](./lab-4.1-sagemaker-canvas/README.md)

Construya un modelo predictivo de riesgo crediticio utilizando SageMaker Canvas. Aprenderá a importar datos financieros, crear indicadores de solvencia mediante ingeniería de características, entrenar un modelo de clasificación binaria y evaluar su desempeño mediante métricas críticas para la supervisión bancaria.

**Duración**: 50 minutos

**Temas cubiertos**:
- Ciclo de vida del Machine Learning
- Ingeniería de características financieras
- Evaluación de modelos y métricas de riesgo
- Auditoría de código generado automáticamente

### 🤖 [Laboratorio 4.2: IA Generativa con Amazon Bedrock](./lab-4.2-bedrock/README.md)

Experimente con modelos fundacionales de IA Generativa, configure controles de seguridad mediante Guardrails y despliegue una aplicación web que consume la API de Bedrock. Este laboratorio está dividido en tres partes: experimentación con modelos, configuración de gobernanza y despliegue de aplicación.

**Duración**: 80 minutos (Parte 1: 25 min, Parte 2: 20 min, Parte 3: 35 min)

**Temas cubiertos**:
- Comparativa de modelos fundacionales
- Técnicas de prompting avanzado (Zero-Shot, Few-Shot, Chain-of-Thought)
- Configuración de Guardrails para filtrado de contenido y protección de datos sensibles
- Despliegue de aplicación con CloudFormation e integración con Bedrock

## Prerequisitos del Día 4

Para aprovechar al máximo este día, debe tener conocimientos de los días anteriores:

- **Día 1 - Fundamentos de AWS**: Navegación en la consola de AWS, comprensión de regiones y zonas de disponibilidad, familiaridad con la interfaz de servicios.

- **Día 2 - Almacenamiento y Alta Disponibilidad**: Experiencia con Amazon S3 para almacenamiento de datos, conocimiento de CloudFormation para despliegue de infraestructura como código (Lab 2.3).

- **Día 3 - Seguridad y Gobernanza**: Comprensión de roles IAM y políticas de permisos, aplicación del principio de mínimo privilegio (Lab 3.2).

⚠️ **Importante**: Si eliminó recursos de días anteriores, no afectará la ejecución de los laboratorios del Día 4, ya que trabajaremos con servicios independientes (SageMaker Canvas y Bedrock). Sin embargo, los conceptos aprendidos sobre IAM, CloudFormation y mejores prácticas de seguridad serán fundamentales para este día.

## Recursos Compartidos del Instructor

El instructor ha provisto los siguientes recursos para los laboratorios del Día 4. **NO modifique estos recursos**:

1. **Dataset de riesgo crediticio**: `bcrp-credit-risk.csv`
   - Archivo CSV con datos históricos de préstamos para el Lab 4.1
   - Contiene variables financieras: Ingreso_Mensual, Monto_Cuota, Dias_Mora, Historial_Crediticio, Estado_Credito
   - **Recurso compartido - NO modificar**

2. **Plantilla CloudFormation**: `genai-app.yaml`
   - Plantilla para desplegar la aplicación web de Bedrock en el Lab 4.2 Parte 3
   - Crea instancia EC2 y rol IAM con permisos para invocar modelos de Bedrock
   - **Recurso compartido - NO modificar**

3. **Políticas IAM base**:
   - Políticas predefinidas para acceso a SageMaker Canvas y Amazon Bedrock
   - Configuradas por el instructor con permisos de mínimo privilegio
   - **Recurso compartido - NO modificar**

## Solución de Problemas

Si encuentra dificultades durante los laboratorios del Día 4, consulte la [Guía de Solución de Problemas](./TROUBLESHOOTING.md) que contiene soluciones detalladas a errores comunes organizados por laboratorio.

**Errores que requieren asistencia del instructor:**

⚠️ Si encuentra alguno de estos errores, notifique al instructor de inmediato:

- **Errores de permisos IAM**: Mensajes "Access Denied" o "Not Authorized" al acceder a SageMaker Canvas o Bedrock
- **Errores de límites de cuota**: Mensajes indicando que se ha excedido la cuota de SageMaker o Bedrock en la cuenta
- **Problemas de acceso a modelos**: Modelos de Bedrock no disponibles en el Chat Playground o errores al invocar modelos

No intente solucionar estos errores por su cuenta, ya que requieren ajustes a nivel de cuenta que solo el instructor puede realizar.

## Limpieza de Recursos (Opcional)

Al finalizar el Día 4, puede optar por eliminar los recursos creados durante los laboratorios. Consulte la [Guía de Limpieza de Recursos](./limpieza/README.md) para instrucciones detalladas.

⚠️ **Advertencia de costos**: Los recursos de IA pueden generar costos si se mantienen activos:

- **SageMaker Canvas**: Los modelos entrenados y el dominio de Canvas generan costos por almacenamiento y cómputo
- **Instancias EC2**: La aplicación web desplegada en el Lab 4.2 utiliza una instancia EC2 que genera costos por hora de ejecución

**Nota**: Este es el último día del workshop. Si no planea continuar experimentando con estos servicios, se recomienda realizar la limpieza completa de recursos al finalizar.

---

¡Comencemos a explorar el poder de la Inteligencia Artificial en AWS!
