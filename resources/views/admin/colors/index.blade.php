@extends("layouts.admin")

@section("content")

<div class="row">
    <div class="col-md-12">

        @if(session('message'))
            <div class="alert alert-success">{{session('message')}}</div>
        @endif

        <div class="card-header">
            <h4>Colors
                <a href="{{ url('admin/colors/create') }}" class="btn btn-primary btn-sm text-white float-end">
                    Add Color
                </a>
            </h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Color Name</th>
                        <th>Color Code</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($colors as $color)
                    <tr>
                        <td>{{$color->id}}</td>
                        <td>{{$color->name}}</td>
                        <td>{{$color->code}}</td>
                        <td>{{$color->status? 'Hidden':'Visible'}}</td>
                        <td>
                            <a href="{{ url('admin/colors/'.$color->id.'/edit') }}" class="btn btn-primary btn-sm">Edit</a>
                            <a href="" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
