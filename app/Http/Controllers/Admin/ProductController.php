<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SelectResource;
use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        try {
            return Inertia::render('Admin/Product/Create', [
                'select' => $this->getSelect(),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = $this->validateRequest($request);
            if ($validator->error) {
                dd($validator->data);
                return redirect()->route('admin.product.create')->withErrors(['dialog' => [$validator->data]]);
            }

            Product::create($validator->data);

            return redirect()->route('admin.product.index')->withSuccess(['dialog' => "เพิ่มข้อมูลข้อมูลเรียบร้อย"]);
        } catch (Exception $e) {
            throw $e;
        }
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

    private function validateRequest(Request $request, $mode = 'create')
    {
        $fillModel   = ['p_pc_id', 'p_name_th', 'p_name_en', 'p_price'];
        $rules       = Product::rules();
        $requestData = setPayload($request->all(), $fillModel, 'p');
        $validator   = Validator::make($requestData, $rules);
        if ($validator->fails()) {
            return (object) ["data" => $validator->errors(), "error" => true];
        }
        if ($mode == 'create') {
            $requestData = array_merge($requestData, [
                'p_created_by' => Auth::id(),
                'p_serial_number' => generateSerialNumber($requestData['p_pc_id'])
            ]);
        }
        $requestData = array_merge($requestData, [
            'p_updated_id' => Auth::id(),
        ]);
        return (object) ["data" => $requestData, "error" => false];
    }

    private function getSelect()
    {
        return [
            'productCategory' => SelectResource::collectionSelect(ProductCategory::get(), 'pc_name_th'),
        ];
    }
}
