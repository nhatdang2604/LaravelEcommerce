<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TestController extends Controller
{

    public $product;
    public function index() {

        echo( date("Y-m-d H:i:s"));
        $productId = 27;

        $this->product =
            Product::
            where('id', $productId)
            //->sharedLock()
            ->first();

            echo( date("Y-m-d H:i:s"));

        dd('test');

        return $this->product;
    }

    public function index2() {
        echo(date("Y-m-d H:i:s"));

        $productId = 27;

         //Using this approach to locking table
        //  DB::transaction(function() use($productId) {

        //     //Check if the given product is exists
        //     //Get the latest product information, espeacially about the quantity
        //     $this->product =
        //         Product::with('productColors')
        //         ->where('id', $productId)
        //         //->sharedLock()
        //         ->first();


        //     sleep(10);
        // });

        //Check if the given product is exists
        //Get the latest product information, espeacially about the quantity
        $this->product =
            Product::where('id', $productId)
            //->sharedLock()
            ->first();

        echo( date("Y-m-d H:i:s"));

        sleep(10);
        echo( date("Y-m-d H:i:s"));
        dd("test2");
        return $this->product;
    }
}
