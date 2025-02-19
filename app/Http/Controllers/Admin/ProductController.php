<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $results = Product::with('productCategory')->when($request->search, function ($query, $search) {
                $query->where('p_serial_number', 'LIKE', '%' . $search . '%');
                // $query->where('p_name_th', 'LIKE', '%' . $search . '%')
                //     ->orWhere('p_name_en', 'LIKE', '%' . $search . '%')
                //     ->orWhere('p_serial_number', 'LIKE', '%' . $search . '%')
                //     ->orWhereHas('productCategory', function ($query) use ($search) {
                //         $query->where('pc_name_th', 'LIKE', '%' . $search . '%')
                //             ->orWhere('pc_name_en', 'LIKE', '%' . $search . '%');
                //     });
            })
                // ->orderBy('product_category.pc_name_en', 'ASC')
                ->orderBy(ProductCategory::select('pc_name_en')->whereColumn('product_category.id', 'product.p_pc_id'))
                ->orderBy('p_name_en', 'ASC')
                ->paginate(config('app.paginate_perpage'))
                ->withQueryString();
            return Inertia::render('Admin/Product/Index', [
                'data' => ProductResource::collection($results),
                'requestData' => $request->all(),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
