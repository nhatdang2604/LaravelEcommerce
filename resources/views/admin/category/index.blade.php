@extends("layouts.admin")

@section("content")

<div class="row">
    <div class="col-md-12">

        @if (session("message"))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="card-header">
            <h4>Category
                <a href="{{ url('admin/category/create') }}" class="btn btn-primary btn-sm float-end">Add Category</a>
            </h4>
        </div>

        <div class="card-body">
        </div>
    </div>
</div>

@endsection
