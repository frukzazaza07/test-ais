<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $results = ProductCategory::when($request->search, function ($query, $search) {
                $query->where('pc_name_th', 'LIKE', '%' . $search . '%')
                    ->orwhere('pc_name_en', 'LIKE', '%' . $search . '%');
            })
                ->orderBy('pc_name_en', 'ASC')
                ->paginate(config('app.paginate_perpage'))
                ->withQueryString();
            return Inertia::render('Admin/ProductCategory/Index', [
                'data' => ProductCategoryResource::collection($results),
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
