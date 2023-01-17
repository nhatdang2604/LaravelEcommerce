@extends('layouts.admin')

@section('title', 'My Order Details')

@section('content')

<div class="py-3 py-md-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @if(session('message'))
                    <div class="alert alert-success mb-3">
                        {{session('message')}}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3>My Orders</h3>
                    </div>

                    <div class="card-body">
                        <h4 class="text-primary">
                            <i class="fa fa-shopping-cart text-dark"></i> My Order Details
                            <a href="{{url('admin/orders')}}" class="btn btn-danger btn-sm float-end mx-1">Back</a>
                            <a href="{{url('admin/invoice/'.$order->id.'/generate')}}" class="btn btn-primary btn-sm float-end mx-1">Download Invoice</a>
                            <a href="{{url('admin/invoice/'.$order->id)}}" target="_blank" class="btn btn-warning btn-sm float-end mx-1">View Invoice</a>
                            <a href="{{url('admin/invoice/'.$order->id.'/mail')}}" class="btn btn-info btn-sm float-end mx-1">Send Invoice via Mail</a>

                        </h4>
                        <hr/>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Order Details</h5>
                                <hr>
                                <h6>Order Id: {{$order->id}}</h6>
                                <h6>Tracking Id/No.: {{$order->tracking_no}}</h6>
                                <h6>Order Created Date: {{$order->created_at}}</h6>
                                <h6>Payment Mode: {{$order->payment_mode}}</h6>
                                <h6 class="border p-2 text-success">
                                    Order Status Message: <span class="text-uppercase">{{$order->status_message}}</span>
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <h5>User Details</h5>
                                <hr>
                                <h6>Fullname: {{$order->fullname}}</h6>
                                <h6>Email: {{$order->email}}</h6>
                                <h6>Phone: {{$order->phone}}</h6>
                                <h6>Address: {{$order->address}}</h6>
                                <h6>Pincode: {{$order->pincode}}</h6>
                            </div>
                        </div>

                        <br/>
                        <h5>Order Items</h5>
                        <hr>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Item Id</th>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td width="10%">{{$item->id}}</td>
                                        <td width="10%">
                                            <img src="{{asset($item->product->productImages[0]->image)}}"
                                                        style="width: 50px; height: 50px"
                                                        alt="{{$item->product->name}}">
                                        </td>
                                        <td width="10%">
                                            {{$item->product->name}}
                                            @if ($item->productColor)
                                                <span>- Color: {{$item->productColor->Color->name}}</span>
                                            @endif
                                        </td>
                                        <td width="10%">${{$item->price}}</td>
                                        <td width="10%">{{$item->quantity}}</td>
                                        <td width="10%" class="fw-bold">${{$item->quantity * $item->price}}</td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="5" class="fw-bold">Total Amount</td>
                                        <td colspan="1" class="fw-bold">${{$totalPrice}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border mt-3">
                    <div class="card-body">
                        <h4>Order Process (Order Status Updates)</h4>
                        <hr/>
                        <div class="row">
                            <div class="col-md-5">
                                <form action="{{url('admin/orders/'.$order->id)}}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <label>Update Your Order Status</label>
                                    <div class="input-group">
                                        <select name="order_status" class="form-select">
                                            <option value="">Select Order Status</option>
                                            <option value="in progress" {{Request::get('status') == 'in progress'?'selected':''}}>In Progress</option>
                                            <option value="completed" {{Request::get('status') == 'completed'?'selected':''}}>Completed</option>
                                            <option value="pending" {{Request::get('status') == 'pending'?'selected':''}}>Pending</option>
                                            <option value="cancelled" {{Request::get('status') == 'cancelled'?'selected':''}}>Cancelled</option>
                                            <option value="out-for-delivery" {{Request::get('status') == 'out-for-delivery'?'selected':''}}>Out for delivery</option>
                                        </select>

                                        <button type="submit" class="btn btn-primary text-white">
                                            Update
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-7">
                                <br/>
                                <h4 class="mt-3">Current Order Status: <span class="text-uppercase">{{$order->status_message}}</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
