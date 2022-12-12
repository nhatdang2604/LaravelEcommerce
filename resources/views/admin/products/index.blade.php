@extends("layouts.admin")

@section("content")

<div class="row">
    <div class="col-md-12">

        @if(session('message'))
            <div class="alert alert-success">{{session('message')}}</div>
        @endif

        <div class="card-header">
            <h4>Products
                <a href="{{ url('admin/products/create') }}" class="btn btn-primary btn-sm text-white float-end">Add Products </a>
            </h4>
        </div>

        <div class="card-body">
        </div>
    </div>
</div>

@endsection
