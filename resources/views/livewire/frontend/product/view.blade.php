<div>
    <div class="py-3 py-md-5">
        <div class="container">

            <!--Error message, espeacially when the user is
                not authorized for the Wishlist's feature-->
            @if(session()->has('message'))
                <div class="alert alert-info" wire:ignore>
                    {{session('message')}}
                </div>
            @endif

            <div class="row">
                <div class="col-md-5 mt-3">
                    <div class="bg-white border" wire:ignore>
                        @if ($product->productImages)
                            {{-- <img src="{{asset($product->productImages[0]->image)}}" class="w-100" alt="Img"> --}}
                            <div class="exzoom" id="exzoom">
                                <!-- Images -->
                                <div class="exzoom_img_box">
                                  <ul class='exzoom_img_ul'>
                                    @foreach ($product->productImages as $image)
                                        <li><img src="{{asset($image->image)}}"/></li>
                                    @endforeach
                                  </ul>
                                </div>
                                <div class="exzoom_nav"></div>
                                <!-- Nav Buttons -->
                                <p class="exzoom_btn">
                                    <a href="javascript:void(0);" class="exzoom_prev_btn"> < </a>
                                    <a href="javascript:void(0);" class="exzoom_next_btn"> > </a>
                                </p>
                            </div>
                        @else
                            No Image Available
                        @endif
                    </div>
                </div>
                <div class="col-md-7 mt-3">
                    <div class="product-view">
                        <h4 class="product-name">
                            {{$product->name}}

                            {{-- @if ($product->quantity > 0)
                            <label class="label-stock bg-success">In Stock</label>
                            @else
                            <label class="label-stock bg-danger">Out Stock</label>
                            @endif --}}
                        </h4>
                        <hr>
                        <p class="product-path">
                            Home / {{$category->name}} / {{$product->name}}
                        </p>
                        <div>
                            <span class="selling-price">${{$product->selling_price}}</span>
                            <span class="original-price">${{$product->original_price}}</span>
                        </div>

                        <div>

                            <!--Decorate the "Instock/Out of stock" label if the
                                product had different colors or not -->
                            @if ($product->productColors->count() > 0)
                                @foreach ($product->productColors as $productColor)
                                    {{-- <input type="radio" name="colorSelection" value="{{$productColor->id}}"/> {{$productColor->Color->name}} --}}
                                    <label class="colorSelectionLabel" style="background-color: {{$productColor->Color->code}};"
                                        wire:click="colorSelected({{$productColor->id}})">
                                        {{$productColor->Color->name}}
                                    </label>
                                @endforeach
                                <br/>

                                <div>
                                    @if (-1 == $productColorSelectedQuantity)
                                        <label class="label-stock bg-danger">Out of Stock</label>
                                    @elseif ($productColorSelectedQuantity > 0)
                                        <label class="label-stock bg-success">In Stock</label>
                                    @endif
                                </div>
                            @else
                                <div>
                                    @if ($product->quantity > 0)
                                        <label class="label-stock bg-success">In Stock</label>
                                    @else
                                        <label class="label-stock bg-danger">Out of Stock</label>
                                    @endif
                                </div>
                            @endif

                        </div>

                        <div class="mt-2">
                            <div class="input-group">
                                <span class="btn btn1" wire:click="decrementQuantity"><i class="fa fa-minus"></i></span>
                                <input type="text" wire:model="quantityCount" readonly value="{{$this->quantityCount}}" class="input-quantity" />
                                <span class="btn btn1" wire:click="incrementQuantity"><i class="fa fa-plus"></i></span>
                            </div>
                        </div>
                        <div class="mt-2">

                            <!--Hide and unhide the "Add to Cart button if the product is Instock/Out of Stock"-->
                            @if ($product->productColors->count() > 0)
                                @if (0 <= $productColorSelectedQuantity)
                                    <button type="button" wire:click="addToCart({{$product->id}})" class="btn btn1">
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </button>
                                @endif
                            @else
                                @if ($product->quantity > 0)
                                    <button type="button" wire:click="addToCart({{$product->id}})" class="btn btn1">
                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                    </button>
                                @endif
                            @endif

                            <button type="button" wire:click="addToWishlist({{$product->id}})" class="btn btn1">

                                <span wire:loading.remove wire:target="addToWishlist">
                                    <i class="fa fa-heart"></i> Add To Wishlist
                                </span>

                                <span wire:loading wire:target="addToWishlist">
                                    Adding...
                                </span>
                            </button>
                        </div>
                        <div class="mt-3">
                            <h5 class="mb-0">Small Description</h5>
                            <p>
                                {!! $product->small_description !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h4>Description</h4>
                        </div>
                        <div class="card-body">
                            <p>
                                {!! $product->description !!}                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function(){

            $("#exzoom").exzoom({

            // thumbnail nav options
            "navWidth": 60,
            "navHeight": 60,
            "navItemNum": 5,
            "navItemMargin": 7,
            "navBorder": 1,

            // autoplay
            "autoPlay": false,

            // autoplay interval in milliseconds
            "autoPlayTimeout": 2000

            });

        });
    </script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            showCloseButton: true,
            timer: 5000,
            timerProgressBar:true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        window.addEventListener('alert',({detail:{type,message}})=>{
            Toast.fire({
                icon:type,
                title:message
            })
        })
    </script>
@endpush
