@extends("layouts.admin")

@section("content")

<div class="row">
    <div class="col-md-12">

        @if(session('message'))
            <div class="alert alert-success">{{session('message')}}</div>
        @endif

        <div class="card-header">
            <h4>Slider List
                <a href="{{ url('admin/sliders/create') }}" class="btn btn-primary btn-sm text-white float-end">
                    Add Slider
                </a>
            </h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sliders as $slider)
                    <tr>
                        <td>{{$slider->id}}</td>
                        <td>{{$slider->title}}</td>
                        <td>{{$slider->description}}</td>
                        <td>
                            <img src="{{ asset("$slider->image") }}" style="width: 70px; height: 70px;" alt="Slider"/>
                        </td>
                        <td>{{$slider->status == '0'?'Visble':'Hidden'}}</td>
                        <td>
                            <a href="#" class="btn btn-success btn-sm">Edit</a>
                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    @empty
                    No Slider Found
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
