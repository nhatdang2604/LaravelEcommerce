@extends("layouts.admin")

@section("content")

<div class="row">
    <div class="col-md-12">
        <div class="card-header">
            <h4>Add Slider
                <a href="{{ url('admin/sliders') }}" class="btn btn-danger btn-sm text-white float-end">BACK </a>
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

            <form action="{{ url('admin/sliders/create') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control"/>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control"/>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <input type="checkbox" name="status" style="width:50px;height:30px"/>
                    Checked = Hidden, Unchecked = Visible
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
