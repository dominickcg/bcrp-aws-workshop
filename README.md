# ☁️ Workshop AWS - Fundamentos de Computación en la Nube

Bienvenido al Workshop de Amazon Web Services (AWS). Este programa de capacitación técnica está diseñado para proporcionar conocimientos prácticos sobre los servicios fundamentales de AWS mediante laboratorios hands-on en un entorno real.

## 📋 Descripción General

Este workshop cubre los pilares fundamentales de AWS a través de cuatro días de capacitación intensiva, desde conceptos básicos de infraestructura hasta servicios avanzados de inteligencia artificial. Cada día combina teoría esencial con laboratorios prácticos guiados paso a paso.

### Nivel de Conocimiento

El contenido está diseñado para participantes con nivel **AWS Certified Cloud Practitioner** o conocimientos equivalentes. Se asume familiaridad básica con:

- Conceptos fundamentales de computación en la nube
- Terminología básica de redes y sistemas

### Metodología

- **Enfoque práctico:** Laboratorios hands-on con recursos reales de AWS
- **Entorno compartido:** Múltiples participantes trabajando en la misma cuenta AWS
- **Guías detalladas:** Instrucciones paso a paso en español con verificaciones visuales
- **Consola AWS:** Todas las actividades se realizan mediante la interfaz gráfica

## 📅 Programa del Workshop

### Día 1 - Fundamentos de AWS: Redes y Cómputo
**Duración:** 3 horas

Aprende a configurar la infraestructura base de AWS, incluyendo redes virtuales aisladas y servidores virtuales con configuración automatizada.

**Laboratorios:**
- Lab 1.1: Configuración de VPC y Subredes
- Lab 1.2: Despliegue de Instancias EC2

📖 [Ver contenido del Día 1](./dia-1/README.md)

---

### Día 2 - Almacenamiento, Bases de Datos y Alta Disponibilidad
**Duración:** 3 horas

Explora servicios de almacenamiento persistente y de objetos, bases de datos administradas en configuración Multi-AZ, y arquitecturas web escalables con balanceadores de carga y auto scaling.

**Laboratorios:**
- Lab 2.1: Almacenamiento EBS y S3
- Lab 2.2: Base de Datos RDS Multi-AZ
- Lab 2.3: Elasticidad con ELB, ASG y CloudFormation

📖 [Ver contenido del Día 2](./dia-2/README.md)

---

### Día 3 - Seguridad, Identidad y Gobernanza
**Duración:** 2 horas 50 minutos

Implementa mejores prácticas de seguridad, gestión de identidades y accesos, y políticas de gobernanza en AWS. Aprende a proteger aplicaciones web con AWS WAF, gestionar accesos mediante roles IAM y Session Manager, y auditar acciones con CloudTrail y Trusted Advisor.

**Laboratorios:**
- Lab 3.1: Protección Perimetral con AWS WAF
- Lab 3.2: Gestión de Identidades y Acceso Seguro (IAM y Session Manager)
- Lab 3.3: Gobernanza y Auditoría (CloudTrail y Trusted Advisor)

📖 [Ver contenido del Día 3](./dia-3/README.md)

---

### Día 4 - Inteligencia Artificial y Machine Learning
**Duración:** 2 horas 30 minutos

Explora servicios de inteligencia artificial y machine learning de AWS. Aprende a construir modelos de ML sin código usando SageMaker Canvas y a implementar aplicaciones de IA generativa con Amazon Bedrock, incluyendo técnicas de prompt engineering y controles de seguridad con Guardrails.

**Laboratorios:**
- Lab 4.1: Machine Learning con SageMaker Canvas (50 minutos)
- Lab 4.2: IA Generativa con Amazon Bedrock (80 minutos)

📖 [Ver contenido del Día 4](./dia-4/README.md)

---

## 📁 Estructura del Repositorio

```
bcrp-aws-workshop/
├── README.md                                    # Este archivo
├── acceso-aws/                                  # Guía de acceso a AWS
├── dia-1/                                       # Día 1: Redes y Cómputo
│   ├── README.md                                # Guía principal del Día 1
│   ├── TROUBLESHOOTING.md                       # Solución de problemas
│   ├── lab-1.1-vpc/                             # Lab: VPC y Subredes
│   ├── lab-1.2-ec2/                             # Lab: EC2
│   └── limpieza/                                # Guía de limpieza opcional
├── dia-2/                                       # Día 2: Almacenamiento, BD y HA
│   ├── README.md                                # Guía principal del Día 2
│   ├── TROUBLESHOOTING.md                       # Solución de problemas
│   ├── lab-2.1-storage/                         # Lab: EBS y S3
│   ├── lab-2.2-rds/                             # Lab: RDS Multi-AZ
│   ├── lab-2.3-ha-elb-asg/                      # Lab: ELB, ASG y CloudFormation
│   └── limpieza/                                # Guía de limpieza opcional
├── dia-3/                                       # Día 3: Seguridad, Identidad y Gobernanza
│   ├── README.md                                # Guía principal del Día 3
│   ├── TROUBLESHOOTING.md                       # Solución de problemas
│   ├── lab-3.1-waf/                             # Lab: AWS WAF
│   ├── lab-3.2-iam-ssm/                         # Lab: IAM y Session Manager
│   ├── lab-3.3-governance/                      # Lab: CloudTrail y Trusted Advisor
│   └── limpieza/                                # Guía de limpieza opcional
├── dia-4/                                       # Día 4: IA y Machine Learning
│   ├── README.md                                # Guía principal del Día 4
│   ├── TROUBLESHOOTING.md                       # Solución de problemas
│   ├── lab-4.1-sagemaker-canvas/                # Lab: SageMaker Canvas
│   ├── lab-4.2-bedrock/                         # Lab: Amazon Bedrock
│   └── limpieza/                                # Guía de limpieza opcional
```

## 🎯 Objetivos de Aprendizaje

Al completar este workshop, serás capaz de:

### Fundamentos de Infraestructura
- Diseñar y configurar redes virtuales aisladas en AWS
- Desplegar y gestionar instancias de cómputo escalables
- Implementar soluciones de almacenamiento persistente y de objetos

### Arquitectura en la Nube
- Aplicar principios de alta disponibilidad y elasticidad
- Diseñar arquitecturas resilientes y tolerantes a fallos
- Implementar estrategias de monitoreo y observabilidad

### Seguridad y Gobernanza
- Aplicar el principio de mínimo privilegio
- Implementar controles de acceso y políticas de seguridad
- Establecer prácticas de gobernanza y cumplimiento

### Servicios Avanzados
- Utilizar servicios de bases de datos administradas
- Implementar soluciones de inteligencia artificial
- Integrar servicios de machine learning en aplicaciones

## 🚀 Cómo Usar Este Repositorio

### Para Participantes

**Sigue las guías en orden:**
- Lee el README principal del día
- Completa cada laboratorio secuencialmente
- Consulta el troubleshooting o al instructor si encuentras problemas

### Para Instructores

- Cada día incluye documentación de requerimientos y directrices
- Los laboratorios están diseñados para 50 minutos cada uno
- Se proporcionan archivos de soporte (scripts, políticas, código)
- Incluye secciones de troubleshooting y verificación

## ⚙️ Requisitos Previos

### Acceso Técnico
- Cuenta de AWS proporcionada por el instructor
- Navegador web moderno (Chrome, Firefox, Edge, Safari)
- Conexión a internet estable

### Conocimientos Recomendados
- Conceptos básicos de redes (IP, subredes, enrutamiento)
- Familiaridad con sistemas operativos Linux
- Comprensión de arquitecturas cliente-servidor
- Conocimientos básicos de línea de comandos

### Acceso a AWS

Antes de comenzar cualquier laboratorio, asegúrate de tener acceso configurado:

- Cuenta de AWS activa proporcionada por el instructor
- Credenciales de inicio de sesión (usuario y contraseña)
- Acceso a la Consola de AWS en español

**📖 Consulta la [Guía de Acceso a Cuenta AWS](./acceso-aws/README.md) para instrucciones detalladas sobre cómo iniciar sesión por primera vez, cambiar tu contraseña y configurar el idioma de la consola.**

### Verificación de Región

**IMPORTANTE:** Todos los participantes deben trabajar en la misma región de AWS designada por el instructor.

**Pasos para verificar:**

1. Inicia sesión en la Consola de AWS con el enlace brindado por el instructor
2. En la esquina superior derecha, junto a tu nombre de usuario, verás el nombre de la región actual
3. Haz clic en el nombre de la región para abrir el menú desplegable
4. Selecciona la región indicada por el instructor (ejemplo: **US East (N. Virginia) us-east-1**)
5. Verifica que la región correcta aparece en la esquina superior derecha

**Nota:** Si trabajas en una región diferente, tus recursos no serán visibles para el instructor y podrías tener problemas de conectividad con recursos compartidos.

## 🔒 Consideraciones de Seguridad

Este workshop utiliza un **entorno compartido** donde múltiples participantes trabajan en la misma cuenta AWS. Es fundamental seguir estas reglas:

- ✅ Usa nomenclatura con tu identificador único en todos los recursos
- ❌ NO modifiques recursos de otros participantes
- ❌ NO elimines recursos compartidos del instructor
- ❌ NO recrees recursos que ya existen

## 📚 Recursos Adicionales

### Documentación Oficial de AWS

- [AWS Documentation](https://docs.aws.amazon.com/) - Documentación completa de todos los servicios
- [AWS Getting Started](https://aws.amazon.com/getting-started/) - Guías de inicio rápido
- [AWS Architecture Center](https://aws.amazon.com/architecture/) - Mejores prácticas y patrones de arquitectura
- [AWS Well-Architected Framework](https://aws.amazon.com/architecture/well-architected/) - Marco de buenas prácticas
- [AWS Whitepapers](https://aws.amazon.com/whitepapers/) - Documentos técnicos y guías estratégicas

### Capacitación y Certificación

- [AWS Training and Certification](https://aws.amazon.com/training/) - Cursos oficiales de AWS
- [AWS Skill Builder](https://skillbuilder.aws/) - Plataforma de aprendizaje gratuita
- [AWS Certification](https://aws.amazon.com/certification/) - Información sobre certificaciones

### Comunidad y Soporte

- [AWS Forums](https://forums.aws.amazon.com/) - Foros de la comunidad
- [AWS re:Post](https://repost.aws/) - Plataforma de preguntas y respuestas
- [AWS Blog](https://aws.amazon.com/blogs/) - Noticias y actualizaciones

## 🤝 Contribuciones

Este repositorio contiene material de capacitación técnica. Si encuentras errores o tienes sugerencias de mejora, por favor contacta al equipo de desarrollo del workshop.

## 📄 Licencia

Este proyecto está licenciado bajo la [Licencia MIT](./LICENSE). Copyright © 2026 AMBER CLOUD GLOBAL LLC.

---

**¿Listo para comenzar?** Dirígete al [Día 1: Fundamentos de AWS](./dia-1/README.md) para iniciar tu viaje en la nube.