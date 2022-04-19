# Importador de productos

## Contenido:

### 
Este proyecto contiene lo básico para importar productos de tres fuentes de archivos diferentes diferentes: json, xlsx, y xml. 


# **CONFIGURACIÓN DE ENTORNO LOCAL**

Los pasos a seguir para la configuración del entorno de trabajo local son los siguientes:
- Clonar el repositorio de git:
```sh
git clone https://github.com/joanbatiste/bigbuy-products.git
```
- Levanta los servicios incluidos y necesarios para la ejecución:
```sh
docker-compose -f docker-compose-dev.yml up -d --build
```
- Entrar en el contenedor del proyecto e instalar las dependencias del mismo.
```sh
docker exec -it -u www-data bigbuy_back bash
``` 
```sh
composer install
```
- En el navegador, abrir localhost:12254 para acceder al servicio de bbdd.

- Dirígete a "Cuentas de usuarios / Agregar cuenta de usuario". Coloca el nombre de usuario y bbdd que quieras:
- Genera una contraseña pulsando el botón "Generar" (evita que contenga símbolos extraños)
- Marca el check de "Crear base de datos con el mismo nombre y otorgar todos los privilegios".
- No hay que hacer nada más. Al final de la pantalla pulsa "Continuar".
- Abrir el archivo .env y substituir en siguiente línea. También deberás cambiar el usuario de bd y la contraseña por la que acabas de generar en phpMyAdmin:
```sh
DATABASE_URL="mysql://user:password@mysql:3306/bbdd-name?serverVersion=5.7&charset=UTF8MB4"
```
- Desde la consola del contenedor del proyecto ejecutar las migraciones para generar las tablas:
```sh
php bin/console d:m:migrate
```

 
 Si todo ha ido bien, debes de tener el proyecto funcionando en tu localhost. No olvides que para comprobar las funcionalidades que requieran envío de emails tienes que tener levantado tu contenedor de mailhog local.

