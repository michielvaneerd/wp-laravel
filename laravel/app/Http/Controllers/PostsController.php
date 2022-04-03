<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class PostsController extends Controller
{
    public function getFromWpApi($url) {
        return Http::get(url('wordpress/index.php/wp-json/wp/v2' . $url))->json();
    }

    public function getPosts() {
        if (function_exists('get_header')) {
            // WP has been included inside public/index.php, so we can use all WP functions like in WP templates.
            // Pro: we can use WP functions
            // Cons: - WP is loaded always - Clash of global functions like __() (WP takes precendence)
            //get_header();
            $posts = get_posts();
            dd($posts);
        } else {
            // WP hasn't been included and we can't do it now anymore, so we use the WP api to get the needed content.
            // http://localhost/wordpress/index.php/wp-json/wp/v2
            // Pro: no direct mixing of WP and Laravel, __() function still exists in Laravel.
            
            $posts = Http::get(url('wordpress/index.php/wp-json/wp/v2/posts'))->json();
            return view('frontend.posts', [
                'posts' => $posts
            ]);
            // do something with response...
            //die(__('validation.accepted', ['attribute' => 'Test']));
        }
    }
}
