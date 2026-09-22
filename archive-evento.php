<?php
/**
 * Plantilla para el listado general de Eventos (Archive)
 */

get_header();
$layout = onepress_get_layout();
?>

<div id="content" class="site-content">
    <?php onepress_breadcrumb(); ?>

    <div id="content-inside" class="container <?php echo esc_attr($layout); ?>">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <?php if (have_posts()): ?>

                    <header class="page-header">
                        <?php
                        // Muestra el título
                        the_archive_title('<h1 class="page-title">', '</h1>');
                        the_archive_description('<div class="taxonomy-description">', '</div>');

                        ?>


                    </header>

                    <!-- ==========================================
                         CONTENEDOR DE LA CUADRÍCULA (GRID)
                         ========================================== -->
                    <div class="grid-actividades"
                        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-top: 40px; margin-bottom: 40px;">

                        <?php while (have_posts()):
                            the_post();

                            // 1. OBTENEMOS LOS DATOS DEL POD
                            $id = get_the_ID();
                            $imagen_cuadrada = get_post_meta($id, 'imagen_cuadrada', true);
                            $es_libre = get_post_meta($id, 'es_libre', true);

                            // Extraer el ID de la imagen cuadrada (por si es un array o un número)
                            $id_img = '';
                            if (is_array($imagen_cuadrada) && isset($imagen_cuadrada['ID']))
                                $id_img = $imagen_cuadrada['ID'];
                            elseif (is_array($imagen_cuadrada) && !empty($imagen_cuadrada))
                                $id_img = $imagen_cuadrada[0]['ID'] ?? $imagen_cuadrada[0];
                            elseif (is_numeric($imagen_cuadrada))
                                $id_img = $imagen_cuadrada;

                            // --- EVENTOS ---
                            $evento_recurrente = get_post_meta($id, 'es_recurrente', true);
                            $evento_fecha = get_post_meta($id, 'fecha', true);
                            $evento_proxima_fecha = get_post_meta($id, 'proxima_fecha', true);

                            $fecha_actual = current_time('Y-m-d');
                            ?>

                            <!-- TARJETA INDIVIDUAL -->
                            <article class="tarjeta-actividad"
                                style="background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08); transition: transform 0.3s; display: flex; flex-direction: column;">

                                <!-- IMAGEN SUPERIOR -->
                                <a href="<?php the_permalink(); ?>">
                                    <?php if ($id_img): ?>
                                        <?php echo wp_get_attachment_image($id_img, 'medium_large', false, ['style' => 'width: 100%; height: 250px; object-fit: cover; display: block;']); ?>
                                    <?php elseif (has_post_thumbnail()): ?>
                                        <!-- Respaldo: Si no hay imagen cuadrada, usa la imagen destacada nativa -->
                                        <?php the_post_thumbnail('medium_large', ['style' => 'width: 100%; height: 250px; object-fit: cover; display: block;']); ?>
                                    <?php else: ?>
                                        <!-- Respaldo: Cuadro gris por si se te olvida subir la foto -->
                                        <div
                                            style="width: 100%; height: 250px; background: #eee; display: flex; align-items: center; justify-content: center; color: #999;">
                                            Sin Imagen</div>
                                    <?php endif; ?>
                                </a>

                                <!-- INFORMACIÓN INFERIOR -->
                                <div class="info-tarjeta"
                                    style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">
                                    <h2 style="font-size: 1.3em; margin-top: 0; margin-bottom: 15px; line-height: 1.3;">
                                        <a href="<?php the_permalink(); ?>"
                                            style="color: #333; text-decoration: none;"><?php the_title(); ?></a>
                                    </h2>

                                    <div style="font-size: 0.85em; margin-bottom: 20px; flex-grow: 1;">

                                        <!-- 1. ETIQUETAS DE ACCESO (Solo si el evento es hoy o en el futuro) -->
                                        <?php if ($fecha_actual <= $evento_fecha): ?>
                                            <?php if ($es_libre): ?>
                                                <span class="tag verde">Acceso Libre</span>
                                            <?php else: ?>
                                                <span class="tag gris">Con Inscripción</span>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <!-- 2. ETIQUETAS DE FECHA Y ESTADO -->
                                        <?php if ($evento_recurrente): ?>
                                            <!-- Evento recurrente: -->
                                            <span class="tag azul">
                                                <?php echo esc_html(date_i18n('j \d\e F', strtotime($evento_fecha))); ?>
                                            </span>

                                        <?php elseif ($fecha_actual > $evento_fecha): ?>
                                            <!-- Evento único: Ya pasó -->
                                            <span class="tag negro">Evento pasado</span>

                                        <?php else: ?>
                                            <!-- Evento único: Futuro o es el día de hoy -->
                                            <span class="tag azul">
                                                <?php echo esc_html(date_i18n('j \d\e F', strtotime($evento_fecha))); ?>
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                    <a href="<?php the_permalink(); ?>" class="btn btn-primary"
                                        style="display: block; text-align: center; border-radius: 4px; padding: 12px; margin: 0; width: 100%;">Ver
                                        Detalles</a>
                                </div>

                            </article>

                        <?php endwhile; ?>

                    </div> <!-- FIN DE LA CUADRÍCULA -->

                    <?php
                    // Paginación nativa de OnePress (Página 1, 2, 3...)
                    the_posts_navigation();

                else:
                    // Si no hay publicaciones, muestra el mensaje por defecto
                    get_template_part('template-parts/content', 'none');
                endif;
                ?>

            </main>
        </div>

        <?php if ($layout != 'no-sidebar') {
            get_sidebar();
        } ?>
    </div>
</div>

<?php get_footer(); ?>