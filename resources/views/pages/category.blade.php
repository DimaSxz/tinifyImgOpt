@extends ('layouts.app')

@section ('content')
    <section class="jumbotron text-center"
        style="background: url({{ \App\Helpers\ImgTinyOptimiser::getOptimisedImg($category->img_path) }}) no-repeat; background-size: cover;"
    >
        <div class="container" style="color: white; background: rgba(0,0,0,.7); padding: 20px;">
            <h1 class="jumbotron-heading">{{ $category->name }}</h1>
            <p class="lead text-muted">{{ $category->description }}</p>
            @if (count($products) == 0)
                <p class="alert alert-danger" role="alert">
                    В выбранной категории товаров нет!
                </p>
            @else
                <p>Товаров в категории: {{ count($products) }}</p>
            @endif
        </div>
    </section>

    <div class="container">
        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        @if($product->img_path)
                            <img class="card-img-top"
                                 width="{{ env('IMG_THUMB_WIDTH') }}"
                                 height="{{ env('IMG_THUMB_HEIGHT') }}"
                                 src="{{ \App\Helpers\ImgTinyOptimiser::getOptimisedThumb($product->img_path) }}" alt="{{ $product->name }}">
                        @endif
                        <div class="card-body">
                            <h4 class="card-title">{{ $product->name }}</h4>
                            <p class="card-text">{{ $product->description }}</p>
                        </div>
                        <div class="card-body">
                            <p>Цена: {{ $product->price }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="/product/{{ $product->id }}">Подробнее</a>
                                <small class="text-muted">{{ date('d.m.Y H:m', strtotime($product->created_at)) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
