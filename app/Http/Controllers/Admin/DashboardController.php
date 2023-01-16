<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index() {

        //Get some total number
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalBrands = Brand::count();
        $totalAllUsers = User::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('role_as', '0')->count(); //user role count
        $totalAdmins = User::where('role_as', '1')->count();    //admin role count

        //Get order by today, this month and this year
        $now = Carbon::now();
        $today = $now->format('d-m-Y');
        $thisMonth = $now->format('m');
        $thisYear = $now->format('Y');
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $thisMonthOrders = Order::whereMonth('created_at', $thisMonth)->count();
        $thisYearOrders = Order::whereYear('created_at', $thisYear)->count();


        return view('admin.dashboard', compact(
            'totalProducts', 'totalCategories', 'totalBrands', 'totalAllUsers', 'totalOrders',
            'totalUsers', 'totalAdmins', 'todayOrders', 'thisMonthOrders', 'thisYearOrders',
        ));
    }


}
