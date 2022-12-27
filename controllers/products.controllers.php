<?php

require_once "db/conection.php";

class ProductsController
{

    public static function createProduct()
    {

        //Se obtiene la información del body enviada como JSON
        $data = json_decode(file_get_contents('php://input'));

        //Se verifica que venga la información necesaria para crear el producto
        if (isset($data->name) && isset($data->reference) && isset($data->price) && isset($data->weight) && isset($data->category) && isset($data->stock)) {

            //Almacenamos en variables la información recibida en el body
            $name      = $data->name;
            $reference = $data->reference;
            $price     = $data->price;
            $weight    = $data->weight;
            $category  = $data->category;
            $stock     = $data->stock;

            //Se valida que el tipo de dato sea el correcto
            if (!is_string($name) || !is_string($reference) || !is_integer($price) || !is_integer($weight) || !is_string($category) || !is_integer($stock)) {
                $json = array(
                    'status' => 400,
                    'message' => 'Tipo de dato incorrecto'
                );

                //Se retorna error en caso que el tipo de dato no sea el adecuado
                echo json_encode($json, http_response_code($json['status']));

                return;
            }

            //Se realiza la sentencia para crear el producto
            $sql = "INSERT INTO `products`(`name`,`reference`,`price`,`weight`,`category`,`stock`) VALUES ('" . $name . "','" . $reference . "'," . $price . "," . $weight . ",'" . $category . "'," . $stock . ")";

            //Se prepara la sentencia
            $statement = Database::connection()->prepare($sql);

            //Se ejecuta la sentencia y guardamos en base de datos
            $statement->execute();

            $json = array(
                'status' => 200,
                'message' => 'Producto creado exitosamente'
            );

            //Se retorna mensaje ecitoso de creación de producto
            echo json_encode($json, http_response_code($json['status']));

            return;
        } else {

            $json = array(
                'status' => 400,
                'message' => 'Información incompleta, No se pudo crear el producto'
            );

            //Si no vienen todos los campos requeridos se retorna un mensaje de error
            echo json_encode($json, http_response_code($json['status']));

            return;
        }
    }

    public static function getProducts()
    {
        //Se realiza la sentencia para traer todos los productos
        $sql = "SELECT * FROM `products`";

        //Se prepara la sentencia
        $statement = Database::connection()->prepare($sql);
        //Se ejecuta la sentencia
        $statement->execute();

        //Se almacena la respuesta
        $response = $statement->fetchAll(PDO::FETCH_CLASS);

        //Se retornan todos los registros como un JSON
        echo json_encode($response);

        return;
    }

    public static function updateProduct()
    {

        //Se valida que venga el id del producto en los parametros
        if (isset($_GET['id'])) {

            //Se almacena el id en una variable
            $id = $_GET['id'];

            //Se obtiene la información a actualizar desde el body
            $data = json_decode(file_get_contents('php://input'));

            //Validamos que no venga vacio el body
            if (empty($data)) {
                $json = array(
                    'status' => 400,
                    'message' => 'No hay información para actualizar'
                );

                //Se retorna JSON con mensaje de error
                echo json_encode($json, http_response_code($json['status']));

                return;
            }

            //Se valida que el id exista en la base de datos
            $sql = "SELECT * FROM `products` WHERE id = " . $id;

            //Se prepara la sentencia SQL
            $statement = Database::connection()->prepare($sql);
            //Se ejecuta la sentencia y se actualiza el registro
            $statement->execute();

            //Se almacena la respuesta de la consulta en una variable
            $response = $statement->fetchAll(PDO::FETCH_CLASS);

            //Se valida que la consulta haya retornado algun registro
            if (count($response) == 0) {

                $json = array(
                    'status' => 400,
                    'message' => 'El producto con ID ' . $id . ' no existe'
                );

                //Se retorna JSON con mensaje de error
                echo json_encode($json, http_response_code($json['status']));

                return;
            }

            //Se realiza la base de la sentencia SQL
            $sql = "UPDATE `products` SET `";

            //Se completa la sentencia con la información del body
            foreach ($data as $key => $value) {
                $sql .= $key . "` = '" . $value . "' , `";
            }

            //Se eliminan los ultimos cractéres para concatenar la condicion
            $sql = substr($sql, 0, -3);
            //Se concatena condicion where
            $sql .= "WHERE `id` = " . $id;

            //Se prepara la sentencia SQL
            $statement = Database::connection()->prepare($sql);
            //Se ejecuta la sentencia y se actualiza el registro
            $statement->execute();

            $json = array(
                'status' => 200,
                'message' => 'Producto acualizado correctamente'
            );

            //Se retorna JSON con mensaje de actualización satisfactoria
            echo json_encode($json, http_response_code($json['status']));

            return;
        } else {

            $json = array(
                'status' => 400,
                'message' => 'El ID del producto es requerido'
            );

            //Se retorna mensaje de error en caso de que no se proporcione el id
            echo json_encode($json, http_response_code($json['status']));

            return;
        }
    }

    public static function deleteProduct()
    {
        //Se valida que venga el id del producto en los parametros
        if (isset($_GET['id'])) {

            //Se almacena el id en una variable
            $id = $_GET['id'];

            //Validamos que el id exista
            $sql = "SELECT * FROM `products` WHERE `id` = " . $id;

            //Se prepara la sentencia SQL
            $statement = Database::connection()->prepare($sql);
            //Se ejecuta la sentencia y se actualiza el registro
            $statement->execute();

            //Se almacena la respuesta de la consulta en una variable
            $response = $statement->fetchAll(PDO::FETCH_CLASS);

            //Se valida que la consulta haya retornado algun registro
            if (count($response) == 0) {

                $json = array(
                    'status' => 400,
                    'message' => 'El producto con ID ' . $id . ' no existe'
                );

                //Se retorna JSON con mensaje de error
                echo json_encode($json, http_response_code($json['status']));

                return;
            }

            //Se realiza la sentencia para eliminar el registro
            $sql = "DELETE FROM `products` WHERE `id` = " . $id;

            //Se prepara la sentencia SQL
            $statement = Database::connection()->prepare($sql);
            //Se ejecuta la sentencia y se actualiza el registro
            $statement->execute();

            $json = array(
                'status' => 200,
                'message' => 'Producto eliminado correctamente'
            );

            //Se retorna JSON con mensaje de eliminación del registro
            echo json_encode($json, http_response_code($json['status']));
        } else {

            $json = array(
                'status' => 400,
                'message' => 'El ID del producto es requerido'
            );

            //Se retorna mensaje de error en caso de que no se proporcione el id
            echo json_encode($json, http_response_code($json['status']));

            return;
        }
    }

    public static function sellProduct()
    {
        if (isset($_GET['id']) && isset($_GET['quantity'])) {

            //Se almacena la información de los parametros en variables
            $id       = $_GET['id'];
            $quantity = $_GET['quantity'];

            //Se realiza sentencia para validar que el producto existe
            $sql = "SELECT * FROM `products` WHERE `id` = " . $id;

            //Se prepara la sentencia SQL
            $statement = Database::connection()->prepare($sql);
            //Se ejecuta la sentencia
            $statement->execute();

            //Se almacena la respuesta obtenida en una variable
            $response = $statement->fetchAll(PDO::FETCH_CLASS);

            //Se valida que la consulta haya retornado algun registro
            if (count($response) == 0) {
                $json = array(
                    'status' => 400,
                    'message' => 'El producto con ID ' . $id . ' no existe'
                );

                //Se retorna mensaje de error
                echo json_encode($json, http_response_code($json['status']));
                return;
            }

            //Se valida que haya suficiente stock del producto
            if ($response[0]->stock >= $quantity) {

                //Se almacena en una variable el nuevo stock
                $newStock = $response[0]->stock - $quantity;

                //Se realiza sentencia SQL para actualizar stock
                $sql = "UPDATE `products` SET `stock`= " . $newStock . " WHERE `id` = " . $id;

                //Se prepara la sentencia SQL
                $statement = Database::connection()->prepare($sql);
                //Se ejecuta la sentencia y se actualiza el registro
                $statement->execute();

                // ------------------ Inserción en tabla sales ------------------------ //

                //Se realiza sentencia SQL para traer todos los registros que tenga ese producto en la tabla sales
                $sql = "SELECT * FROM `sales` WHERE `product_id` = " . $response[0]->id;

                //Se prepara la sentencia SQL
                $statement = Database::connection()->prepare($sql);
                //Se ejecuta la sentencia y se actualiza el registro
                $statement->execute();

                //Almacenamos la respuesta de la consulta en una variable
                $response = $statement->fetchAll(PDO::FETCH_CLASS);

                //Validamos que si existan registros de ese producto en la tabla sales
                if (count($response) > 0) {
                    //Almacenamos la cantidad de ventas en una variable
                    $sales = $response[0]->sales_quantity + $quantity;

                    //Se realiza sentencia SQL para actualizar la cantidad de ventas
                    $sql = "UPDATE `sales` SET `sales_quantity` = " . $sales . " WHERE `product_id` = " . $id;

                    //Se prepara la sentencia SQL
                    $statement = Database::connection()->prepare($sql);
                    //Se ejecuta la sentencia y se actualiza el registro
                    $statement->execute();
                } else {
                    //Se realiza sentencia SQL para crear un nuevo registro en la tabla sales
                    $sql = "INSERT INTO `sales`(`product_id`, `sales_quantity`) VALUES (" . $id . ", " . $quantity . ")";

                    //Se prepara la sentencia SQL
                    $statement = Database::connection()->prepare($sql);
                    //Se ejecuta la sentencia y se actualiza el registro
                    $statement->execute();
                }

                $json = array(
                    'status' => 200,
                    'message' => 'Se realizó la venta correctamente'
                );


                //Se retorna mensaje exitoso
                echo json_encode($json, http_response_code($json['status']));
                return;
            } else {

                $json = array(
                    'status' => 400,
                    'message' => 'No contamos con las cantidades solicitadas, stock insuficiente'
                );

                //Si no hay suficiente Stock se retorna mensaje de error
                echo json_encode($json, http_response_code($json['status']));
                return;
            }
        } else {
            $json = array(
                'status' => 400,
                'message' => 'El ID del producto y la cantidad son requeridas'
            );

            //Si no se proporciona el id o la cantidad se retorna mensaje de error
            echo json_encode($json, http_response_code($json['status']));
            return;
        }
    }
}