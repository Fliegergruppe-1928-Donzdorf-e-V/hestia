<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package Hestia
 * @since Hestia 1.0
 * @modified 1.1.30
 */

get_header();

$default                 = hestia_get_blog_layout_default();
$sidebar_layout          = apply_filters( 'hestia_sidebar_layout', get_theme_mod( 'hestia_blog_sidebar_layout', $default ) );
$wrap_class              = apply_filters( 'hestia_filter_index_search_content_classes', 'col-md-8 blog-posts-wrap' );
$alternative_blog_layout = get_theme_mod( 'hestia_alternative_blog_layout', 'blog_normal_layout' );
$wrap_posts              = 'flex-row';
if ( Hestia_Public::should_enqueue_masonry() === true ) {
	$wrap_posts .= ' post-grid-display';
}

do_action( 'hestia_before_index_wrapper' ); ?>

<div class="<?php echo hestia_layout(); ?>">
	<div class="hestia-blogs" data-layout="<?php echo esc_attr( $sidebar_layout ); ?>">
		<div class="container">
			<?php

			do_action( 'hestia_before_index_posts_loop' );
			$paged         = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$posts_to_skip = ( $paged === 1 ) ? apply_filters( 'hestia_filter_skipped_posts_in_main_loop', array() ) : array();

			?>



				
			
			
			
			<div id="carousel-container">
				<img src="" id="random-image-1" style="z-order: 5; position: absolute; top: 0; left: 0; transition: opacity 1s ease; height: 0px; opacity: 1; border: #dddddd solid 5px; border-radius: 10px">
                <img src="" id="random-image-2" style="z-order: 4; position: absolute; top: 0; left: 0; transition: opacity 1s ease; height: 0px; opacity: 0; border: #dddddd solid 5px; border-radius: 10px">
                <img src="" id="random-image-3" style="z-order: 3; position: absolute; top: 0; left: 0; height: 0px; opacity: 0; border: #dddddd solid 5px; border-radius: 10px">
            </div>
			<br/>
			<br/>
            <script>
                document.addEventListener('DOMContentLoaded', function () {

                    IMAGE_COUNT = 149;

                    var images = [];
                    for (let index = 1; index < IMAGE_COUNT; index++) { 
                        let name = '/wp-content/carousel/carousel' + index.toString().padStart(3, "0") + '.jpg';
                        images.push(name);
                    }

                    var currentElementIdx = 0;
                    var previousElementIdx = 1;
                    var imageElements = [
                        document.getElementById('random-image-1'),
                        document.getElementById('random-image-2'),
                        document.getElementById('random-image-3')
                    ];

                    var carouselcontainer = document.getElementById('carousel-container')

                    var counter = 0;

                    var previousNumbers = []

                    function showRandomImage() {
                        counter++;

                        
                        var newImg;
                        var randomAttempts = 0;
                        while (true) {
                            randomAttempts++;
                            newImg = Math.floor(Math.random() * IMAGE_COUNT - 1);
                            if (randomAttempts > 3) break;
                            if (!previousNumbers.includes(newImg)) {
                                previousNumbers.push(newImg);
                                break;
                            }
                        }
                    
                        var temp = currentElementIdx;
                        currentElementIdx = previousElementIdx;
                        previousElementIdx = temp;

                        var newImageElement = imageElements[currentElementIdx];
                        var oldImageElement = imageElements[previousElementIdx];
                        var loadImageElement = imageElements[2];

                        if (counter > 1) {
                            newImageElement.src = loadImageElement.src;
                        } else {
                            newImageElement.src = images[newImg+1];
                        }
                        loadImageElement.src = images[newImg];
                        newImageElement.style.opacity = 1;
                        oldImageElement.style.opacity = 0;

                        let w = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0)

                        newImageElement.style.width = w + "px";
                        newImageElement.style.height = "auto";
                        oldImageElement.style.width = w + "px";
                        oldImageElement.style.height = "auto";
                        var factor = 8;
                        //if (w < 800) factor = 9;
                        //if (w < 500) factor = 10;
                        carouselcontainer.style.height = (w / 16 * factor) + "px";
                    }
                    setInterval(showRandomImage, 3000);
                    showRandomImage(true);
                });                                     
            </script>
			
			
			
		
			
	

			
			<div class="row">
				<?php
				if ( $sidebar_layout === 'sidebar-left' ) {
					get_sidebar();
				}
				?>
				<div class="<?php echo esc_attr( $wrap_class ); ?>">
					<?php
					do_action( 'hestia_before_index_content' );

					$counter = 0;
					if ( have_posts() ) {
						echo '<div class="' . esc_attr( $wrap_posts ) . '">';
						while ( have_posts() ) {
							the_post();
							$counter ++;
							$pid = get_the_ID();
							if ( ! empty( $posts_to_skip ) && in_array( $pid, $posts_to_skip, true ) ) {
								$counter ++;
								continue;
							}
							if ( $alternative_blog_layout === 'blog_alternative_layout2' ) {
								get_template_part( 'template-parts/content', 'alternative-2' );
							} elseif ( ( $alternative_blog_layout === 'blog_alternative_layout' ) && ( $counter % 2 === 0 ) ) {
								get_template_part( 'template-parts/content', 'alternative' );
							} else {
								get_template_part( 'template-parts/content' );
							}
						}
						echo '</div>';
						$hestia_pagination_type = get_theme_mod( 'hestia_pagination_type', 'number' );
						if ( $hestia_pagination_type === 'number' ) {
							do_action( 'hestia_before_pagination' );
							the_posts_pagination();
							do_action( 'hestia_after_pagination' );
						}
					} else {
						get_template_part( 'template-parts/content', 'none' );
					}
					?>
				</div>
				<?php
				if ( $sidebar_layout === 'sidebar-right' ) {
					get_sidebar();
				}
				?>
			</div>
		</div>
	</div>
	<?php do_action( 'hestia_after_archive_content' ); ?>

	<?php get_footer(); ?>
