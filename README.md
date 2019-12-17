## Crear un tema con Laravel y la WordPress REST API 

Código fuente del vídeotutorial en el que explico cómo realizar una 
integración entre una aplicación que usa Laravel y WordPress a través de la 
WordPress REST API.

Puedes encontrar más documentación en:
- [Documentación de Laravel](https://laravel.com/docs/master).
- [WordPress REST API](https://developer.wordpress.org/rest-api/).

Para realizar la autentificación tienes que instalar y configurar el siguiente 
plugin:
- [Application Passwords](https://wordpress.org/plugins/application-passwords/)

Además tienes que añadir el siguiente código en WordPress (vía plugin 
preferentemente o en el functions.php del tema (optión no recomendada)) 
para añadir un endpoint que te devuelva el contenido del menú principal:

```php
function get_menu() {
    return wp_get_nav_menu_items('menu-principal');
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'wp/v2', '/menu', array(
        'methods' => 'GET',
        'callback' => 'get_menu',
    ) );
} );
```

## Licencia

Este proyecto es código abierto bajo la [licencia MIT](https://opensource.org/licenses/MIT).
