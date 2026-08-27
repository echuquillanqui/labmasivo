# Instalación del módulo de resultados de laboratorio

El módulo necesita dos paquetes PHP que Composer debe instalar en cada equipo o servidor:

```bash
composer require phpoffice/phpspreadsheet:^2.1 barryvdh/laravel-dompdf:^2.2 --with-all-dependencies
php artisan optimize:clear
php artisan migrate
```

En Laragon, abra **Terminal** desde Laragon y confirme primero que está situado en la carpeta correcta:

```bat
cd C:\laragon\www\laboratorio-masivo
composer require phpoffice/phpspreadsheet:^2.1 barryvdh/laravel-dompdf:^2.2 --with-all-dependencies
php artisan optimize:clear
php artisan migrate
```

Para comprobar la instalación:

```bash
composer show phpoffice/phpspreadsheet
composer show barryvdh/laravel-dompdf
php artisan route:list --path=resultados-laboratorio
```

## Error `Class "PhpOffice\PhpSpreadsheet\IOFactory" not found`

Ese mensaje significa que el código del módulo está presente, pero Composer todavía no ha descargado `phpoffice/phpspreadsheet` en la carpeta `vendor`. No se soluciona copiando una clase manualmente: ejecute el comando `composer require` anterior desde la raíz del proyecto.

Si el paquete figura en `composer.json` pero sigue sin existir en `vendor`, ejecute:

```bash
composer update phpoffice/phpspreadsheet barryvdh/laravel-dompdf --with-all-dependencies
composer dump-autoload
php artisan optimize:clear
```

Después vuelva a abrir la pantalla de importación. No es necesario modificar ni volver a subir el archivo Excel.
