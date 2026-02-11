# Guía de Acceso a Cuenta AWS

## Introducción

En este workshop trabajaremos en una cuenta AWS compartida donde cada participante tendrá su propio usuario IAM con permisos específicos. Esta guía te ayudará a acceder a la cuenta por primera vez y configurar tu contraseña personal.

**Tiempo estimado:** 5 minutos

---

## Paso 1: Acceder al Formulario de Inicio de Sesión

**⏱️ Tiempo estimado: 2 minutos**

El instructor te proporcionará un enlace de acceso directo a la consola de AWS. Este enlace te llevará directamente al formulario de inicio de sesión de IAM.

1. Haz clic en el enlace proporcionado por el instructor
2. Se abrirá una página con el formulario de inicio de sesión de AWS

El formulario contiene tres campos:

### Campo 1: Account ID or alias

- Este campo ya estará **precompletado** con el alias de la cuenta del workshop
- **NO modifiques** este campo
- El instructor precisará el alias que se mostrará en este campo

### Campo 2: IAM user name

- Ingresa tu **correo corporativo completo**
- Ejemplo: `juan.perez@ejemplo.com`
- Asegúrate de escribirlo correctamente, incluyendo el dominio completo

### Campo 3: Password

- Ingresa la **contraseña temporal** proporcionada por el instructor
- Esta contraseña es temporal y deberás cambiarla en el siguiente paso
- La contraseña es sensible a mayúsculas y minúsculas

3. Haz clic en el botón **"Sign in"** o **"Iniciar sesión"**

✅ **Checkpoint:** Si las credenciales son correctas, serás redirigido automáticamente a la página de cambio de contraseña.

---

## Paso 2: Cambio de Contraseña Obligatorio

**⏱️ Tiempo estimado: 2 minutos**

Al iniciar sesión por primera vez, AWS te solicitará cambiar tu contraseña temporal por una contraseña personal y segura.

Verás un formulario con tres campos:

### Campo 1: Old password (Contraseña antigua)

- Ingresa la **contraseña temporal** que te proporcionó el instructor
- Es la misma contraseña que usaste en el paso anterior

### Campo 2: New password (Nueva contraseña)

- Crea una **contraseña personal y segura**
- La contraseña debe cumplir con los siguientes requisitos:
  - Mínimo 14 caracteres
  - Al menos una letra mayúscula (A-Z)
  - Al menos una letra minúscula (a-z)
  - Al menos un número
  - Al menos un carácter no alfanumérico

### Campo 3: Confirm new password (Confirmar nueva contraseña)

- Vuelve a escribir tu nueva contraseña exactamente igual
- Asegúrate de que coincida con la contraseña del campo anterior

**Recomendaciones para tu contraseña:**
- Usa una combinación de letras, números y símbolos
- No uses información personal fácil de adivinar
- Anota tu contraseña en un lugar seguro (no la compartas con otros participantes)
- Recuerda que la contraseña es sensible a mayúsculas y minúsculas

1. Completa los tres campos
2. Haz clic en el botón **"Confirm password change"** o **"Confirmar cambio de contraseña"**

✅ **Checkpoint:** Si el cambio es exitoso, serás redirigido a la consola principal de AWS.

---

## Paso 3: Verificación de Idioma de la Consola

**⏱️ Tiempo estimado: 1 minuto**

Es importante que la consola de AWS esté configurada en español para seguir las instrucciones del workshop correctamente.

### Verificar el idioma actual

1. Observa la interfaz de la consola de AWS
2. Los menús, botones y textos deben estar en español
3. Por ejemplo, deberías ver "Servicios", "Recursos", "Consola de administración"

### Si la consola NO está en español

Si ves la interfaz en inglés (por ejemplo: "Services", "Resources", "Management Console"), sigue estos pasos:

1. En la esquina inferior izquierda de la consola, busca el selector de idioma
2. Haz clic en el idioma actual (probablemente muestre "English")
3. Se abrirá un menú desplegable con opciones de idioma
4. Selecciona **"Español"** de la lista
5. La página se recargará automáticamente en español

**Ubicación alternativa del selector de idioma:**
- Si no lo encuentras en la esquina inferior izquierda, busca en el menú de configuración (ícono de engranaje) en la barra superior
- Selecciona "Settings" o "Configuración"
- Busca la opción "Language" o "Idioma"
- Cambia a "Español"

✅ **Checkpoint:** La consola debe mostrar todos los textos en español. Verifica que puedes leer "Servicios" en la barra superior.

---

## Troubleshooting - Solución de Problemas

### Problema 1: "Credenciales incorrectas" o "Incorrect username or password"

**Síntomas:**
- Aparece un mensaje de error al intentar iniciar sesión
- El mensaje indica que el usuario o contraseña son incorrectos

**Soluciones:**

1. **Verifica tu correo corporativo:**
   - Asegúrate de escribir tu correo completo, incluyendo `@ejemplo.com`
   - Verifica que no haya espacios al inicio o al final
   - Confirma que usaste minúsculas (los correos son sensibles a mayúsculas/minúsculas)

2. **Verifica la contraseña temporal:**
   - Confirma con el instructor que estás usando la contraseña correcta
   - Verifica que no haya espacios al copiar y pegar
   - Asegúrate de respetar mayúsculas y minúsculas

3. **Verifica el Account ID/alias:**
   - NO modifiques el campo de Account ID que viene precompletado
   - Si lo modificaste accidentalmente, recarga la página usando el enlace del instructor

4. **Si el problema persiste:**
   - Notifica al instructor inmediatamente
   - Proporciona tu correo corporativo para que verifique tu usuario

---

### Problema 2: "Contraseña expirada" o "Password expired"

**Síntomas:**
- Mensaje indicando que la contraseña ha expirado
- No puedes acceder a la consola

**Soluciones:**

1. Notifica al instructor inmediatamente
2. El instructor deberá restablecer tu contraseña temporal
3. Una vez restablecida, intenta iniciar sesión nuevamente

---

### Problema 3: "Usuario no encontrado" o "User not found"

**Síntomas:**
- Mensaje indicando que el usuario no existe
- Error al intentar iniciar sesión

**Soluciones:**

1. **Verifica que estás usando el enlace correcto:**
   - Usa únicamente el enlace proporcionado por el instructor
   - No intentes acceder desde la página principal de AWS

2. **Verifica tu correo corporativo:**
   - Confirma con el instructor que tu usuario fue creado
   - Verifica que estás escribiendo exactamente el correo que el instructor registró

3. **Si el problema persiste:**
   - Notifica al instructor
   - Es posible que tu usuario aún no haya sido creado en el sistema

---

### Problema 4: Error al cambiar contraseña

**Síntomas:**
- Mensaje de error al intentar cambiar la contraseña
- "Password does not meet requirements" o "La contraseña no cumple los requisitos"

**Soluciones:**

1. **Verifica los requisitos de contraseña:**
   - Mínimo 14 caracteres
   - Al menos una letra mayúscula (A-Z)
   - Al menos una letra minúscula (a-z)
   - Al menos un número
   - Al menos un carácter no alfanumérico

2. **Verifica que las contraseñas coincidan:**
   - La "Nueva contraseña" y "Confirmar nueva contraseña" deben ser idénticas
   - Cuidado con espacios adicionales al copiar y pegar

3. **Intenta con una contraseña diferente:**
   - Asegúrate de cumplir todos los requisitos

---

### Problema 5: Consola en idioma incorrecto

**Síntomas:**
- La consola aparece en inglés u otro idioma
- No encuentras el selector de idioma

**Soluciones:**

1. **Busca el selector de idioma en la esquina inferior izquierda:**
   - Haz clic en el idioma actual
   - Selecciona "Español" del menú

2. **Si no encuentras el selector en la esquina inferior:**
   - Busca el ícono de engranaje (⚙️) en la barra superior derecha
   - Haz clic en "Settings" o "Configuración"
   - Busca "Language" o "Idioma"
   - Selecciona "Español"
   - Guarda los cambios

3. **Recarga la página:**
   - Si el cambio no se aplica inmediatamente, presiona F5 o recarga la página
   - El idioma debería cambiar a español

---

### Problema 6: No puedo acceder después de cambiar la contraseña

**Síntomas:**
- Cambiaste la contraseña exitosamente pero no puedes acceder
- Aparece error de credenciales incorrectas

**Soluciones:**

1. **Espera unos segundos:**
   - A veces el cambio de contraseña tarda unos segundos en propagarse
   - Espera 30 segundos e intenta nuevamente

2. **Verifica que estás usando la nueva contraseña:**
   - Asegúrate de usar la contraseña que acabas de crear, no la temporal

3. **Si olvidaste tu nueva contraseña:**
   - Notifica al instructor inmediatamente
   - El instructor deberá restablecer tu contraseña

---

## ¿Necesitas Ayuda Adicional?

Si después de seguir esta guía y revisar el troubleshooting aún tienes problemas para acceder:

1. **Levanta la mano** y notifica al instructor
2. **NO intentes** crear una nueva cuenta o modificar configuraciones avanzadas
3. **Proporciona al instructor:**
   - Tu correo corporativo
   - El mensaje de error exacto que estás viendo
   - Los pasos que ya intentaste

El instructor te ayudará a resolver el problema para que puedas continuar con el workshop.

---

**Una vez que hayas accedido exitosamente a la consola de AWS en español, estás listo para continuar con el workshop.** Regresa al [README principal del Día 1](../README.md) para comenzar con los laboratorios.
