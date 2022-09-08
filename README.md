
## API REST  

Para la correcta configuración del proyecto es necesario seguir las siguientes indicaciones.

- Una vez clonado el proyecto es necesario crear el archivo .env posteriormente configuramos una base de prueba (de ser necesario ejecutar también **php artisan key:generate** para crear la key del proyecto) y ejecutar **composer install**.
- Una vez teniendo la configuración inicial lista, ejecutamos las migraciones con el siguiente comando **php artisan migrate** creando así nuestra estructura de base de datos.
- Para la visualización de la tarea programada solicitada en la prueba ejecutamos **php artisan schedule:list** y listamos las tareas programadas que se han configurado en el sistema.
- Para consumir el endpoint **https://reqres.in/api/users** ejecutamos **php artisan command:insertusers** y vemos como se insertan los registros del endpoint en nuestra tabla **users** (*las contraseñas almacenadas por defecto son **password***).
- Ya teniendo los usuarios en la tabla users podemos hacer uso de nuestro sistema de documentación del API, para ello iniciamos nuestro servidor local con **php artisan serve** y ejecutamos **php artisan l5-swagger:generate**.
- En nuestro navegador vamos a la ruta **http://127.0.0.1:8000/api/documentation** y revisamos el funcionamiento del API.
- Para autenticarnos en nuestra API nos dirigimos al endpoint **/api/login** y enviamos como el email y contraseña de uno de los usuarios que se encuentran en la tabla **users** por ejemplo : 
		{
		  "email": "george.bluth@reqres.in",
		  "password": "password"
		}
- Nos dara como respuesta el token que es necesario para consumir los demás servicios del API: 
		{
		  "token": "11|TUrnCEMYANWLbS7WJyV6taCbPDAGNqnXiMhN7FAX",
		  "message": "Success"
		}
- Posteriormente nos autenticamos en el sistema para hacer uso de los otros servicios del API.
- En cada uno de los endpoints se encuentra un Request body de ejemplo, los cuales indican la información que está esperando el servicio.
