#!/usr/bin/env python3
"""
Script para generar logo.png y favicon.ico para el Workshop AWS BCRP
Requiere: pip install Pillow
"""

try:
    from PIL import Image, ImageDraw, ImageFont
    import os
except ImportError:
    print("Error: Este script requiere la librería Pillow")
    print("Instale con: pip install Pillow")
    exit(1)

def create_logo():
    """Crea logo.png de 200x50 píxeles"""
    # Crear imagen con fondo transparente
    img = Image.new('RGBA', (200, 50), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)
    
    # Colores AWS
    aws_blue = (35, 47, 62)
    aws_orange = (255, 153, 0)
    
    # Dibujar rectángulo de fondo
    draw.rectangle([5, 5, 45, 45], fill=aws_orange, outline=aws_blue, width=2)
    
    # Dibujar puntos (simulando nube)
    points = [(15, 15), (25, 15), (35, 15), (20, 25), (30, 25), (15, 35), (25, 35), (35, 35)]
    for point in points:
        draw.ellipse([point[0]-2, point[1]-2, point[0]+2, point[1]+2], fill='white')
    
    # Agregar texto
    try:
        # Intentar usar una fuente del sistema
        font_large = ImageFont.truetype("arial.ttf", 16)
        font_small = ImageFont.truetype("arial.ttf", 12)
    except:
        # Si no está disponible, usar fuente por defecto
        font_large = ImageFont.load_default()
        font_small = ImageFont.load_default()
    
    draw.text((55, 10), "Workshop AWS", fill=aws_blue, font=font_large)
    draw.text((55, 30), "BCRP", fill=aws_orange, font=font_small)
    
    # Guardar
    img.save('logo.png', 'PNG')
    print("Logo.png creado exitosamente")

def create_favicon():
    """Crea favicon.ico de 32x32 píxeles"""
    # Crear imagen
    img = Image.new('RGBA', (32, 32), (35, 47, 62))
    draw = ImageDraw.Draw(img)
    
    # Color naranja AWS
    aws_orange = (255, 153, 0)
    
    # Dibujar diseño simple (nube estilizada)
    draw.rectangle([6, 10, 26, 22], fill=aws_orange)
    draw.ellipse([4, 8, 14, 18], fill=aws_orange)
    draw.ellipse([18, 8, 28, 18], fill=aws_orange)
    draw.ellipse([10, 14, 22, 26], fill=aws_orange)
    
    # Guardar como ICO
    img.save('favicon.ico', format='ICO', sizes=[(32, 32)])
    print("Favicon.ico creado exitosamente")

def main():
    print("Generando imágenes para Workshop AWS BCRP...")
    print("-" * 50)
    
    # Verificar que estamos en el directorio correcto
    if not os.path.exists('logo.svg'):
        print("Advertencia: No se encontró logo.svg")
        print("Asegúrese de ejecutar este script desde la carpeta assets/")
        print()
    
    try:
        create_logo()
        create_favicon()
        print("-" * 50)
        print("Imagenes generadas exitosamente!")
        print()
        print("Archivos creados:")
        print("  - logo.png (200x50 píxeles)")
        print("  - favicon.ico (32x32 píxeles)")
        print()
        print("Ahora puede cargar estos archivos a su bucket S3")
    except Exception as e:
        print(f"Error al generar imágenes: {e}")
        exit(1)

if __name__ == "__main__":
    main()
