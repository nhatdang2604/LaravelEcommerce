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
                                    <option value="{{$brand}}" {{$brand->name == $product->brand?'selected':''}}>
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
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection
