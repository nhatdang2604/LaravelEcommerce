@extends("layouts.admin")

@section("content")

<div class="row">
    <div class="col-md-12">

        @if(session('message'))
            <div class="alert alert-success">{{session('message')}}</div>
        @endif

        <div class="card-header">
            <h4>Edit Color
                <a href="{{ url('admin/colors') }}" class="btn btn-danger btn-sm text-white float-end">
                    Back
                </a>
            </h4>
        </div>

        <div class="card-body">

            @if ($errors->any())
            <div class="alert alert-warning">
                @foreach ($errors->all() as $error)
                    <div>{{$error}}</div>
                @endforeach
            </div>
            @endif

            <form action="{{ url('admin/colors/'.$color->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Color Name</label>
                    <input type="text" name="name" value="{{ $color->name }}" class="form-control"/>
                </div>

                <div class="mb-3">
                    <label>Color Code</label>
                    <input type="text" name="code" value="{{ $color->code }}" class="form-control"/>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <input type="checkbox" name="status" {{ $color->status?'checked':'' }} style="width:50px;height:30px"/> Checked = Hidden, Unchecked = Visible
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
