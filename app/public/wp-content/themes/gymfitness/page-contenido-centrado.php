<?php
/**
 * Template Name: Contenido Centrado (No Sidebars)
 */

// Llama al header.php
    get_header();
?>
<h1>No sidebar</h1>
<main class="container seccion contenido-centrado">
<?php
    get_template_part('template-parts/pagina');
?>
</main>
<?php
    get_footer();
?>
