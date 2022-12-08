@extends("layouts.admin")

@section("content")

<div class="row">
    <div class="col-md-12">
        <div class="card-header">
            <h4>Add Category
                <a href="{{ url('admin/category/create') }}" class="btn btn-primary btn-sm text-white float-end">BACK </a>
            </h4>
        </div>

        <div class="card-body">
            <form action="{{ url('admin/category') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control"/>
                        @error('name') <small class="text-danger">{{$message}}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Slug</label>
                        <input type="text" name="slug" class="form-control"/>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" row="3"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control"/>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Status</label><br/>
                        <input type="checkbox" name="status"/>
                    </div>

                    <div class="col-md-12 mb-3">
                        <h4>SEO Tags</h4>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Meta title</label>
                        <input type="text" name="meta-title" class="form-control"/>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Meta keyword</label>
                        <textarea name="meta-keyword" class="form-control" row="3"></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Meta description</label>
                        <textarea name="meta-description" class="form-control" row="3"></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <button type="submit" class="btn btn-primary float-end">Save</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

@endsection
