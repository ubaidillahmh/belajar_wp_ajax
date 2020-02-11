<?php

    function autocomplete_form()
    {
        ?>
            <form action="<?php esc_url($_SERVER['REQUEST_URI']) ?>" method="get">
                <p>
                    Cari &nbsp; <input type="text" name="q" id="cari" placeholder="Pencarian"> &nbsp;<p> <button id="subs" type="submit" name="autoc-submit">Cari</button></p>
                </p>
            </form>

        <?php
        if(isset($_GET['autoc-submit']))
        {
            $que    = sanitize_text_field($_GET['q']);

            $paged = ( isset($_GET['hal'])) ? $_GET['hal'] : 1;

            $param  = $que;
            $arg    = array(
                's'                 => $param, 
                'status'            => 'publish',
                'posts_per_page'    => 5,
                'paged'             => $paged
            );

            $query  = new WP_Query($arg);
            // var_dump($query);die;
            while ( $query->have_posts() ) : $query->the_post();
        ?>
            <p><b><?php the_title() ?><b></p><br>
        <?php
            endwhile;
            $big = 999999999;
 
            echo paginate_links( array(
                // 'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?hal=%#%',
                'current' => max( 1, $paged ),
                'total' => $query->max_num_pages
            ) );

            wp_reset_postdata();
        }

    }

    function autocomplete_jquery()
    {
        wp_enqueue_script( 'jquery', plugins_url( 'js/jquery.min.js', __FILE__ ) );
        wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js' );
        wp_enqueue_style( 'jquery-ui-style', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );

        wp_enqueue_script( 'autocomplete-ajax', plugins_url( 'autocomplete_ajax.js', __FILE__ ) );
        wp_localize_script( 'autocomplete-ajax', 'autoc_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
    }

    add_action( 'wp_enqueue_scripts', 'autocomplete_jquery' );

    add_action( 'wp_ajax_nopriv_my_action', 'autocomplete_allpost' );   

    function autocomplete_allpost()
    {
        $param  = $_GET['q'];
        $arg    = array('s' => $param, 'status' => 'publish');

        $query  = new WP_Query($arg);

        $data = array();
        $i = 0;
        // echo '[';
        while ( $query->have_posts() ) : $query->the_post();
        
            $data[$i]['id'] = get_the_ID();
            $data[$i]['data'] = get_the_title();

            $i++;

        endwhile;
        
        echo json_encode($data);

        die();
    }

    // function autocomplete_jquery_script()
    // {
    //     $arg    = array('post_type' => 'post');
    //     $query  = new WP_Query($arg); 
    //     // var_dump($query);die;
    //     

    //     <script type="text/javascript">
        
    //     jQuery(document).ready(function($){
            
    //         $.ajax({
    //             url: ajaxurl,
    //             data:{
    //                 'action': 'autocomplete_allpost'
    //             },
    //             success:function(result){
    //                 console.log(result);
    //             },
    //         });

    //         $('#cari').autocomplete({
    //             source: data
    //         });
            
    //         $('#subs').on('click', function(){
    //             console.log(data);
    //             return false;
    //         });
    //     });
    //     </script>
    // }
    // add_action( 'wp_footer', 'autocomplete_jquery_script' );

    function autocomplete_shortcode()
    {
        ob_start();
        autocomplete_form();
        // autocomplete_jquery_script();

        return ob_get_clean();
    }

    

    add_shortcode('wp6_training', 'autocomplete_shortcode');
?>