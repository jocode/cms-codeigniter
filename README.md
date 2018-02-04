# cms-codeigniter

Este es un Sistema Gestor de Contenido realizado con el Framework codeigniter.

Codeigniter es un framework muy potente, con una extensa documentación y está basado en el patrón Modelo Vista Controlador, es open-source y gratuito.

> Su objetivo es permitir que los desarrolladores puedan realizar proyectos mucho más rápido que creando toda la estructura desde cero, brindando un conjunto de bibliotecas para tareas comunes, así como una interfaz simple y una estructura lógica para acceder esas bibliotecas.

La página oficial es https://codeigniter.com, desde aquí se puede descargar.

Al día de hoy está la **Version 3.1.7**, con esa es la que trabajaremos.

Lo descargamos, y podemos eliminar la carpeta **user_guide**, que es la guía de usuario que la podemos ver sin necesidad de internet.

## Qué es MVC

Modelo-vista-controlador es un **patrón** de **arquitectura de software**, que separa los datos y la lógica de negocio de una aplicación de su representación y el módulo encargado de gestionar los eventos y las comunicaciones. 
<small>Tomado de https://es.wikipedia.org/wiki/Modelo–vista–controlador</small>

![Modelo–vista–controlador](/images/mvc.png)

Básicamente está basado en esas tres capas
- **Modelo** Es quien accede a los datos, es donde se coloca la lógica de negocio.
- **Vista** Es quien muestra los datos al usuario.
- **Controlador** Es el mediador entre la vista y el modelo.

Al descargar codeigniter, tenemos dos carpetas, dejando a un lado **user_guide**
- **application** Se encuentran todos los archivos propios de la aplicación que estamos construyendo.
	- **controllers** Se alamacenan los controladores
	- **models** Se guardan los modelos
	- **views** Se guardan las vistas
- **system** Contiene los archivos del framework, como el core, los archivos database y las librerías que vienen incluidas en codeigniter.


Las librerías de Codeigniter las podemos extenderlas desde nuestra aplicación, agregandolas en nuestra carpeta libs en el directorio application.

Todos los controladores de Codeigniter, deben extender de la clase **CI_Controller**, los controladores sirven como mediador entre la vista y los datos. Es aquí donde delegamos todas las funciones.

La estructura de urls en codeigniter, de acuerdo al patrón de arquitectura MVC, es 

`dominio/controlador/metodo/argumentos`

**CI_Controller** nos proporciona el objeto URI, y tiene un método llamado `segment()`, donde le pasamos el número del segmento de la url que queremos tomar.
```php 
$this->uri->segment(1);
```

## Por qué cuando no se envía un controlador y un método, se llama al controlador Welcome y el método Config?

En el archivo [routes.php](/application/config/routes.php), definimos las rutas del Framework. Aquí se configura el controlador que se mostrará por defecto, y llamará al método index
```php 
$route['default_controller'] = 'welcome';
``` 

> Los nombre de clase de los controladores, deben empezar con la primera letra en mayúscula

Para cargar las vistas desde un controlador, usamos el método `load`, de la clase **Loader**, para eso hemos creado un controlador de ejemplo llamado [Test.php](/application/controllers/Test.php)
```php 
$this->load->view('vista');
```
Al pasar por parámetro la vista, usamos el nombre, por ejemplo **_vista_**, pero si esta vista está incluida  en una **carpeta**, colocamos el nombre de la carpeta y el archivo que debe ser en php, por ejemplo **_carpeta/vista_**

Para pasar los datos a la vista, lo hacemos como segundo parámetro, en un **arreglo** de tipo **clave => valor** o usando el método `compact()` de PHP, y como tercer parámetro, podemos pasar un valor booleano que por defecto es false, que devuelve la vista en una variable.


## Conectarse a una base de datos con Codeigniter
El **active record** es una capa de abstracción que me permite hacer operaciones sobre una base de datos sin la necesidad de acceder a un modelo.

Codeigniter cuenta con la clase **_Query Builder_** que nos permite realizar sentencias SQL con un script mínimo. Para más información ver [Query Builder Class](https://www.codeigniter.com/userguide3/database/query_builder.html)


La configuración de conexión por defecto a una base de datos, la definimos en el archivo [database.php](/application/config/database.php), que está dentro de la carpeta **config**.

También podemos cargar un grupo de configuraciones para las base de datos y cargarlas en el controlador con el método database del objeto `load`. 
```php 
$this->load->database();
```

Para obtener los datos de una tabla podemos usar la siguiente sentencia
```php 
$this->db->get('test')->result();
```
Con esto traemos todos los datos de la tabla `test`.

- **result()** Devuelve los datos en forma de objeto 
- **result_array()** Devuelve los datos en forma de arreglo

También podemos seleccionar un solo campo 
```php 
$this->db->select('nombre')->get('test')->result();
```

O varios campos, pasando los nombres de los campos en un array 
```php 
$this->db->select(['nombre', 'direccion'])->get('test')->result();
```

## Creación de Modelos 
Para crear un modelo, debemos hacerlo en la carpeta **models**, dentro del directorio **application**. El modelo, debe tener el sufijo **_\_model_**, y la clase debe llamarse igual que el archivo, además debe extender de la clase **CI_Model**. También el modelo debe inicializar el método constructor del padre.

Por ejemplo, se ha creado el modelo [Test_model.php](/application/models/Test_model.php).

Para llamar un método desde el controlador, hacemos lo siguiente:
```php 
$this->load->model('Test_model', 't_model');
```
- El primer parámetro es el nombre del modelo 
- El segundo es el nombre del objeto del modelo que es como un alias del objeto del modelo 
- Como tercer parámetro, también le podemos pasar una configuración de conexión a una nueva base de datos



