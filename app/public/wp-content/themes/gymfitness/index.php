<?php
    // Llama al header.php, es una funion de WordPress
    get_header();
?>
<main>
<?php
    // Loop de WP: Acceso a los contenidos del post
    while(have_posts()): the_post();
        // Imprime el título del post
        the_title();
        // Imprime el contenedido de la página
        the_content();
    endwhile;
?>
</main>
<?php
    get_footer();
?>