# PRUEBA TECNICA PHP

<br>

## _Acerca de_

El proyecto Cafeteria es un _CRUD_ realizado con PHP nativo y MySQL. Este cuenta con las opciones de creación, eliminación, edición, listado y venta de productos. Se realizaron diferentes validaciones en los endpoints, por lo que al ingresar información incorrecta o no ingresar información requerida se retornara un error en formato _JSON_.

## Estructura de la aplicación

<br>

La aplicación cuenta con la separación de la lógica manejando la siguiente estructura de directorios:

- _controllers:_ Dentro de esta carpeta se encuentra el archivo _*products.controllers.php*_, este es el controlador que es llamado al acceder a cada uno de los endpoints. Se encarga de interactuar con la base de datos y retornar las respuestas en formato _JSON_.

- _db:_ Dentro de esta carpeta se encuentra el archivo _*connection.php*_, este se encarga de realizar la conexión con la base de datos y tiene la información de conexión de la misma. Para este proyecto se creo la _base de datos_ _"Cafeteria"_ en la cual se encuentran las tablas de _"products"_ y _"sales"_, la interacción con la base de datos se hizo a traves de _*PDO*_.

- _routes:_ Dentro de esta carpeta se encuentra el archivo _*products.routes.php*_ que se encarga de identificar el _método HTTP_ utilizado y la url ingresada para comunicarse con el _controlador_ y retornandola finalmente al archivo _*index.php*_.

---

## Ambientación del proyecto

<br>

1. Instalar e iniciar los servicios de base de datos y el servidor apache incluidos en [_XAMP(v8.1.12)_](https://www.apachefriends.org/es/download.html).

2. Clonar repositorio dentro del directorio `C:/xampp/htdocs/` _(Para desarrollo)_

```
    git clone https://github.com/camiloosorios/crud-prueba-php.git
```

3. Instalar e inicar [_Postman_](https://www.postman.com/downloads/).

---

## Endpoints de la Aplicación

<br>

El proyecto se va a manejar de manera local, por lo tanto la base de la url va a ser siempre `http://localhost/prueba-php` y los diferentes endpoints serán `get, create, update, delete` y `store`.

1. Para obtener todos los productos se realiza una petición GET a la siguiente URL:

```
    http://localhost/prueba-php/get
```

2. Para crear un nuevo producto se debe realizar una peticion POST a la siguiente URL:

```
    http://localhost/prueba-php/create
```

Adicionalmente se debe enviar por medio del _body_ la información a registrar en formato _JSON_ que debe lucir de la siguiente manera:

```
    {
        "name": "producto1",
        "category": "categoria1",
        "price": 1000,
        "weight": 1,
        "reference": "ref1",
        "stock": 1000
    }
```

3. Para actualizar un producto se debe realizar una peticion PUT a la siguiente URL:

```
    http://localhost/prueba-php/update?id=0
```

Se debe tener en cuenta que este endpoint recibe un parametro en la URL que es el `id` del producto, el cual es obligatorio y ademas la información a actualizar debe enviarse en el body en formato _JSON_ y debe lucir similar a esto:

```
    {
        "name": "producto1",
        "category": "categoria1",
        "price": 1000,
        "weight": 1,
        "reference": "ref1",
        "stock": 1000
    }
```

_Pdt: Los campos en el body son opcionales, solo deben ir los que se requieran actualizar._

4. Para eliminar un producto se debe realizar una peticion DELETE a la siguiente URL:

```
    http://localhost/prueba-php/delete?id=1
```

Se debe tener en cuenta que este endpoint recibe un parametro en la URL que es el `id` del producto, el cual es obligatorio.

5. Para realizar la venta de un producto se debe realizar una peticion POST a la siguiente URL:

```
    http://localhost/prueba-php/store?id=1&quantity=1
```

Se debe tener en cuenta que este endpoint recibe dos parametros en la URL que son `id` (id de producto) y `quantity` (cantidad de productos a vender).

---

## Consultas SQL

<br>

1. Para Obtener el producto con mas stock se debe realizar la siguiente consulta SQL:

```
    SELECT * FROM products
    WHERE stock = (SELECT MAX(stock) FROM products);
```

2. Para obtener el producto más vendido se debe realizar la siguiente consulta SQL:

```
    SELECT products.id, name, sales_quantity, sales.created_at FROM `products`
    INNER JOIN sales
    ON products.id = sales.product_id
    WHERE sales_quantity = (SELECT MAX(sales_quantity) FROM sales);
```
