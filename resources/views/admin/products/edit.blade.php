@extends("layouts.admin")

@section("content")

<div class="row">
    <div class="col-md-12">

        @if (session('message'))
            <h5 class="alert alert-success">{{session('message')}}</h5>
        @endif

        <div class="card-header">
            <h4>Edit Product
                <a href="{{ url('admin/products') }}" class="btn btn-danger btn-sm text-white float-end">BACK </a>
            </h4>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-warning">
                    @foreach($errors->all() as $error)
                        <div>{{$error}}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{url('admin/products/'.$product->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <ul class="nav nav-tabs" id="myTab" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">
                            Home
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="seotag-tab" data-bs-toggle="tab" data-bs-target="#seotag-tab-pane" type="button" role="tab" aria-controls="seotag-tab-pane" aria-selected="false">
                            SEO Tags
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane" type="button" role="tab" aria-controls="details-tab-pane" aria-selected="false">
                            Details
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="image-tab" data-bs-toggle="tab" data-bs-target="#image-tab-pane" type="button" role="tab" aria-controls="image-tab-pane" aria-selected="false">
                            Product Image
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="colors-tab" data-bs-toggle="tab" data-bs-target="#colors-tab-pane" type="button" role="tab">
                            Product Colors
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade border p-3 show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                        <div class="mb-3">
                            <label>Category</label>
                            <select name="category-id" class="form-control">
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{$category->id == $product->category_id?'selected':''}}>
                                        {{$category->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Product name</label>
                            <input type="text" name="name" value="{{$product->name}}" class="form-control"/>
                        </div>

                        <div class="mb-3">
                            <label>Product slug</label>
                            <input type="text" name="slug" value="{{$product->slug}}" class="form-control"/>
                        </div>

                        <div class="mb-3">
                            <label>Brands</label>
                            <select name="brand" class="form-control">
                                @foreach ($brands as $brand)
                                    <option value="{{$brand->name}}" {{$brand->name == $product->brand?'selected':''}}>
                                        {{$brand->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Small description (500 words)</label>
                            <textarea name="small-description" class="form-control" rows="4">{{$product->small_description}}</textarea>
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4">{{$product->description}}</textarea>
                        </div>
                    </div>

                    <div class="tab-pane fade border p-3" id="seotag-tab-pane" role="tabpanel" aria-labelledby="seotag-tab" tabindex="0">
                        <div class="mb-3">
                            <label>Meta title</label>
                            <input type="text" name="meta-title" value="{{$product->meta_title}}" class="form-control"/>
                        </div>


                        <div class="mb-3">
                            <label>Meta description</label>
                            <textarea name="meta-description" class="form-control" rows="4">{{$product->meta_description}}</textarea>
                        </div>

                        <div class="mb-3">
                            <label>Meta keyword</label>
                            <textarea name="meta-keyword" class="form-control" rows="4">{{$product->meta_keyword}}</textarea>
                        </div>

                    </div>

                    <div class="tab-pane fade border p-3" id="details-tab-pane" role="tabpanel" aria-labelledby="details-tab" tabindex="0">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Original price</label>
                                    <input type="text" name="original-price" value="{{$product->original_price}}" class="form-control"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Selling price</label>
                                    <input type="text" name="selling-price" value="{{$product->selling_price}}" class="form-control"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Quantity</label>
                                    <input type="number" name="quantity" value="{{$product->quantity}}" class="form-control"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Trending</label>
                                    <input type="checkbox" name="trending" {{$product->trending == '1'?'checked':''}} style="width: 50px; height: 50px;"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Feature</label>
                                    <input type="checkbox" name="feature" {{$product->feature == '1'?'checked':''}} style="width: 50px; height: 50px;"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Status</label>
                                    <input type="checkbox" name="status" {{$product->status == '1'?'checked':''}} style="width: 50px; height: 50px;"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade border p-3" id="image-tab-pane" role="tabpanel" aria-labelledby="image-tab" tabindex="0">
                        <div class="mb3">
                            <label>Upload product image</label>
                            <input type="file" name="image[]" multiple class="form-control"/>
                        </div>

                        <div>
                            @if($product->productImages)
                                <div class="row">
                                    @foreach ($product->productImages as $image)
                                        <div class="col-md-2">
                                            <img src="{{ asset($image->image)}}" style="width: 80px; height: 80px"
                                            class="me-4 border" alt="Img"/>
                                            <a href="{{url('admin/product-image/'.$image->id.'/delete')}}" class="d-block">Remove</a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <h5>No image added</h5>
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane fade border p-3" id="colors-tab-pane" role="tabpanel">
                        <div class="mb3">
                            <h4>Add Product Color</h4>
                            <label>Select color</label>
                            <hr/>
                            <div class="row">
                                @forelse ($colors as $color)
                                    <div class="col-md-3">
                                        <div class="p-2 border mb-3">
                                            Color: <input type="checkbox" name="colors[{{$color->id}}]" value="{{ $color->id }}"/>
                                            {{$color->name}}
                                            <br/>
                                            Quantity: <input type="number" name="color_quantity[{{$color->id}}]" style="width:70px; border:1px"/>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-md-12">
                                        <h1>No colors found</h1>
                                    </div>
                                @endforelse
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Color Name</th>
                                            <th>Quantity</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($productColors as $productColor)
                                            <tr class="product-color-tr">
                                                <td>

                                                    @if($productColor->color)
                                                        {{$productColor->color->name}}
                                                    @else
                                                        No Color Found
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="input-group mb-3" style="width:150px;">
                                                        <input type="text" value="{{$productColor->quantity}}" class="product-color-quantity form-control form-control-sm"/>
                                                        <button type="button" value="{{$productColor->id}}" class="update-product-color-btn btn btn-primary btn-sm text-white">
                                                            Update
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" value="{{$productColor->id}}" class="delete-product-color-btn btn btn-danger btn-sm text-white">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-2 float-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.update-product-color-btn', function() {

            const product_id = "{{ $product->id }}";
            const product_color_id = $(this).val();

            const quantity = $(this).closest('.product-color-tr').find('.product-color-quantity').val();


            if (quantity <= 0) {
                alert("Quantity is required");
                return false;
            }

            const data = {
                'product_id': product_id,
                'quantity': quantity,
            };

            $.ajax({
                type: "POST",
                url: `/admin/product_color/${product_color_id}`,
                data: data,
                success: function(response) {
                    alert(response.message);
                },
            });
        });

        $(document).on('click', '.delete-product-color-btn', function() {
            const product_color_id =$(this).val();
            const thisBtn = $(this);

            $.ajax({
                type: "DELETE",
                url: `/admin/product_color/${product_color_id}/delete`,
                success: function(response) {
                    thisBtn.closest('.product-color-tr').remove();
                    alert(response.message);
                }
            });

        });

    });
</script>
@endsection
