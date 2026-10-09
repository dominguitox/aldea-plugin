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
                        $imagenes = get_post_meta(get_the_ID(), 'galeria', false);

                        if (!empty($imagenes)) {
                            if (isset($imagenes[0]) && is_array($imagenes[0])) {
                                $imagenes = $imagenes[0];
                            }

                            $urls_imagenes = [];
                            foreach ($imagenes as $imagen) {
                                $img_id = is_array($imagen) ? $imagen['ID'] : $imagen;
                                if (is_numeric($img_id)) {
                                    $url = wp_get_attachment_image_url($img_id, 'medium_large');
                                    if ($url)
                                        $urls_imagenes[] = $url;
                                }
                            }

                            if (!empty($urls_imagenes)):
                                ?>
                                <div id="contenedor-carrusel-<?php the_ID(); ?>"
                                    style="position: relative; max-width: 600px; height: 300px; border-radius: 8px; overflow: hidden; background: #eaeaea;">
                                    <img id="rotador-<?php the_ID(); ?>" src="<?php echo esc_url($urls_imagenes[0]); ?>"
                                        style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.5s ease-in-out;">

                                    <?php if (count($urls_imagenes) > 1): ?>
                                        <button id="btn-prev-<?php the_ID(); ?>"
                                            style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; font-size: 18px;">&#10094;</button>
                                        <button id="btn-next-<?php the_ID(); ?>"
                                            style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; font-size: 18px;">&#10095;</button>
                                    <?php endif; ?>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const urls = <?php echo json_encode($urls_imagenes); ?>;
                                        const contenedor = document.getElementById('contenedor-carrusel-<?php the_ID(); ?>');
                                        const imgEl = document.getElementById('rotador-<?php the_ID(); ?>');
                                        const btnPrev = document.getElementById('btn-prev-<?php the_ID(); ?>');
                                        const btnNext = document.getElementById('btn-next-<?php the_ID(); ?>');
                                        let i = 0;
                                        let intervalo;

                                        function cambiarImagen(index) {
                                            imgEl.style.opacity = 0;
                                            setTimeout(() => {
                                                imgEl.src = urls[index];
                                                imgEl.style.opacity = 1;
                                            }, 500);
                                        }

                                        function iniciarIntervalo() {
                                            clearInterval(intervalo);
                                            intervalo = setInterval(() => {
                                                i = (i + 1) % urls.length;
                                                cambiarImagen(i);
                                            }, 6000);
                                        }

                                        if (urls.length > 1) {
                                            iniciarIntervalo();

                                            btnNext.addEventListener('click', () => {
                                                i = (i + 1) % urls.length;
                                                cambiarImagen(i);
                                                iniciarIntervalo();
                                            });

                                            btnPrev.addEventListener('click', () => {
                                                i = (i - 1 + urls.length) % urls.length;
                                                cambiarImagen(i);
                                                iniciarIntervalo();
                                            });

                                            contenedor.addEventListener('mouseenter', () => {
                                                clearInterval(intervalo);
                                            });

                                            contenedor.addEventListener('mouseleave', () => {
                                                iniciarIntervalo();
                                            });
                                        }
                                    });
                                </script>
                                <?php
                            endif;
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