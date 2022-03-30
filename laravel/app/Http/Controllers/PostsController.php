<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class PostsController extends Controller
{
    public function getPosts() {
        get_header();
        $posts = get_posts();
        dd($posts);
    }
}
