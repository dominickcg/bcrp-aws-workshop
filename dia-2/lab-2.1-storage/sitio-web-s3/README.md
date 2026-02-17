# Sitio Web Estático para S3

Este directorio contiene todos los archivos necesarios para el hosting estático en Amazon S3 del Laboratorio 2.1 del Día 2.

## Estructura de Archivos

```
sitio-web-s3/
├── index.html          # Página principal del workshop
├── nosotros.html       # Página "Acerca de" con información del workshop
├── contacto.html       # Página de contacto con formulario
├── error.html          # Página de error 404 personalizada
├── css/
│   └── styles.css      # Estilos CSS responsive
├── js/
│   └── main.js         # JavaScript para funcionalidad del sitio
└── assets/
    ├── logo.svg        # Logo del workshop (formato SVG)
    ├── favicon.svg     # Favicon del sitio (formato ICO)
    ├── generate-images.py  # Script opcional para generar PNG/ICO
    └── README.md       # Instrucciones para generar imágenes
```

## Características del Sitio

### Páginas HTML

1. **index.html**: Página principal con información general del workshop y los tres laboratorios del Día 2
2. **nosotros.html**: Información detallada sobre el workshop, metodología y estructura del Día 2
3. **contacto.html**: Página de contacto con formulario funcional (demostración)
4. **error.html**: Página de error 404 personalizada que se muestra cuando se accede a rutas inexistentes

### Estilos CSS

- Diseño responsive que se adapta a diferentes tamaños de pantalla
- Colores del tema AWS: azul oscuro (#232F3E) y naranja (#FF9900)
- Grid layout para tarjetas de laboratorios
- Estilos para formularios y mensajes de error/éxito
- Media queries para dispositivos móviles y tablets

### JavaScript

- Validación de formulario de contacto
- Actualización dinámica del año en el footer
- Smooth scrolling para enlaces internos
- Logging de tiempo de carga de página
- Mensajes de consola para verificación del Día 2

### Assets

- **logo.svg**: Logo vectorial del Workshop AWS BCRP
- **favicon.svg**: Icono del sitio en formato SVG
- **generate-images.py**: Script Python opcional para generar versiones PNG/ICO

## Uso en el Laboratorio 2.1

### Paso 1: Preparación Local

Los participantes deben descargar o clonar estos archivos en su computadora local.

### Paso 2: Carga a S3

**IMPORTANTE**: Todos los archivos deben cargarse en una sola operación para mantener la estructura de carpetas.

1. Seleccionar todos los archivos y carpetas (index.html, nosotros.html, contacto.html, error.html, css/, js/, assets/)
2. Arrastrar y soltar en la consola de S3, o usar el botón "Cargar"
3. Verificar que la estructura de carpetas se mantiene correctamente

### Paso 3: Configuración de S3

1. Habilitar "Static website hosting" en el bucket
2. Configurar `index.html` como documento de índice
3. Configurar `error.html` como documento de error
4. Aplicar la política de bucket para acceso público
5. Desactivar "Block all public access"

### Paso 4: Verificación

Acceder al endpoint de S3 y verificar:
- La página principal se carga correctamente
- Los estilos CSS se aplican (colores, diseño)
- El JavaScript funciona (abrir consola del navegador)
- La navegación entre páginas funciona
- Las rutas inexistentes muestran error.html
- El logo y favicon se cargan correctamente

## Verificación de Funcionalidad

### CSS Funcionando Correctamente

Si el CSS se carga correctamente, verá:
- Header con fondo azul oscuro (#232F3E)
- Botones y enlaces en naranja (#FF9900)
- Diseño en grid para las tarjetas de laboratorios
- Texto bien formateado y espaciado

### JavaScript Funcionando Correctamente

Abra la consola del navegador (F12) y verifique:
```
Workshop AWS BCRP - JavaScript loaded successfully
Site hosted on Amazon S3
==================================================
Workshop AWS BCRP - Día 2
Laboratorio 2.1: Almacenamiento EBS y S3
JavaScript ejecutándose correctamente
==================================================
```

### Rutas Relativas Funcionando

Todas las rutas son relativas y deben funcionar sin modificación:
- CSS: `css/styles.css`
- JavaScript: `js/main.js`
- Imágenes: `assets/logo.svg`, `assets/favicon.svg`
- Navegación: `index.html`, `nosotros.html`, `contacto.html`

## Solución de Problemas

### El CSS no se aplica

- Verificar que la carpeta `css/` se cargó correctamente en S3
- Verificar que el archivo `styles.css` está dentro de la carpeta `css/`
- Verificar la política del bucket permite acceso público a todos los objetos

### El JavaScript no funciona

- Abrir la consola del navegador para ver errores
- Verificar que la carpeta `js/` se cargó correctamente
- Verificar que `main.js` está dentro de la carpeta `js/`

### Las imágenes no se cargan

- Verificar que la carpeta `assets/` se cargó correctamente
- Verificar que los archivos SVG están en la carpeta `assets/`
- Los navegadores modernos soportan SVG directamente

### La página de error no aparece

- Verificar que `error.html` está en la raíz del bucket
- Verificar la configuración de "Static website hosting"
- Asegurarse de que el documento de error está configurado como `error.html`

## Notas Importantes

1. **Estructura de carpetas**: Es crítico mantener la estructura de carpetas al cargar a S3
2. **Rutas relativas**: Todas las rutas son relativas, no usar rutas absolutas
3. **Formato SVG**: Los archivos de imagen están en formato SVG para simplicidad
4. **Sin emojis**: Los archivos de código no contienen emojis (solo texto y código)
5. **Idioma**: El contenido está en español, el código en inglés (estándar)

## Recursos Adicionales

- [Documentación de S3 Static Website Hosting](https://docs.aws.amazon.com/es_es/AmazonS3/latest/userguide/WebsiteHosting.html)
- [Políticas de Bucket S3](https://docs.aws.amazon.com/es_es/AmazonS3/latest/userguide/bucket-policies.html)
- [Solución de problemas de hosting estático](https://docs.aws.amazon.com/es_es/AmazonS3/latest/userguide/troubleshooting-website-hosting.html)
