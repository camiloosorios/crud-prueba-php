<?php

require_once "controllers/products.controllers.php";

class Products
{

    private $method;
    private $routesArray;
    private $route;

    public function __construct()
    {

        //capturamos la información de la URL
        $this->routesArray = explode('/', $_SERVER['REQUEST_URI']);

        //Se obtiene la ruta a la cual navegar
        $this->route = explode('?', $this->routesArray[2])[0];

        //Obtenemos el método HTTP
        $this->method = $_SERVER['REQUEST_METHOD'];
    }


    public function routes()
    {
        //Creación de productos
        if ($this->method == 'POST' && $this->route == 'create') {

            return ProductsController::createProduct();
        }

        //Obtención de producto
        if ($this->method == 'GET' && $this->route == 'get') {

            return ProductsController::getProducts();
        }

        //Actualización de producto
        if ($this->method == 'PUT' && $this->route == 'update') {

            return ProductsController::updateProduct();
        }

        //Eliminación de producto
        if ($this->method == 'DELETE' && $this->route == 'delete') {

            return ProductsController::deleteProduct();
        }

        //Venta de productos
        if ($this->method == 'POST' && $this->route == 'store') {

            return ProductsController::sellProduct();
        }
    }
}