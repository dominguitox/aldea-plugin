<!-- CONTENEDOR PRINCIPAL  -->
<?php
$title = get_theme_mod('onepress_services_title', esc_html__('Our Services', 'onepress'));
$subtitle = get_theme_mod('onepress_services_subtitle', esc_html__('Section subtitle', 'onepress'));
$desc = get_theme_mod('onepress_services_desc');
?>
<div class="actividades" style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">
    <div class="section-title-area">
        <?php if ($subtitle != '') {
            echo '<h5 class="section-subtitle">' . esc_html($subtitle) . '</h5>';
        } ?>
        <?php if ($title != '') {
            echo '<h2 class="section-title">' . esc_html($title) . '</h2>';
        } ?>
        <?php if ($desc) {
            echo '<div class="section-desc">' . apply_filters('onepress_the_content', wp_kses_post($desc)) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } ?>
    </div>
    <!-- BOTONES DE FILTRO -->
    <div class="filtros-actividades" style="text-align: center; margin-bottom: 30px; margin-top: 20px;">
        <button class="boton btn-filtro activo" data-filtro="todos">Todos</button>
        <button class="boton btn-filtro" data-filtro="taller">Talleres</button>
        <button class="boton btn-filtro" data-filtro="evento">Eventos</button>
    </div>

    <!-- CUADRÍCULA DE TARJETAS -->
    <div class="talleres-destacados-grid"
        style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; min-height: 320px;">
        <?php
        $pod_ajustes = pods('ajustes_del_hero');
        $talleres_elegidos = $pod_ajustes->field('talleres_destacados');
        $eventos_elegidos = $pod_ajustes->field('eventos_destacados');

        $talleres_elegidos = is_array($talleres_elegidos) ? $talleres_elegidos : array();
        $eventos_elegidos = is_array($eventos_elegidos) ? $eventos_elegidos : array();

        $actividades_elegidas = array_merge($eventos_elegidos, $talleres_elegidos);

        if (!empty($actividades_elegidas)) {
            $contador = 0;
            foreach ($actividades_elegidas as $actividad) {

                $id = $actividad['ID'];
                $tipo_post = get_post_type($id);

                $titulo = get_the_title($id);
                $enlace = get_permalink($id);

                // --- IMAGEN ---
                $imagen_cuadrada = get_post_meta($id, 'imagen_cuadrada', true);
                $id_img = '';
                if (is_array($imagen_cuadrada) && isset($imagen_cuadrada['ID']))
                    $id_img = $imagen_cuadrada['ID'];
                elseif (is_array($imagen_cuadrada) && !empty($imagen_cuadrada))
                    $id_img = $imagen_cuadrada[0]['ID'] ?? $imagen_cuadrada[0];
                elseif (is_numeric($imagen_cuadrada))
                    $id_img = $imagen_cuadrada;


                $cupos_usados = (int) get_post_meta($id, 'cupos_usados', true);

                // --- EVENTOS ---
                $evento_recurrente = get_post_meta($id, 'es_recurrente', true);
                $evento_fecha = get_post_meta($id, 'fecha', true);
                $evento_proxima_fecha = get_post_meta($id, 'proxima_fecha', true);

                $fecha_actual = current_time('Y-m-d');
                ?>

                <!-- TARJETA INDIVIDUAL -->
                <article class="tarjeta-taller item-filtrable" data-tipo="<?php echo esc_attr($tipo_post); ?>"
                    style="background: #fff; border-radius: 6px; border: 1px solid #dbdbdb; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08); transition: transform 0.3s; display: flex; flex-direction: column;">

                    <a href="<?php echo esc_url($enlace); ?>">
                        <?php if ($id_img): ?>
                            <?php echo wp_get_attachment_image($id_img, 'medium', false, ['style' => 'width: 100%; height: 180px; object-fit: cover; display: block;']); ?>
                        <?php else: ?>
                            <div
                                style="width: 100%; height: 180px; background: #eee; display: flex; align-items: center; justify-content: center; color: #999; font-size: 0.9em;">
                                Sin Imagen</div>
                        <?php endif; ?>
                    </a>

                    <div class="info-tarjeta"
                        style="padding: 15px; border-top: 1px solid #dbdbdb; display: flex; flex-direction: column; flex-grow: 1;">
                        <h2
                            style="font-size: 1.1em; margin-top: 0; margin-bottom: 15px; line-height: 1.3; min-height: 2.6em; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <a href="<?php echo esc_url($enlace); ?>"
                                style="color: #333; text-decoration: none;"><?php echo esc_html($titulo); ?></a>
                        </h2>

                        <div style="font-size: 0.8em; margin-bottom: 15px; flex-grow: 1;">
                            <?php if ($tipo_post == 'taller'): ?>
                                <?php
                                if (function_exists('imprimir_etiqueta_cupos')) {
                                    imprimir_etiqueta_cupos($id);
                                }
                                ?>

                            <?php endif; ?>
                            <?php if ($tipo_post == 'evento'): ?>
                                <?php if ($evento_recurrente): ?>
                                    <!-- Evento recurrente: proxima fecha-->
                                    <span class="tag verde">
                                        <?php echo esc_html(date_i18n('j \d\e F', strtotime($evento_fecha))); ?>
                                    </span>
                                <?php else: ?>
                                    <?php if ($fecha_actual > $evento_fecha): ?>
                                        <!-- Evento unico: ya pasó-->
                                        <span class="tag rojo">
                                            <?php echo esc_html(date_i18n('j \d\e F', strtotime($evento_fecha))); ?>
                                        </span>
                                    <?php else: ?>
                                        <!-- Evento unico: proxima fecha-->
                                        <span class="tag azul">
                                            <?php echo esc_html(date_i18n('j \d\e F', strtotime($evento_fecha))); ?>
                                        </span>
                                    <?php endif ?>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                        <a href="<?php echo esc_url($enlace); ?>" class="btn btn-primary btn-rojo"
                            style="display: block; text-align: center; border-radius: 4px; padding: 10px; margin: 0; width: 100%; font-size: 0.9em; text-transform: uppercase;">
                            Ver Detalles
                        </a>
                    </div>
                </article>
                <?php
                $contador++;
            }
        }
        ?>

    </div>

</div>

<!-- SCRIPT PARA EL FILTRO CON LÍMITE DE 4 -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const botones = document.querySelectorAll('.btn-filtro');
        const tarjetas = document.querySelectorAll('.item-filtrable');

        function aplicarFiltro(filtroElegido) {
            let mostrados = 0;

            tarjetas.forEach(tarjeta => {
                const tipoTarjeta = tarjeta.getAttribute('data-tipo');

                if ((filtroElegido === 'todos' || tipoTarjeta === filtroElegido) && mostrados < 4) {
                    tarjeta.style.display = 'flex';
                    mostrados++;
                } else {
                    tarjeta.style.display = 'none';
                }
            });
        }

        aplicarFiltro('todos');

        botones.forEach(boton => {
            boton.addEventListener('click', function () {
                botones.forEach(b => {
                    b.style.background = '#fff';
                    b.style.color = '#333';
                    b.style.borderColor = '#ccc';
                });
                this.style.background = '#333';
                this.style.color = '#fff';
                this.style.borderColor = '#333';

                const filtroElegido = this.getAttribute('data-filtro');
                aplicarFiltro(filtroElegido);
            });
        });
    });
</script>