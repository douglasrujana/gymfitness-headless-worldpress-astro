<?php
// Llama al header.php
get_header();
?>        
    <main class="container seccion">
        <?php
            // Loop de WP: Acceso a los contenidos del post
            get_template_part('template-parts/pagina');
        ?>
    </main>
<?php
get_footer();
?>    