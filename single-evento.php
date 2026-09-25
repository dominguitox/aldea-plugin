<?php
/**
 * The template for displaying all single posts for Eventos.
 *
 * @package OnePress
 */

get_header();
$layout = onepress_get_layout();

?>

<div id="content" class="site-content">

    <?php onepress_breadcrumb(); ?>

    <div id="content-inside" class="container <?php echo esc_attr($layout); ?>">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

                <?php while (have_posts()):
                    the_post(); ?>

                    <?php get_template_part('template-parts/content', 'single'); ?>

                    <?php
                    // --- INICIO DE CAMPOS INFORMACION EVENTO ---
                    $post_id = get_the_ID();

                    // Campos de recurrencia
                    $proxima_fecha = get_post_meta($post_id, 'proxima_fecha', true);
                    $frecuencia = get_post_meta($post_id, 'frecuencia', true);

                    // Campos reciclados (asumiendo que usas los mismos nombres que en talleres)
                    $hora_inicio = get_post_meta($post_id, 'hora_inicio', true);
                    $hora_termino = get_post_meta($post_id, 'hora_termino', true);
                    $es_libre = get_post_meta($post_id, 'es_libre', true);
                    $valor = get_post_meta($post_id, 'valor', true);

                    $imagen_cuadrada = get_post_meta($post_id, 'imagen_cuadrada', true);
                    $imagen_retrato = get_post_meta($post_id, 'imagen_retrato', true);

                    // Función de ayuda para extraer IDs de imagen de Pods
                    if (!function_exists('extraer_id_imagen_pod')) {
                        function extraer_id_imagen_pod($img_field)
                        {
                            if (is_array($img_field) && isset($img_field['ID']))
                                return $img_field['ID'];
                            if (is_array($img_field) && !empty($img_field))
                                return $img_field[0]['ID'] ?? $img_field[0];
                            if (is_numeric($img_field))
                                return $img_field;
                            return false;
                        }
                    }
                    $id_cuadrada = extraer_id_imagen_pod($imagen_cuadrada);
                    $id_retrato = extraer_id_imagen_pod($imagen_retrato);

                    // LÓGICA AUTOMÁTICA DE FECHA CADUCADA
                    $evento_pasado = false;
                    $texto_boton = "Inscribirse";

                    if ($proxima_fecha) {
                        // Compara la fecha del evento con la fecha de hoy
                        $fecha_del_evento = strtotime($proxima_fecha);
                        $fecha_hoy = strtotime('today');

                        if ($fecha_del_evento < $fecha_hoy) {
                            $evento_pasado = true;
                            $texto_boton = "Próxima fecha por confirmar";
                        }
                    }
                    ?>

                    <div class="info-evento-pods"
                        style="display: flex; flex-wrap: wrap; gap: 30px; margin-top: 40px; padding: 25px; background: #f9f9f9; border-radius: 10px;">

                        <!-- COLUMNA IZQUIERDA: Detalles del Evento -->
                        <div style="flex: 1; min-width: 250px;">
                            <h3 style="margin-top:0;">Detalles del Evento</h3>
                            <ul style="list-style: none; padding: 0; line-height: 1.8;">

                                <?php if ($frecuencia): ?>
                                    <li><strong>🔄 Frecuencia:</strong> <?php echo esc_html($frecuencia); ?></li>
                                <?php endif; ?>

                                <?php if ($proxima_fecha): ?>
                                    <li><strong>Próxima edición:</strong>
                                        <?php
                                        if ($evento_pasado) {
                                            echo '<span style="color: red; font-weight: bold;">Por anunciar</span>';
                                        } else {
                                            echo esc_html($proxima_fecha);
                                        }
                                        ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($hora_inicio): ?>
                                    <li><strong>⏰ Horario:</strong>
                                        <?php if ($hora_termino): ?>
                                            de <?php echo esc_html(date('H:i', strtotime($hora_inicio))); ?> a
                                            <?php echo esc_html(date('H:i', strtotime($hora_termino))); ?>
                                        <?php else: ?>
                                            a las <?php echo esc_html(date('H:i', strtotime($hora_inicio))); ?>
                                        <?php endif; ?>
                                    </li>
                                <?php endif; ?>
                            </ul>

                            <!-- LÓGICA DE PRECIO / ACTIVIDAD LIBRE -->
                            <div
                                style="margin-top: 20px; padding: 15px; background: #fff; border: 1px solid #dbdbdb; border-left: 4px solid #03c4a1 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                <?php if ($es_libre): ?>
                                    <h4 style="margin-top: 0;">Actividad Libre</h4>
                                    <p style="margin-bottom: 0;">Este evento es de acceso libre y gratuito. ¡Te esperamos!</p>
                                <?php else: ?>
                                    <h4 style="margin-top: 0;">Información de Inscripción</h4>
                                    <?php if ($valor): ?>
                                        <p><strong>Valor:</strong> <?php echo esc_html($valor); ?></p>
                                    <?php endif; ?>
                                    <p style="margin-bottom: 0; font-size: 0.9em; color: #666;"><em>Inscríbete para asegurar tu
                                            lugar.</em></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA: Imágenes y Botones -->
                        <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 15px;">
                            <?php if ($id_cuadrada): ?>
                                <?php echo wp_get_attachment_image($id_cuadrada, 'large', false, ['style' => 'width: 100%; height: auto; border-radius: 8px; object-fit: cover;']); ?>
                            <?php endif; ?>

                            <!-- BOTONES     -->
                            <div
                                style="display: flex; flex-direction: row; justify-content: space-between; gap: 10px; width: 100%;">
                                <!-- BOTON INSCRIBIRSE -->
                                <button class="btn btn-primary" style="flex: 4; padding: 12px 10px; font-weight: bold;"
                                    <?php if ($evento_pasado)
                                        echo 'disabled style="background: #ccc; border: none;"'; ?>>
                                    <?php echo esc_html($texto_boton); ?>
                                </button>
                                <!-- BOTON CONTACTO -->
                                <button class="btn btn-secondary"
                                    style="flex: 3; padding: 12px 10px; font-weight: bold;">Contacto</button>
                            </div>

                            <?php if ($id_retrato): ?>
                                <?php echo wp_get_attachment_image($id_retrato, 'large', false, ['style' => 'width: 100%; height: auto; border-radius: 8px; object-fit: cover;']); ?>
                            <?php endif; ?>
                        </div>

                    </div>
                    <?php // --- FIN DE CAMPOS INFORMACION EVENTO --- ?>

                    <!-- DATOS DE AUTOR Y FECHA AL FINAL -->
                    <div class="meta-evento-footer"
                        style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eaeaea; color: #777; font-size: 0.9em; text-transform: uppercase; letter-spacing: 0.5px;">
                        Publicado el
                        <?php echo get_the_date(); ?> por
                        <?php the_author(); ?><br>
                        <span
                            style="font-size: 0.85em; color: #999; display: inline-block; margin-top: 5px; text-transform: none;">
                            Última edición:
                            <?php the_modified_date(); ?> a las
                            <?php the_modified_time(); ?> por
                            <?php echo get_the_modified_author(); ?>
                        </span>
                    </div>

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()):
                        comments_template();
                    endif;
                    ?>

                <?php endwhile; // End of the loop. ?>

            </main>
        </div>

        <?php if ($layout != 'no-sidebar') { ?>
            <?php get_sidebar(); ?>
        <?php } ?>

    </div>
</div>

<?php get_footer(); ?>