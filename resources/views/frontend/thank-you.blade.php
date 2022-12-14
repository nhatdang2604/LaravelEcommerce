@extends('layouts.app')

@section('title', 'Thank You for Shopping')

@section('content')

    <div class="py-3 pyt-md-4">

        <div class="row">
            <div class="col-md-12 text-center">

                @if (Session::has('message'))
                    <div class="alert alert-success">
                        {{session('message')}}
                    </div>
                @endif

                <div class="py-4 shadow bg-">
                    <h4>Thank You for Shopping with Funda Ecommerce</h4>
                    <a href="{{url('collections/')}}" class="btn btn-primary">Shop now</a>
                </div>
            </div>
        </div>
    </div>

@endsection
