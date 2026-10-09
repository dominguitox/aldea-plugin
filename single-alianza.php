<?php get_header(); ?>

<div id="content" class="site-content" style="padding: 40px 0;">
    <div class="container">
        <?php while (have_posts()):
            the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <!-- Cabecera: Logo, Título y Enlace -->
                <header class="entry-header" style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px;">
                    <?php
                    // Obtener logo
                    $logo = get_post_meta(get_the_ID(), 'logo_institucion', true);
                    if ($logo) {
                        $logo_id = is_array($logo) ? $logo['ID'] : $logo;
                        echo wp_get_attachment_image($logo_id, 'thumbnail', false, array('style' => 'max-width: 120px; height: auto; object-fit: contain;'));
                    }
                    ?>
                    <div>
                        <?php the_title('<h1 class="entry-title" style="margin: 0 0 10px 0;">', '</h1>'); ?>

                        <?php
                        // Obtener sitio web
                        $sitio_web = get_post_meta(get_the_ID(), 'sitio_web', true);
                        if ($sitio_web): ?>
                            <a href="<?php echo esc_url($sitio_web); ?>" target="_blank" rel="noopener noreferrer"
                                style="font-weight: bold; text-decoration: none;">
                                Visitar sitio web &rarr;
                            </a>
                        <?php endif; ?>
                    </div>
                </header>

                <!-- Descripción -->
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <style>
                    .grid-alianzas {
                        display: grid;
                        grid-template-columns: 1fr;
                        gap: 20px;
                    }

                    @media (min-width: 768px) {
                        .grid-alianzas {
                            grid-template-columns: 1fr 1fr;
                        }
                    }
                </style>

                <!-- Grid Galería + Descripcion 2 (Apertura corregida sin cierre prematuro) -->
                <div id="galeria" class="grid-alianzas">

                    <!-- Columna 1: Carrusel -->
                    <div style="margin-bottom: 20px;">
                        <?php
                                if (function_exists('imprimir_galeria_rotatoria')) {
                                    imprimir_galeria_rotatoria($id);
                                }
                                ?>

                    </div>

                    <!-- Columna 2: Descripción 2 -->
                    <div>
                        <?php
                        $descripcion = get_post_meta(get_the_ID(), 'descripcion', true);

                        if ($descripcion) {
                            echo wpautop(wp_kses_post($descripcion));
                        }
                        ?>
                    </div>

                </div>

            </article>

        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>