<?php

$user = array_key_exists( 'PHP_AUTH_USER', $_SERVER) ? $_SERVER['$PHP_AUTH_USER'] : '';
$pwd = array_key_exists( 'PHP_AUTH_PW', $_SERVER) ? $_SERVER['PHP_AUTH_PW'] : '';

if ( $user !== 'jhon' || $pwd !== '1234') {
    die;
}

// Definimos los recursos disponibles
$allowedResourcetypes = [
    'books',
    'authors',
    'genres',
];

// Validamos que el recurso este disponible
$resourceType = $_GET['resource_type'];

if(!in_array($resourceType, $allowedResourcetypes)) {
    die;
}

// Defino los recursos
$books = [
    1 => [
        'titulo' => 'Lo que el viento se llevo',
        'id_autor' => 2,
        'id_genero' => 2,
    ],
    2 => [
        'titulo' => 'La Iliada',
        'id_autor' => 1,
        'id_genero' => 1,
    ],
    3 => [
        'titulo' => 'La Odisea',
        'id_autor' => 1,
        'id_genero' => 1,
    ],
];

header('Content-Type: aplication/json');
// Levantamos el id del recurso buscado
$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : '';

// Generamos la respuesta asumiendo que el pedido es correcto
switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
    //echo $_SERVER['REQUEST_METHOD'];
    case 'GET':
        if ( empty( $resourceId ) ) {
            echo json_encode( $books );
        } else {
            if (array_key_exists( $resourceId, $books ) ) {
                echo json_encode( $books[ $resourceId ] );
            }
        }
        //echo json_encode($books);
        break;
    case 'POST':
        $json = file_get_contents('php://input');
        $books[] = json_decode($json, true);

        //echo array_keys( $books )[ count($books) - 1 ];
        echo json_encode($books);
        break;
    case 'PUT':
        //validamos que el recurso buscado exista
        if (!empty($resourceId) && array_key_exists($resourceId, $books)){
            // Tomamos la entrada cruda
            $json = file_get_contents('php://input');
             // transformamos el json recibido a un nuevo elemento del arreglo
            $books[$resourceId] = json_decode($json, true);
            // Retornamos la coleccion modificada en formato json
            echo json_encode($books);
        }
        break;
    case 'DELETE':
        if (!empty($resourceId) && array_key_exists($resourceId, $books)) {
            unset( $books[ $resourceId ] );
        }

        echo json_encode( $books );
        break;
}

?>
