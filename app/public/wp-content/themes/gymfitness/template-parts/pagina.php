<?php
// Loop de WP: Acceso a los contenidos del post
while (have_posts()): the_post();
    // Imprime el título del post
    the_title('<h1 class="text-center text-primary">', '</h1>');
    // si hay un aimagen se muestra 
    if (has_post_thumbnail()) {
        // Muestra la imágen destacada
        the_post_thumbnail('full', array('class' => 'imagen-destacada'));
    }
    // Imprime el contenedido de la página
    the_content();
endwhile;
?>