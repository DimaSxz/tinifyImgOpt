@extends ('layouts.app')

@section ('content')
    <div class="container-fluid text-center"
             style="background: url({{ \App\Helpers\ImgTinyOptimiser::getOptimisedImg($product->img_path) }}) no-repeat; background-size: cover; width:100%; height:60vh;"
    >
    </div>
    <div class="container-fluid" style="color: white; background: rgba(0,0,0,.7); padding: 20px;">
        <h1 class="jumbotron-heading">{{ $product->name }}</h1>
        <p class="lead text-muted">{{ $product->description }}</p>
        <p class="lead text-muted">Цена: {{ $product->price}}</p>
        @foreach ($categories as $category)
            <a href="/category/{{ $category->id }}" class="lead text-muted">{{ $category->name }}</a>
        @endforeach
    </div>
@endsection
