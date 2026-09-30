<?php get_header(); ?>

<div id="content" class="site-content" style="padding: 40px 0;">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <!-- Título -->
                <header class="entry-header" style="margin-bottom: 20px;">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>

                <div class="entry-content">
                    <!-- Descripción (Editor nativo) -->
                    <?php the_content(); ?>

                    <!-- Galería de imágenes -->
                    <div class="galeria-alianzas" style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 30px;">
                        <?php
                        $imagenes = get_post_meta( get_the_ID(), 'galeria', false ); 
                        
                        if ( ! empty( $imagenes ) ) {
                            // Extraer el array interno si Pods lo anida
                            if ( isset( $imagenes[0] ) && is_array( $imagenes[0] ) ) {
                                $imagenes = $imagenes[0];
                            }
                            
                            foreach ( $imagenes as $imagen ) {
                                $img_id = is_array( $imagen ) ? $imagen['ID'] : $imagen;
                                if ( is_numeric( $img_id ) ) {
                                    echo wp_get_attachment_image( $img_id, 'medium', false, array( 'style' => 'width: auto; height: 200px; object-fit: cover;' ) );
                                }
                            }
                        }
                        ?>
                    </div>
                </div>

            </article>

        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>