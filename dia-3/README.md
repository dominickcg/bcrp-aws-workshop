# 🔒 Día 3 - Seguridad, Identidad y Gobernanza

## Introducción

Bienvenido al tercer día del Workshop AWS BCRP. Después de construir una infraestructura de red sólida en el Día 1 y desplegar servicios de almacenamiento y alta disponibilidad en el Día 2, hoy nos enfocaremos en los aspectos críticos de **seguridad**, **gestión de identidades** y **gobernanza** en AWS.

En este día aprenderás a proteger tus aplicaciones web con AWS WAF, gestionar accesos de forma segura mediante roles IAM y Session Manager, y auditar todas las acciones realizadas en tu cuenta AWS. Estos conocimientos son fundamentales para construir arquitecturas empresariales que cumplan con estándares de seguridad y mejores prácticas de la industria.

## Agenda del Día

| Horario | Actividad | Duración |
|---------|-----------|----------|
| 10:00 - 10:10 | Introducción al Día 3 | 10 minutos |
| 10:10 - 11:00 | **Laboratorio 3.1**: Protección Perimetral con AWS WAF | 50 minutos |
| 11:00 - 11:50 | **Laboratorio 3.2**: Gestión de Identidades y Acceso Seguro | 50 minutos |
| 11:50 - 12:40 | **Laboratorio 3.3**: Gobernanza y Auditoría | 50 minutos |
| 12:40 - 12:50 | Revisión y preguntas | 10 minutos |

**Duración total:** 2 horas 50 minutos (170 minutos)

## Conceptos Clave

En este día aprenderás sobre:

- **AWS WAF (Web Application Firewall)**: Firewall de aplicaciones web que protege contra ataques comunes de internet como inyección SQL y cross-site scripting (XSS). Opera en la capa 7 del modelo OSI, inspeccionando el contenido de las solicitudes HTTP/HTTPS.

- **IAM Roles**: Identidades de AWS que definen permisos sin necesidad de credenciales permanentes. Los roles pueden ser asumidos por servicios de AWS (como EC2) para acceder a otros recursos de forma segura.

- **AWS Systems Manager Session Manager**: Servicio que permite acceso seguro a instancias EC2 sin necesidad de abrir puertos SSH, gestionar claves privadas o usar bastions hosts. Toda la actividad queda registrada para auditoría.

- **AWS CloudTrail**: Servicio de auditoría que registra todas las llamadas a la API de AWS, permitiendo rastrear quién hizo qué, cuándo y desde dónde. Esencial para cumplimiento normativo y análisis de seguridad.

- **AWS Trusted Advisor**: Herramienta que analiza tu cuenta AWS y proporciona recomendaciones en cinco categorías: optimización de costos, rendimiento, seguridad, tolerancia a fallos y límites de servicio.

## Laboratorios

### 🛡️ [Laboratorio 3.1 - Protección Perimetral con AWS WAF](./lab-3.1-waf/README.md)
Implementa un firewall de aplicaciones web para proteger tu Application Load Balancer contra ataques comunes como inyección SQL. Aprende la diferencia entre protección de red (Security Groups) y protección de aplicación (WAF).

**Duración**: 50 minutos

### 👤 [Laboratorio 3.2 - Gestión de Identidades y Acceso Seguro](./lab-3.2-iam-ssm/README.md)
Crea roles IAM con permisos de acceso a S3 y conecta a instancias EC2 de forma segura usando Session Manager, eliminando la necesidad de SSH y claves privadas.

**Duración**: 50 minutos

### 📊 [Laboratorio 3.3 - Gobernanza y Auditoría](./lab-3.3-governance/README.md)
Utiliza CloudTrail para auditar acciones realizadas en tu cuenta AWS y revisa recomendaciones de seguridad y optimización de costos con Trusted Advisor.

**Duración**: 50 minutos

## Prerequisitos del Día 2

Para completar los laboratorios del Día 3, debes tener activos los siguientes recursos creados en el Día 2:

- **Application Load Balancer (ALB)**: Balanceador de carga creado en el Lab 2.3
- **Auto Scaling Group (ASG)**: Grupo de auto escalado con instancias EC2 en ejecución (Lab 2.3)
- **Launch Template**: Plantilla de lanzamiento asociada al ASG (Lab 2.3)
- **Bucket S3**: Bucket con sitio web estático creado en el Lab 2.1
- **Security Groups**: Configurados para el ALB y las instancias EC2

⚠️ **Importante**: Si eliminaste alguno de estos recursos al finalizar el Día 2, deberás recrearlos antes de comenzar los laboratorios del Día 3.

## Recursos Compartidos del Instructor

El instructor ha provisionado los siguientes recursos compartidos que utilizarás durante los laboratorios:

- **Políticas IAM base**: Políticas gestionadas de AWS disponibles para todos los participantes
- **CloudTrail**: Servicio de auditoría ya configurado a nivel de cuenta

⚠️ **Recurso compartido - NO modificar**: Estos recursos son utilizados por todos los participantes. No los modifiques ni elimines.

## Solución de Problemas

Si encuentras dificultades durante los laboratorios, consulta la [Guía de Solución de Problemas](./TROUBLESHOOTING.md) que contiene soluciones a errores comunes organizados por laboratorio.

**Errores que requieren asistencia del instructor:**
- Errores de permisos IAM
- Errores de límites de cuota de AWS
- Problemas para acceder a CloudTrail o Trusted Advisor

## Limpieza de Recursos (Opcional)

Al finalizar el Día 3, puedes opcionalmente eliminar los recursos creados. Consulta la [Guía de Limpieza de Recursos](./limpieza/README.md) para instrucciones detalladas.

**Nota**: Esta limpieza es completamente opcional. Solo realícela si no continuará con el Día 4 del workshop.

---

**¡Estás listo para comenzar!** Dirígete al [Lab 3.1: Protección Perimetral con AWS WAF](./lab-3.1-waf/README.md) para iniciar el día 3.
