<div>
    <div class="py-3 py-md-5 bg-light">
        <div class="container">
            <h4>My Cart</h4>
            <hr/>
            <div class="row">
                <div class="col-md-12">
                    <div class="shopping-cart">

                        @if ($cart->count() > 0)
                            <div class="cart-header d-none d-sm-none d-mb-block d-lg-block">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h4>Products</h4>
                                    </div>
                                    <div class="col-md-2">
                                        <h4>Colors</h4>
                                    </div>
                                    <div class="col-md-1">
                                        <h4>Price</h4>
                                    </div>
                                    <div class="col-md-2">
                                        <h4>Quantity</h4>
                                    </div>
                                    <div class="col-md-1">
                                        <h4>Total</h4>
                                    </div>
                                    <div class="col-md-2">
                                        <h4>Remove</h4>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @forelse ($cart as $item)
                            <div class="cart-item">
                                <div class="row">
                                    <div class="col-md-4 my-auto">
                                        <a href="{{url('collections/'.$item->product->category->slug.'/'.$item->product->slug)}}">
                                            <label class="product-name">
                                                <img src="{{asset($item->product->productImages[0]->image)}}"
                                                        style="width: 50px; height: 50px"
                                                        alt="{{$item->product->name}}">
                                                {{$item->product->name}}
                                            </label>
                                        </a>
                                    </div>
                                    <div class="col-md-2 my-auto">
                                        @if ($item->productColor)
                                            <label class="color">{{$item->productColor->Color->name}}</label>
                                        @endif
                                    </div>
                                    <div class="col-md-1 my-auto">
                                        <label class="price">${{$item->product->selling_price}} </label>
                                    </div>
                                    <div class="col-md-2 col-7 my-auto">
                                        <div class="quantity">
                                            <div class="input-group">

                                                <button type="button" wire:loading.attr="disable" wire:click="decrementQuantity({{$item->id}})" class="btn btn1"><i class="fa fa-minus"></i></button>
                                                <input type="text" wire:model="quantityMap.{{$item->id}}" value="{{$this->quantityMap[$item->id]}}" readonly class="input-quantity" />
                                                <button type="button" wire:loading.attr="disable" wire:click="incrementQuantity({{$item->id}})" class="btn btn1"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 my-auto">
                                        <label class="price">${{$item->product->selling_price * $this->quantityMap[$item->id]}} </label>
                                    </div>
                                    <div class="col-md-2 col-5 my-auto">
                                        <div class="remove">
                                            <a href="" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i> Remove
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <h4>No Item in Cart</h4>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
