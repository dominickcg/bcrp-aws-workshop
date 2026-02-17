# Assets - Archivos de Imagen

Esta carpeta contiene los recursos gráficos para el sitio web estático del Workshop AWS BCRP.

## Archivos Requeridos

### logo.png
- **Descripción**: Logo del Workshop AWS BCRP
- **Dimensiones recomendadas**: 200x50 píxeles
- **Formato**: PNG con transparencia
- **Uso**: Se muestra en el header de todas las páginas

### favicon.ico
- **Descripción**: Icono del sitio web que aparece en la pestaña del navegador
- **Dimensiones**: 16x16 o 32x32 píxeles
- **Formato**: ICO
- **Uso**: Se referencia en el `<head>` de cada página HTML

## Generación de Archivos

### Opción 1: Usar el archivo SVG incluido

El archivo `logo.svg` puede convertirse a PNG usando herramientas en línea o software de diseño:

1. **Herramientas en línea**:
   - https://cloudconvert.com/svg-to-png
   - https://convertio.co/es/svg-png/

2. **Software de diseño**:
   - Adobe Illustrator
   - Inkscape (gratuito)
   - GIMP (gratuito)

### Opción 2: Crear imágenes personalizadas

Si desea crear sus propias imágenes:

1. **Para logo.png**:
   - Cree una imagen de 200x50 píxeles
   - Use los colores del tema: #232F3E (azul oscuro), #FF9900 (naranja AWS)
   - Incluya el texto "Workshop AWS BCRP"
   - Exporte como PNG con fondo transparente

2. **Para favicon.ico**:
   - Cree una imagen simple de 32x32 píxeles
   - Use un diseño reconocible (ej: iniciales "AWS" o icono de nube)
   - Convierta a formato ICO usando: https://www.favicon-generator.org/

## Archivos Temporales

Para propósitos de demostración, puede usar el archivo SVG directamente modificando las referencias en los archivos HTML:

Cambiar:
```html
<img src="assets/logo.png" alt="Logo Workshop AWS BCRP">
```

Por:
```html
<img src="assets/logo.svg" alt="Logo Workshop AWS BCRP">
```

Y para el favicon, puede omitir temporalmente la línea:
```html
<link rel="icon" type="image/x-icon" href="assets/favicon.ico">
```

## Nota para el Laboratorio

Durante el Laboratorio 2.1, los participantes pueden:
1. Usar el archivo SVG directamente (modificando las referencias HTML)
2. Descargar imágenes PNG/ICO pre-generadas si el instructor las proporciona
3. Crear sus propias imágenes siguiendo las especificaciones anteriores

El objetivo principal del laboratorio es verificar que S3 puede servir archivos estáticos correctamente, no la creación de imágenes.
