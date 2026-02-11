# 📚 Día 1 - Fundamentos de AWS

Bienvenido al primer día del Workshop AWS. En este día aprenderás los fundamentos de la infraestructura de AWS, incluyendo redes virtuales, cómputo en la nube y almacenamiento.

## Agenda

| Hora | Actividad | Duración |
|------|-----------|----------|
| 10:00 - 10:15 | Introducción | 15 min |
| 10:15 - 11:05 | Lab 1.1: VPC y Subredes | 50 min |
| 11:05 - 11:55 | Lab 1.2: Despliegue de EC2 | 50 min |
| 11:55 - 12:00 | Break | 5 min |
| 12:00 - 12:50 | Lab 1.3: Almacenamiento EBS y S3 | 50 min |
| 12:50 - 13:00 | Revisión y preguntas | 10 min |

**Duración total:** 3 horas (180 minutos)

## Conceptos Clave

### VPC (Virtual Private Cloud)

Una VPC es una red virtual aislada en la nube de AWS donde puedes lanzar recursos de AWS en un entorno de red que tú defines. Piensa en una VPC como tu propio centro de datos virtual en la nube, donde tienes control completo sobre:

- **Rango de direcciones IP:** Defines el espacio de direcciones usando notación CIDR (ej: 10.0.0.0/16)
- **Subredes:** Divides tu VPC en segmentos más pequeños para organizar recursos
- **Tablas de enrutamiento:** Controlas cómo fluye el tráfico entre subredes y hacia internet
- **Puertas de enlace:** Conectas tu VPC a internet o a otras redes

**Componentes principales:**
- **Subredes públicas:** Tienen acceso directo a internet a través de un Internet Gateway
- **Subredes privadas:** No tienen acceso directo a internet, usadas para recursos internos
- **Internet Gateway (IGW):** Permite comunicación entre recursos en tu VPC e internet
- **Tablas de enrutamiento:** Determinan hacia dónde se dirige el tráfico de red

### EC2 (Elastic Compute Cloud)

EC2 proporciona capacidad de cómputo escalable en la nube. Es como tener servidores virtuales que puedes crear, configurar y eliminar según tus necesidades.

**Conceptos fundamentales:**
- **Instancia:** Un servidor virtual en la nube
- **AMI (Amazon Machine Image):** Plantilla que contiene el sistema operativo y configuración inicial
- **Tipo de instancia:** Define la capacidad de CPU, memoria, almacenamiento y red (ej: t3.micro)
- **Security Groups:** Firewall virtual que controla el tráfico de entrada y salida
- **Par de claves:** Credenciales para acceso seguro SSH a tus instancias
- **User Data:** Scripts que se ejecutan automáticamente al iniciar la instancia

**Casos de uso comunes:**
- Servidores web y de aplicaciones
- Procesamiento de datos
- Entornos de desarrollo y pruebas

### EBS (Elastic Block Store)

EBS proporciona volúmenes de almacenamiento de bloques persistentes para usar con instancias EC2. Es como agregar discos duros adicionales a tus servidores virtuales.

**Características principales:**
- **Persistencia:** Los datos permanecen incluso si detienes o terminas la instancia
- **Tipos de volúmenes:** gp3 (propósito general), io2 (alto rendimiento), st1 (throughput optimizado)
- **Snapshots:** Copias de respaldo de tus volúmenes
- **Adjuntar/Desasociar:** Puedes mover volúmenes entre instancias en la misma zona de disponibilidad

**Diferencia con almacenamiento de instancia:**
- EBS: Persistente, puede desasociarse y reasociarse
- Almacenamiento de instancia: Temporal, se pierde al detener la instancia

### S3 (Simple Storage Service)

S3 es un servicio de almacenamiento de objetos que ofrece escalabilidad, disponibilidad de datos, seguridad y rendimiento. Es ideal para almacenar y recuperar cualquier cantidad de datos desde cualquier lugar.

**Conceptos fundamentales:**
- **Bucket:** Contenedor para almacenar objetos (archivos)
- **Objeto:** Archivo individual y sus metadatos
- **Clave:** Nombre único del objeto dentro del bucket
- **Regiones:** Los buckets se crean en una región específica de AWS

**Casos de uso comunes:**
- Hosting de sitios web estáticos
- Backup y recuperación de datos
- Almacenamiento de archivos multimedia
- Data lakes para análisis

**Características de hosting estático:**
- Puedes alojar sitios web HTML, CSS y JavaScript
- No soporta lenguajes del lado del servidor (PHP, Python, etc.)
- Altamente escalable y de bajo costo

## Laboratorios

| Lab | Título | Descripción | Duración |
|-----|--------|-------------|----------|
| 1.1 | [VPC y Subredes](./lab-1.1-vpc/README.md) | Configuración de red virtual, subredes y enrutamiento | 50 min |
| 1.2 | [Despliegue de EC2](./lab-1.2-ec2/README.md) | Lanzamiento de instancia EC2 con servidor web Apache | 50 min |
| 1.3 | [Almacenamiento EBS y S3](./lab-1.3-storage/README.md) | Volúmenes de bloques y hosting de sitio web estático | 50 min |

## Prerrequisitos

**📖 Consulta los [Prerrequisitos Generales del Workshop](../README.md#prerrequisitos) para información sobre acceso técnico, conocimientos recomendados, acceso a AWS y verificación de región.**

Además de los prerrequisitos generales, para el Día 1 necesitas:

### Número de Participante Asignado

El instructor te asignará un número único (X) que usarás para calcular tus rangos CIDR. Anota este número:

**Mi número de participante: _____**

Este número es fundamental para evitar conflictos de direcciones IP con otros participantes.

## Convenciones de Nomenclatura

Para trabajar en un entorno compartido donde múltiples participantes crean recursos simultáneamente, es fundamental seguir convenciones de nomenclatura estrictas.

### Formato de Nombres

Todos los recursos que crees deben seguir este formato:

```
{tipo-recurso}-{descripción}-{tu-nombre}
```

**Reglas importantes:**
- Usa solo letras minúsculas
- Usa guiones (-) para separar palabras
- Reemplaza `{tu-nombre}` con tu nombre o identificador único
- Sé consistente con tu identificador en todos los recursos

### Ejemplos de Nomenclatura

| Tipo de Recurso | Ejemplo de Nombre |
|-----------------|-------------------|
| Subred pública | `subnet-publica-luis` |
| Subred privada | `subnet-privada-luis` |
| Tabla de ruteo | `rtb-publica-maria` |
| Security Group | `Web-Server-SG-carlos` |
| Instancia EC2 | `ec2-webserver-ana` |
| Par de claves | `keypair-pedro` |
| Volumen EBS | `ebs-data-sofia` |
| Bucket S3 | `s3-sitio-jorge-2024` |

### Identificación Visual

Cuando navegues por la Consola de AWS, podrás identificar fácilmente tus recursos buscando tu nombre en la columna "Nombre" o "Name". Esto te ayudará a:

- Distinguir tus recursos de los de otros participantes
- Evitar modificar o eliminar recursos de otros
- Facilitar el troubleshooting con el instructor

**ADVERTENCIA:** Nunca modifiques o elimines recursos que no tengan tu identificador. Esto podría afectar el trabajo de otros participantes.

## Etiquetado Obligatorio

Además de la nomenclatura, todos los recursos deben incluir etiquetas (tags) estandarizadas. Las etiquetas son pares clave-valor que ayudan a organizar, rastrear y gestionar recursos.

### Tags Requeridos

Cada recurso que crees debe tener estas dos etiquetas:

| Clave | Valor | Descripción |
|-------|-------|-------------|
| `Owner` | Tu nombre completo | Identifica quién es el propietario del recurso |
| `Project` | `Workshop-BCRP` | Identifica que el recurso pertenece a este workshop |

### Ejemplo de Etiquetado

```
Owner: Luis García
Project: Workshop-BCRP
```

### Cómo Agregar Tags

En la mayoría de los servicios de AWS, encontrarás la sección de etiquetas durante el proceso de creación del recurso:

1. Busca la sección llamada "Etiquetas" o "Tags"
2. Haz clic en "Agregar nueva etiqueta" o "Add new tag"
3. Ingresa la clave (Key): `Owner`
4. Ingresa el valor (Value): tu nombre completo
5. Haz clic en "Agregar nueva etiqueta" nuevamente
6. Ingresa la clave (Key): `Project`
7. Ingresa el valor (Value): `Workshop-BCRP`

**Nota:** Algunos recursos permiten agregar tags después de la creación, pero es mejor agregarlos durante la creación para mantener la consistencia.

### Beneficios del Etiquetado

- **Organización:** Agrupa recursos relacionados
- **Seguimiento de costos:** Identifica gastos por proyecto o propietario
- **Automatización:** Facilita scripts y políticas basadas en tags
- **Auditoría:** Rastrea quién creó qué recursos

## Asignación de Bloques CIDR

Para evitar conflictos de direcciones IP en el entorno compartido, cada participante usará rangos CIDR únicos calculados a partir de su número asignado.

### Fórmula de Cálculo

El instructor te asignará un número único **X**. Usa este número para calcular tus CIDRs:

- **Subred pública:** `10.0.{X*2}.0/24`
- **Subred privada:** `10.0.{X*2+1}.0/24`

### Ejemplos de Asignación

| Participante | Número (X) | Subred Pública | Subred Privada |
|--------------|------------|----------------|----------------|
| Luis | 1 | 10.0.2.0/24 | 10.0.3.0/24 |
| María | 2 | 10.0.4.0/24 | 10.0.5.0/24 |
| Carlos | 3 | 10.0.6.0/24 | 10.0.7.0/24 |
| Ana | 4 | 10.0.8.0/24 | 10.0.9.0/24 |
| Pedro | 5 | 10.0.10.0/24 | 10.0.11.0/24 |

### Cálculo de tus CIDRs

**Mi número asignado (X):** _____

**Mis CIDRs:**
- **Subred pública:** 10.0._____.0/24 (X*2)
- **Subred privada:** 10.0._____.0/24 (X*2+1)

### Contexto de la VPC

Todos los participantes trabajarán dentro de la misma VPC creada por el instructor:

- **VPC CIDR:** `10.0.0.0/16`
- **Capacidad:** 65,536 direcciones IP
- **Subredes disponibles:** 256 subredes /24 (de 10.0.0.0/24 a 10.0.255.0/24)

Cada subred /24 proporciona 256 direcciones IP, de las cuales AWS reserva 5 para uso interno, dejando 251 direcciones utilizables.

### Verificación de CIDR

Antes de crear tus subredes, verifica que:

1. Has calculado correctamente tus CIDRs usando tu número asignado
2. Tus CIDRs están dentro del rango de la VPC (10.0.0.0/16)
3. No hay conflicto con otros participantes (cada número X es único)

**IMPORTANTE:** Si recibes un error "CIDR overlaps" al crear una subred, verifica que estás usando el número correcto asignado por el instructor.

## Recursos Compartidos del Instructor

El instructor creará algunos recursos compartidos que todos los participantes usarán. **NO intentes recrear estos recursos.**

### Recursos Compartidos

| Recurso | Nombre | Descripción |
|---------|--------|-------------|
| VPC | `Lab-VPC` | Red virtual con CIDR 10.0.0.0/16 |
| Internet Gateway | `Lab-IGW` | Puerta de enlace para acceso a internet |

### Reglas Importantes

1. **NO modifiques** recursos que no tengan tu identificador personal
2. **NO elimines** recursos compartidos del instructor
3. **NO recrees** la VPC o el Internet Gateway
4. Si encuentras un error de permisos, **notifica al instructor inmediatamente**
5. Si encuentras un error de límite de cuota, **notifica al instructor inmediatamente**

## Navegación y Soporte

### Uso de la Barra de Búsqueda Global

La Consola de AWS tiene una barra de búsqueda global en la parte superior que te permite encontrar servicios rápidamente:

1. Haz clic en la barra de búsqueda (o presiona `/`)
2. Escribe el nombre del servicio (ej: "VPC", "EC2", "S3")
3. Selecciona el servicio de los resultados
4. Serás redirigido al panel del servicio

**Tip:** Esto es más rápido que navegar por los menús de servicios.

### Estructura de los Laboratorios

Cada laboratorio incluye:

- **Verificación de región:** Primer paso obligatorio
- **Instrucciones paso a paso:** Numeradas y detalladas
- **Ubicaciones exactas de UI:** Dónde hacer clic en la consola
- **Estimaciones de tiempo:** Para cada sección
- **Checkpoints de verificación:** Para confirmar que vas por buen camino
- **Troubleshooting:** Referencia al documento centralizado de solución de problemas
- **Ciclo de vida de recursos:** Qué mantener y qué eliminar

**📖 Para soluciones a errores comunes de todos los laboratorios, consulta la [Guía de Troubleshooting del Día 1](./TROUBLESHOOTING.md).**

### Obtener Ayuda

Si encuentras problemas durante los laboratorios:

1. Consulta la **[Guía de Troubleshooting del Día 1](./TROUBLESHOOTING.md)** para soluciones a errores comunes
2. Verifica que estás en la región correcta
3. Verifica que usaste la nomenclatura correcta
4. Verifica que agregaste los tags obligatorios
5. Si el problema persiste, levanta la mano y notifica al instructor

## Limpieza Opcional

Al final del día, puedes consultar la [Guía de Limpieza Opcional](./limpieza/README.md) para aprender cómo eliminar recursos que no necesitarás para el Día 2.

**Nota:** La limpieza es completamente opcional. La mayoría de los recursos creados hoy se usarán en días posteriores del workshop.

---

**¡Estás listo para comenzar!** Dirígete al [Lab 1.1: VPC y Subredes](./lab-1.1-vpc/README.md) para iniciar tu primer laboratorio.
