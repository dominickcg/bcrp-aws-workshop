# 🚀 Día 2 - Almacenamiento, Bases de Datos y Alta Disponibilidad

## Introducción

Bienvenido al segundo día del Workshop AWS BCRP. Hoy avanzaremos desde los fundamentos de redes y cómputo del Día 1 hacia servicios más especializados que permiten construir aplicaciones empresariales completas en la nube.

El enfoque de este día está en tres pilares fundamentales: **almacenamiento persistente** (EBS y S3), **bases de datos administradas** (RDS Multi-AZ) y **arquitecturas de alta disponibilidad y elasticidad** (ALB, ASG y CloudFormation). Aprenderás a integrar estos servicios para crear soluciones robustas, escalables y resilientes que pueden adaptarse automáticamente a la demanda y mantener disponibilidad continua ante fallos de infraestructura.

## Agenda del Día

| Horario | Actividad | Duración |
|---------|-----------|----------|
| 14:00 - 14:10 | Introducción al Día 2 | 10 minutos |
| 14:10 - 15:00 | **Laboratorio 2.1**: Almacenamiento de Bloques y Hosting de Objetos | 50 minutos |
| 15:00 - 15:40 | **Laboratorio 2.2 (Parte A)**: Configuración de RDS Multi-AZ | 40 minutos |
| 15:40 - 15:45 | ⏱️ Espera: Aprovisionamiento de instancia RDS | 5 minutos |
| 15:45 - 15:55 | **Laboratorio 2.2 (Parte B)**: Verificación de RDS | 10 minutos |
| 15:55 - 16:20 | **Laboratorio 2.3 (Parte A)**: Balanceador de Carga y CloudFormation | 25 minutos |
| 16:20 - 16:25 | ⏱️ Espera: Despliegue de pila CloudFormation | 5 minutos |
| 16:25 - 16:50 | **Laboratorio 2.3 (Parte B)**: Verificación, Pruebas y Monitoreo | 25 minutos |
| 16:50 - 17:00 | Revisión y preguntas | 10 minutos |

## Conceptos Clave

En este día aprenderás sobre:

- **Amazon EBS (Elastic Block Store)**: Almacenamiento de bloques persistente para instancias EC2, ideal para datos que requieren acceso frecuente y baja latencia.

- **Amazon S3 (Simple Storage Service)**: Almacenamiento de objetos escalable y duradero, perfecto para hosting de sitios web estáticos, backups y distribución de contenido.

- **Amazon RDS Multi-AZ**: Servicio de base de datos relacional administrado con replicación automática entre zonas de disponibilidad para garantizar alta disponibilidad.

- **Application Load Balancer (ALB)**: Balanceador de carga que distribuye tráfico HTTP/HTTPS entre múltiples instancias en diferentes zonas de disponibilidad.

- **Auto Scaling Group (ASG)**: Servicio que ajusta automáticamente la cantidad de instancias EC2 según la demanda, proporcionando elasticidad a la aplicación.

- **Amazon CloudWatch**: Servicio de monitoreo que recopila métricas y permite crear alarmas para activar acciones automáticas.

- **AWS CloudFormation**: Infraestructura como código que permite definir y provisionar recursos de AWS de manera automatizada y reproducible.

## Laboratorios

### 💾 [Laboratorio 2.1 - Almacenamiento de Bloques y Hosting de Objetos](./lab-2.1-storage/README.md)
Aprende a crear y montar volúmenes EBS para almacenamiento persistente, y despliega un sitio web estático en S3 con hosting público.

**Duración**: 50 minutos

### 🗄️ [Laboratorio 2.2 - Base de Datos en Alta Disponibilidad (RDS Multi-AZ)](./lab-2.2-rds/README.md)
Configura una base de datos MySQL en Amazon RDS con replicación Multi-AZ para garantizar disponibilidad continua ante fallos de infraestructura.

**Duración**: 50 minutos

### ⚡ [Laboratorio 2.3 - Elasticidad y Alta Disponibilidad Integrada](./lab-2.3-ha-elb-asg/README.md)
Despliega una arquitectura web completa con balanceador de carga, auto scaling y monitoreo usando CloudFormation para automatizar la infraestructura.

**Duración**: 50 minutos

## Prerequisitos del Día 1

Para completar los laboratorios del Día 2, debes tener activos los siguientes recursos creados en el Día 1:

- **VPC**: Red virtual privada configurada en el Lab 1.1
- **Subredes públicas**: Al menos 2 subredes públicas en diferentes zonas de disponibilidad (Lab 1.1)
- **Subred privada**: Al menos 1 subred privada para la base de datos (Lab 1.1)
- **Internet Gateway**: Configurado y asociado a la VPC (Lab 1.1)
- **Tabla de enrutamiento**: Con ruta hacia el Internet Gateway (Lab 1.1)
- **Instancia EC2**: Instancia en ejecución creada en el Lab 1.2
- **Security Group de EC2**: Configurado para permitir SSH y HTTP (Lab 1.2)
- **Par de claves**: Para acceso SSH a las instancias (Lab 1.2)

⚠️ **Importante**: Si eliminaste alguno de estos recursos al finalizar el Día 1, deberás recrearlos antes de comenzar los laboratorios del Día 2.

## Recursos Compartidos del Instructor

El instructor ha provisionado los siguientes recursos compartidos que utilizarás durante los laboratorios:

- **Subred privada de respaldo**: Para configuración Multi-AZ de RDS (Lab 2.2)
- **Archivos del sitio web estático**: Ubicados en [`./lab-2.1-storage/sitio-web-s3/`](./lab-2.1-storage/sitio-web-s3/) para S3 (Lab 2.1)

⚠️ **Recurso compartido - NO modificar**: Estos recursos son utilizados por todos los participantes. No los modifiques ni elimines.

## Solución de Problemas

Si encuentras dificultades durante los laboratorios, consulta la [Guía de Solución de Problemas](./TROUBLESHOOTING.md) que contiene soluciones a errores comunes organizados por laboratorio.

**Errores que requieren asistencia del instructor:**
- Errores de permisos IAM
- Errores de límites de cuota de AWS
- Problemas con recursos compartidos

## Limpieza de Recursos (Opcional)

Al finalizar el Día 2, puedes opcionalmente eliminar los recursos creados para evitar costos. Consulta la [Guía de Limpieza de Recursos](./limpieza/README.md) para instrucciones detalladas.

**Nota**: La limpieza es completamente opcional. Si deseas mantener los recursos para práctica adicional, puedes hacerlo.

---

**¡Estás listo para comenzar!** Dirígete al [Lab 2.1: - Almacenamiento de Bloques y Hosting de Objetos](./lab-2.1-storage/README.md) para iniciar el día 2.
