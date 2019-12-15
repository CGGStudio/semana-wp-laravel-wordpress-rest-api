<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;


class WordpressController extends Controller
{
    private $url = 'http://wordpress.test/wp-json/wp/v2/';
    protected $numeroDePaginas;
    protected $paginaActual;

    public function index(Request $request)
    {
        $menu = $this->getMenu();
        $settings = $this->getSettings();
        $posts = $this->getPosts($request);
        $numeroDePaginas = $this->numeroDePaginas;
        $paginaActual = $this->paginaActual;
        return view('index', compact('menu', 'settings', 'posts', 'numeroDePaginas', 'paginaActual'));
    }

    public function post($slug) {
        $menu = $this->getMenu();
        $settings = $this->getSettings();
        $post = $this->getPost($slug);
        $featuredMediaURL = '';
        $subhead = 'Publicado';
        if ($post) {
            if ($post->featured_media !== 0) {
                $featuredMediaURL = $this->getMediaURL($post->featured_media);
            }
            if (isset($post->_embedded->author[0])) {
                $subhead .= ' por ' . $post->_embedded->author[0]->name;
            }
            $subhead .= ' el ' . Carbon::createFromFormat('Y-m-d\TH:i:s', $post->date_gmt)->setTimezone('Europe/Madrid')->format('d/m/Y');
        }
        return view('post', compact('menu', 'settings', 'post', 'subhead', 'featuredMediaURL'));
    }

    protected function getMenu()
    {
        $cliente = new Client();
        $response = $cliente->get($this->url . 'menu');
        return json_decode($response->getBody());
    }

    protected function getSettings()
    {
        $cliente = new Client([
            'auth' => [
                getenv('APPLICATION_PASSWORD_USERNAME'),
                getenv('APPLICATION_PASSWORD'),
            ]
        ]);
        $response = $cliente->get($this->url . 'settings');
        return json_decode($response->getBody());
    }

    protected function getPosts(Request $request)
    {
        if ($request->getRequestUri() === '/') {
            $this->paginaActual = 1;
        } else {
            $this->paginaActual = substr($request->getRequestUri(), 7);
        }
        $cliente = new Client();
        $response = $cliente->get($this->url . 'posts?_embed&per_page=5&page=' . $this->paginaActual);
        $this->numeroDePaginas = $response->getHeader('X-WP-TotalPages')[0];
        return json_decode($response->getBody());
    }

    protected function getPost($slug) {
        $cliente = new Client();
        $response = $cliente->get($this->url .'posts?_embed&slug=' . $slug);
        if (!empty(json_decode($response->getBody()))) {
            return json_decode($response->getBody())[0];
        }

        $response = $cliente->get($this->url .'pages?_embed&slug=' . $slug);
        if (!empty(json_decode($response->getBody()))) {
            return json_decode($response->getBody())[0];
        }

        return null;
    }

    protected function getMediaURL($id) {
        $cliente = new Client();
        $response = $cliente->get($this->url . 'media/' . $id);
        return json_decode($response->getBody())->source_url;
    }
}
