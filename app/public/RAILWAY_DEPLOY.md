# Instrucciones para desplegar en Railway

## Pasos para corregir el despliegue:

### 1. Hacer push de los cambios a GitHub

```bash
git add .
git commit -m "Fix: Excluir .env del Docker, usar solo variables de Railway"
git push origin main
```

### 2. En el panel de Railway:

1. Ve a tu proyecto **gymfitness-headless-worldpress-astro**
2. Selecciona el servicio WordPress
3. Ve a la pestaña **Deploy**
4. Haz clic en **Redeploy** para forzar una nueva compilación

   O espera a que GitHub Actions detecte el push y construya automáticamente

### 3. Verificar variables en Railway

Ve a **Variables** y asegúrate que estén configuradas:

```
WORDPRESS_DB_NAME=railway
WORDPRESS_DB_USER=root
WORDPRESS_DB_PASSWORD=QDlJkqHSHsYMGXswLDbYKfcnqLWVPCgI
WORDPRESS_DB_HOST=trolley.proxy.rlwy.net:47703
```

### 4. Esperar a que se despliegue

El nuevo despliegue debería tomar 2-5 minutos. Verifica el estado en **Deployments**.

## Flujo ahora:

- **Local**: Lee de `.env` (nunca commiteado)
- **Railway**: Lee de variables de entorno configuradas en el panel
- **Docker**: Solo contiene `wp-config.php` y `wp-content/`
