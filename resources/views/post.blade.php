@extends('layouts.app')

@section('head_title', $settings->title)
@section('wordpress_title', $settings->title)
@if($post)
    @section('site_heading_title', $post->title->rendered)
@section('site_subheading_title', $subhead)
@else
    @section('site_heading_title', '404')
@section('site_subheading_title', 'Elemento no encontrado')
@endif
@if($featuredMediaURL === '')
    @section('header_background_image', 'img/home-bg.jpg')
@else
    @section('header_background_image', $featuredMediaURL)
@endif
@section('main-content')
    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                @if ($post)
                    <div class="post-preview">
                        <p>
                            {!! $post->content->rendered !!}
                        </p>
                        @if (isset($post->_embedded->replies[0]))
                            <h3>Comentarios</h3>
                            @foreach(array_reverse($post->_embedded->replies[0]) as $comentario)
                                <hr>
                                <p>
                                    <small>
                                        Publicado por <a href="{{ $comentario->author_url }}">{{ $comentario->author_name }}</a>
                                        el día
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s', $comentario->date)->setTimezone('Europe/Madrid')->format('d/m/Y') }}
                                        a las
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d\TH:i:s', $comentario->date)->setTimezone('Europe/Madrid')->format('H:i') }}
                                    </small>
                                </p>
                                <p>
                                    {!! $comentario->content->rendered !!}
                                </p>
                            @endforeach
                        @endif
                    </div>
                    <hr>
                @endif
            </div>
        </div>

        <hr>
    </div>
@endsection
