@extends('layouts.app')

@section('title', 'Featured Products')

@section('content')

<div class="py-5">
    <div class="container">
        <div class="row">
            @if (0 != $featuredProducts->count())
                <div class="col-md-12">
                    <h4>Featured Products</h4>
                    <div class="underline mb-4"></div>
                </div>
            @endif

            @forelse ($featuredProducts as $product)
                <div class="col-md-3">
                    <div class="product-card">
                        <div class="product-card-img">
                            <label class="stock bg-danger">
                                New
                            </label>

                            @if($product->productImages->count() > 0)
                            <a href="{{url('/collections/'.$product->category->slug.'/'.$product->slug)}}">
                                <img src="{{ asset($product->productImages[0]->image)}}" alt="{{$product->name}}"/>
                            </a>
                            @endif
                        </div>
                        <div class="product-card-body">
                            <p class="product-brand">{{$product->brand}}</p>
                            <h5 class="product-name">
                            <a href="{{url('/collections/'.$product->category->slug.'/'.$product->slug)}}">
                                    {{$product->name}}
                            </a>
                            </h5>
                            <div>
                                <span class="selling-price">${{$product->selling_price}}</span>
                                <span class="original-price">${{$product->original_price}}</span>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12 p-2">
                    <h4>No Featured Products Available</h4>
                </div>
            @endforelse

            <div class="text-center"a>
                <a href="{{url('collections')}}" class="btn btn-warning px-3">View More</a>
            </div>
        </div>
    </div>
</div>

@endsection
