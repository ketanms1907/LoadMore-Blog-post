Function.php :
-------------------------
add_action('wp_ajax_load_posts_by_ajax', 'load_posts_by_ajax_callback');
add_action('wp_ajax_nopriv_load_posts_by_ajax', 'load_posts_by_ajax_callback');

function load_posts_by_ajax_callback()
	{
	check_ajax_referer('load_more_posts', 'security');

	$paged = $_POST['page'];
	$offset = $paged * 3;
	/*$currentpage_id = get_the_ID(); 
	$current_pageid = array(
		'post_type' => 'testimonials',
	    'page_id' => get_the_ID()
	);*/
	$args = array(
		'post_type' => 'testimonials',
		'posts_per_page' => 3,
		'orderby' => 'date',
		'offset' => $offset,
		'order' => 'DESC',
		'post_status' => 'publish'
		//'post__not_in' => $current_pageid
	);
	$testimonial = new WP_Query();
	$testimonial->query($args);
?>
           <?php
while ( $testimonial->have_posts() ) : $testimonial->the_post(); ?>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 my-posts"> 
                <div class="testimonial-item">
	                <div class="testimonial-hunt-boxes testimonial">
	                  <div class="testimonial-image">
	                  	<?php $testimonial_img = get_the_post_thumbnail_url($testimonial->ID); ?>
	                  	<?php if($testimonial_img) {?>
                            <div class="testimonial-detail-thumb" style="background-image: url('<?php echo $testimonial_img; ?>');"></div> 
                        <?php } ?>
	                  	
	                  </div>
	                  <div class="testimonial-content-sec">
	                  <div class="testimonial-hunt-title"><a href="<?php echo get_the_permalink($news->ID); ?>"><?php echo wp_trim_words( get_the_title(), 15);?></a></div>
	                  <?php $testimonial_short_desc = get_field('testimonial_short_description'); ?>
	                  <?php if(!empty($testimonial_short_desc)){ ?>
	                  <div class="testimonial-hunt-title-text"><?php echo $testimonial_short_desc; ?></div>
	                  <?php } else {?>
	                  <div class="testimonial-hunt-title-text">
	                  	<?php echo wp_trim_words( get_the_content(), 30, '...' );?>
					  </div>
	                  <?php } ?>
	                  <div class="testimonial-hunt-readmore"><a href="<?php echo get_the_permalink($news->ID); ?>">Read more</a></div>
	                </div>
	            	</div>
	              </div>
	          </div>
            <?php endwhile; wp_reset_postdata();
	wp_die();
	}?>
---------------------------------------------------------------------
Page template:

<div class="masonry">
			<?php
              $args = array(
              'post_type'=> 'testimonials',
              'posts_per_page'=> -1,
              'post_status'    => 'publish',
              'order'=>'DESC',
            );
              $posts_array = get_posts( $args );
	              foreach ( $posts_array as $post ) : setup_postdata( $post ); ?>
					<div class="testimonial-item">
	                <div class="testimonial-hunt-boxes testimonial">
	                  <div class="testimonial-image">
	                  	<?php if ( has_post_thumbnail() ) : ?>
						    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						        <?php the_post_thumbnail(); ?>
						    </a>
						<?php endif; ?>
	                  </div>
	                  <div class="testimonial-content-sec">
	                  <div class="testimonial-hunt-title"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></div>
	                  <?php $testimonial_short_desc = get_field('testimonial_short_description'); ?>
	                  <?php if(!empty($testimonial_short_desc)){ ?>
	                  <div class="testimonial-hunt-title-text"><?php echo $testimonial_short_desc; ?></div>
	                  <?php } else {?>
	                  <div class="testimonial-hunt-title-text">
	                  	<?php $content = get_the_content();
							  $content = strip_tags($content);
							  echo substr($content, 0, 100); 
					    ?>
					  </div>
	                  <?php } ?>
	                  <div class="testimonial-hunt-readmore"><a href="<?php the_permalink(); ?>">Read more</a></div>
	              </div>
	                </div>
	              </div>
	              
	              <?php endforeach; 
               		wp_reset_postdata();?>
               		</div>	
---------------------------------------------------------------------

Footer.php
<script type="text/javascript">
      jQuery(document).ready(function($){
          var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
          var page = 1;
          var post_type = jQuery('#post-type').val();
          jQuery(function($) {
            $('body').on('click', '.loadmore', function(e) { 
              var btn = $(this);
              e.preventDefault();
              // console.log(page);
              var data = {
                'action': 'load_posts_by_ajax',
                'page': page,
                'post_type': post_type,
                'security': '<?php echo wp_create_nonce("load_more_posts"); ?>'
              };
              $.post(ajaxurl, data, function(response) {
                //console.log(response.length);
                
                $('.my-posts').last().after(response);
                page++;
                
                if( max_num_page == page ) 
                  $('.loadmore').hide();   
              });
            });
          });
      });    
    </script>
Footer
