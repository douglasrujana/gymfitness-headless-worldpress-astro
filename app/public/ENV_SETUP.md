# Configuración de Variables de Entorno

## Desarrollo Local

1. Copia el archivo `.env.example` a `.env`:

```bash
cp .env.example .env
```

2. Edita `.env` con tus credenciales locales:

```ini
DB_NAME=wordpress_local
DB_USER=root
DB_PASSWORD=tu_contraseña
DB_HOST=localhost
```

3. El archivo `.env` **nunca** será commiteado a git (está en `.gitignore`)

## Producción en Railway

Railway lee automáticamente las variables de entorno que configures:

1. En el panel de Railway, ve a **Variables**
2. Agrega estas variables:

```ini
WORDPRESS_DB_NAME=railway
WORDPRESS_DB_USER=root
WORDPRESS_DB_PASSWORD=tu_contraseña_railway
WORDPRESS_DB_HOST=trolley.proxy.rlwy.net:47703
```

3. WordPress las leerá automáticamente al iniciar

## Prioridad de Variables

El `wp-config.php` busca en este orden:

1. **Archivo `.env`** (local, nunca commiteado)
2. **Variables de entorno** (Railway)
3. **Valores por defecto** (como fallback)

```php
define('DB_NAME', $env_vars['DB_NAME'] ?? getenv('WORDPRESS_DB_NAME') ?? 'wordpress');
```

## Seguridad

✅ Sin credenciales hardcodeadas en el repositorio
✅ Funciona en local sin cambios
✅ Funciona en Railway sin cambios
✅ Fácil de mantener
