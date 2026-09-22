<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package OnePress
 */

get_header();
$layout = onepress_get_layout();

/**
 * @since 2.0.0
 * @see onepress_display_page_title
 */
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
                    // CAMPOS INFORMACION
                    $post_id = get_the_ID();
                    $imagen_cuadrada = get_post_meta($post_id, 'imagen_cuadrada', true);
                    $es_gratuito = get_post_meta($post_id, 'es_gratuito', true);
                    $es_libre = get_post_meta($post_id, 'es_libre', true);
                    $valor = get_post_meta($post_id, 'valor', true);
                    $fecha_de_inicio = get_post_meta($post_id, 'fecha_de_inicio', true);
                    $modalidad = get_post_meta($post_id, 'modalidad', true);
                    $cupos = get_post_meta($post_id, 'cupos', true);
                    $cupos_usados = get_post_meta($post_id, 'cupos_usados', true);

                    $imagen_retrato = get_post_meta($post_id, 'imagen_retrato', true);

                    //  CAMPOS DE HORARIO
                    $dia = get_post_meta($post_id, 'dia', true);
                    $hora_inicio = get_post_meta($post_id, 'hora_inicio', true);
                    $hora_termino = get_post_meta($post_id, 'hora_termino', true);

                    $dia_2 = get_post_meta($post_id, 'dia_2', true);
                    $hora_inicio_2 = get_post_meta($post_id, 'hora_inicio_2', true);
                    $hora_termino_2 = get_post_meta($post_id, 'hora_termino_2', true);

                    //  CAMPOS DE AVISOS EXTRA
                    $esta_suspendido = get_post_meta($post_id, 'esta_suspendido', true);
                    $aviso_extra_titulo = get_post_meta($post_id, 'aviso_extra_titulo', true);
                    $aviso_extra_descripcion = get_post_meta($post_id, 'aviso_extra_descripcion', true);

                    // Función para extraer IDs de imagen de Pods
                    if (!function_exists('aldea_extraer_id_imagen_pod')) {
                        function aldea_extraer_id_imagen_pod($img_field)
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


                    $id_cuadrada = aldea_extraer_id_imagen_pod($imagen_cuadrada);
                    $id_retrato = aldea_extraer_id_imagen_pod($imagen_retrato);
                    ?>
                    <!-- FICHA TALLER -->
                    <div class="info-taller-pods"
                        style="display: flex; flex-wrap: wrap; gap: 30px; padding: 25px; background: #f9f9f9; border-radius: 10px;">

                        <!-- COLUMNA IZQUIERDA: Detalles de Inscripción -->
                        <div class="ficha" style="flex: 1; min-width: 250px;">
                            <h3 style="margin-top:0;">Ficha del Taller</h3>

                            <ul style="list-style: none; padding: 0; line-height: 1.8;">
                                <?php if ($fecha_de_inicio && $fecha_de_inicio !== '0000-00-00'): ?>
                                    <li><strong>Fecha de inicio:</strong>
                                        <?php echo esc_html($fecha_de_inicio); ?>
                                    </li>
                                <?php endif; ?>

                                <!-- HORARIO 1 -->
                                <?php if ($dia && $hora_inicio): ?>
                                    <li><strong>Horario:</strong>
                                    </li>
                                    <?php echo esc_html($dia); ?>
                                    <?php if ($hora_termino): ?>
                                        de <?php echo esc_html(date('H:i', strtotime($hora_inicio))); ?> a
                                        <?php echo esc_html(date('H:i', strtotime($hora_termino))); ?>
                                    <?php else: ?>
                                        a las <?php echo esc_html(date('H:i', strtotime($hora_inicio))); ?>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <!-- HORARIO 2 -->
                                <?php if ($dia_2 && $hora_inicio_2): ?>
                                    <li> <?php echo esc_html($dia_2); ?>
                                        <?php if ($hora_termino_2): ?>
                                            de <?php echo esc_html(date('H:i', strtotime($hora_inicio_2))); ?> a
                                            <?php echo esc_html(date('H:i', strtotime($hora_termino_2))); ?>
                                        <?php else: ?>
                                            a las <?php echo esc_html(date('H:i', strtotime($hora_inicio_2))); ?>
                                        <?php endif; ?>
                                    </li>
                                <?php endif; ?>

                                <?php if ($modalidad): ?>
                                    <li><strong>Modalidad:</strong>
                                        <?php echo esc_html($modalidad); ?>
                                    </li>
                                <?php endif; ?>
                                <li>

                                    <?php if ($cupos !== '' && $cupos == 0): ?>

                                        <span
                                            style="background: #ccc; color: #333; padding: 4px 10px; border-radius: 20px; font-weight: bold;">
                                            Cupos ilimitados
                                        </span>

                                    <?php elseif ($cupos !== '' && $cupos !== false): ?>
                                        <span
                                            style="background: #ccc; color: #333; padding: 4px 10px; border-radius: 20px; font-weight: bold;">
                                            <?php echo (int) $cupos - (int) $cupos_usados; ?> Cupos Disponibles de
                                            <?php echo esc_html($cupos); ?>
                                        </span>
                                    <?php endif; ?>
                                </li>

                            </ul>

                            <!-- ALERTA TALLER SUSPENDIDO -->
                            <?php if (get_post_meta($post_id, 'esta_suspendido', true)): ?>
                                <div
                                    style="margin-top: 20px; padding: 15px; background: #ffe6e6; border-left: 4px solid #ff4d4d; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                    <strong>Taller Suspendido:</strong> Este taller no está disponible actualmente
                                </div>
                            <?php endif; ?>
                            <!-- LÓGICA DE PRECIO / ACTIVIDAD LIBRE -->
                            <div
                                style="margin-top: 20px; padding: 15px; background: #fff; border: 1px solid #dbdbdb; border-left: 4px solid #03c4a1 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">

                                <?php if ($es_libre): ?>
                                    <h4 style="margin-top: 0; ">Actividad Libre</h4>

                                    <?php if ($es_gratuito): ?>
                                        <p style="margin-bottom: 0;">Este taller es de acceso libre y gratuito. ¡Te esperamos!</p>
                                    <?php elseif ($valor): ?>
                                        <p style="margin-bottom: 5px;">Actividad de acceso libre (sin inscripción previa).</p>
                                        <p style="margin-bottom: 0;"><strong>Valor:</strong> <?php echo esc_html($valor); ?></p>
                                    <?php else: ?>
                                        <p style="margin-bottom: 0;">Actividad de acceso libre. ¡Te esperamos!</p>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <h4 style="margin-top: 0;">Información de Inscripción</h4>

                                    <?php if ($es_gratuito): ?>
                                        <p><strong>Valor:</strong> ¡Gratuito! (Requiere inscripción previa)</p>
                                    <?php elseif ($valor): ?>
                                        <p><strong>Valor:</strong> <?php echo esc_html($valor); ?></p>
                                    <?php endif; ?>

                                    <p style="margin-bottom: 0; font-size: 0.9em; color: #666;"><em>Cupos limitados. Inscríbete
                                            para asegurar tu lugar.</em></p>
                                <?php endif; ?>

                            </div>


                            <!-- ALERTA EXTRA -->
                            <?php if (get_post_meta($post_id, 'aviso_extra_titulo', true) && get_post_meta($post_id, 'aviso_extra_descripcion', true)): ?>
                                <div
                                    style="margin-top: 20px; padding: 15px; background: #e6ebff; border-left: 4px solid #4d74ff; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                    <strong>Aviso:
                                        <?php echo esc_html(get_post_meta($post_id, 'aviso_extra_titulo', true)); ?>
                                    </strong>
                                    <?php echo esc_html(get_post_meta($post_id, 'aviso_extra_descripcion', true)); ?>

                                </div>
                                <div id="inscripcion" style="margin-top:30px;">
                                    <h3>Inscripción</h3>
                                    <?php echo do_shortcode('[contact-form-7 id="996981d" title="Formulario contacto taller"]'); ?>
                                </div>
                            <?php endif; ?>
                            <!-- FORMULARIO DE INSCRIPCIÓN -->
                            <div id="seccion-inscripcion"
                                style="margin-top: 40px; padding: 30px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                                <h3 style="margin-top: 0; text-align: center;">Inscripción al Taller</h3>

                                <?php echo do_shortcode('[contact-form-7 id="33605fe" title="Formulario inscripción taller"]'); ?>
                                <!-- SCRIPT PARA PLACEHOLDER -->
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var campoMensaje = document.querySelector('#seccion-inscripcion textarea[name="your-message"]');

                                        if (campoMensaje) {
                                            // Opción A (Activada): Como Placeholder (Texto fantasma)
                                            campoMensaje.placeholder = "Hola! Me interesa inscribirme al taller <?php echo esc_js(get_the_title()); ?>";

                                            // Opción B (Desactivada): Como texto pre-escrito (Recomendado si quieres recibir este texto por correo).
                                            // Para usar esta opción, quítale las dos barras '//' del principio a la línea de abajo, y pónselas a la línea de arriba.
                                            // campoMensaje.value = "Hola! Me interesa inscribirme al taller <?php echo esc_js(get_the_title()); ?>";
                                        }
                                    });
                                </script>
                            </div>
                            <!-- Los comentarios de wordpress. actualmente sin uso -->
                            <?php
                            // If comments are open or we have at least one comment, load up the comment template.
                            if (comments_open() || get_comments_number()):
                                comments_template();
                            endif;
                            ?>

                        </div>

                        <!-- COLUMNA DERECHA: Imágenes Personalizadas -->
                        <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 15px;">

                            <?php if ($id_retrato): ?>
                                <?php echo wp_get_attachment_image($id_retrato, 'large', false, ['style' => 'width: 100%; height: auto; border-radius: 8px; object-fit: cover;']); ?>
                            <?php endif; ?>

                            <!-- BOTONES DE INSCRIBIRSE Y CONTACTO-->
                            <div
                                style="display: flex; flex-direction: row; justify-content: space-between; gap: 10px; width: 100%; ">
                                <!-- BOTON CONTACTO -->
                                <button class="btn btn-secondary"
                                    style="flex: 3; padding: 12px 10px; font-weight: bold;">Contacto
                                </button>
                                <!-- BOTON INSCRIBIRSE -->
                                <?php if (get_post_meta($post_id, 'esta_suspendido', true)): ?>
                                    <button class="btn btn-primary" style="flex: 4; padding: 12px 10px; font-weight: bold;"
                                        disabled>Inscribirse</button>
                                <?php else: ?>
                                    <button href="#seccion-inscripcion" class="btn btn-primary"
                                        style="flex: 4; padding: 12px 10px; font-weight: bold;">Inscribirse</button>
                                <?php endif; ?>

                            </div>

                            <?php /*
               if ($id_cuadrada): ?>
                   <?php echo wp_get_attachment_image($id_cuadrada, 'large', false, ['style' => 'width: 100%; height: auto; border-radius: 8px; object-fit: cover;']); ?>
               <?php endif;
               */ ?>
                        </div>
                    </div>
                    <!-- DATOS DE AUTOR Y FECHA AL FINAL -->
                    <div class="meta-taller-footer"
                        style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eaeaea; color: #777; font-size: 0.9em; text-transform: uppercase; letter-spacing: 0.5px;">
                        Publicado el <?php echo get_the_date(); ?> por <?php the_author(); ?><br>
                        <span
                            style="font-size: 0.85em; color: #999; display: inline-block; margin-top: 5px; text-transform: none;">
                            Última edición: <?php the_modified_date(); ?> a las <?php the_modified_time(); ?> por
                            <?php echo get_the_modified_author(); ?>
                        </span>
                    </div>



                <?php endwhile; // End of the loop. ?>

            </main>
        </div>

        <?php if ($layout != 'no-sidebar') { ?>
            <?php get_sidebar(); ?>
        <?php } ?>

    </div>
</div>

<?php get_footer(); ?>