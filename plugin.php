<?php

/**
 * Plugin Name: WP Slack Slash Command Example
 * Description: An example of using the WordPress REST API as a backend for a Slack Slash Command
 * Author: Andy Brudtkuhl
 * Author URI: http://youmetandy.com
 * Version: 0.1
 * Plugin URI: https://github.com/abrudtkuhl/wp-slack-slash-command
 * License: GPL2+
 */

add_action( 'rest_api_init', function () {
    register_rest_route( 'api', '/slash', array(
        'methods'   =>  'GET',
        'callback'  =>  'get_content',
    ) );
}

function get_content() {
    if( isset( $_GET['token'] ) ) {

        // /heykramer gif
        if( isset( $_GET['command'] ) && $_GET['command'] == 'gif' ) {
            $post = get_posts( array(
                'category_name'     =>  'gifs',
                'orderby'           =>  'rand',
                'posts_per_page'    =>  1,
            ) );
        }

        // /heykramer quote
        if( isset( $_GET['command'] ) && $_GET['command'] == 'quote' ) {
            $post = get_posts( array(
                'category_name'     =>  'quotes',
                'orderby'           =>  'rand',
                'posts_per_page'    =>  1,
            ) );
        }

        // no command given? just give something random
        if( !isset($post) ) {
            $post = get_posts( array(
                'orderby'           =>  'rand',
                'posts_per_page'    =>  1,
            ) );
        }

        // slack formatted response
        $response = array('response_type' => 'in_channel', 'text' => $post[0]->post_content);
        return  $response;
    }

    // huh?
    return "these arent the droids you are looking for :wave:";
}
