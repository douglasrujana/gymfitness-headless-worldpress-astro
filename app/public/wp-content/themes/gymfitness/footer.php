<footer class="footer container">
    <hr>
    <div class="contenido-footer">
    <?php
        $args = array(
            'theme_location' => 'menu-principal',
            'container' => 'nav',
            'container_class' => 'menu-principal'
        );
        // Menu principal
        wp_nav_menu($args);
    ?>
    <p class="copyright">
        Todos los derechos reservados. <?php echo get_bloginfo('name') . " " . date('Y'); ?> 
    </p>
    </div>
</footer>
// Inyecta css de wp 
<?php wp_footer(); ?>
</body>
</html>