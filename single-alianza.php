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

                <!-- Galería de imágenes -->
                <?php
                $imagenes = get_post_meta(get_the_ID(), 'galeria', false);

                if (!empty($imagenes)):
                    if (isset($imagenes[0]) && is_array($imagenes[0])) {
                        $imagenes = $imagenes[0];
                    }
                    ?>
                    <div class="galeria-alianzas" style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 40px;">
                        <?php
                        foreach ($imagenes as $imagen) {
                            $img_id = is_array($imagen) ? $imagen['ID'] : $imagen;
                            if (is_numeric($img_id)) {
                                echo wp_get_attachment_image($img_id, 'medium', false, array('style' => 'width: auto; height: 200px; object-fit: cover; border-radius: 8px;'));
                            }
                        }
                        ?>
                    </div>
                <?php endif; ?>

            </article>

        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>