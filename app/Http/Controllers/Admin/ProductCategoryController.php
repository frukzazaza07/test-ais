<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
                    ->orwhere('pc_name_en', 'LIKE', '%' . $search . '%')
                    ->orwhere('pc_prefix_serial_number', 'LIKE', '%' . $search . '%');
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
        try {
            return Inertia::render('Admin/ProductCategory/Create');
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
                return redirect()->route('admin.product-category.create')->withErrors(['alertMessage' => [$validator->data]]);
            }

            $model = ProductCategory::create($validator->data);

            return redirect()->route('admin.product-category.index')->withSuccess(['alertMessage' => "เพิ่มข้อมูล {$model->pc_name_th} เรียบร้อย"]);
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
        try {
            $data = ProductCategory::find($id);
            if (!$data) {
                return redirect()->route('admin.product-category.index')->withErrors(['alertMessage' => "ไม่พบข้อมูล"]);
            }
            return Inertia::render('Admin/ProductCategory/Edit', [
                "data" => new ProductCategoryResource($data),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $model = ProductCategory::find($id);
            if (!$model) {
                return redirect()->route('admin.product-category.index')->withErrors(['alertMessage' => "ไม่พบข้อมูล"]);
            }
            $validator = $this->validateRequest($request, 'update');
            if ($validator->error) {
                return redirect()->route('admin.product-category.edit', ['product_category' => $id])->withErrors(['alertMessage' => [$validator->data]]);
            }
            $model->update($validator->data);

            return redirect()->route('admin.product-category.index')->withSuccess(['alertMessage' => "อัพเดทข้อมูล {$model->pc_name_th} เรียบร้อย"]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        try {
            $model = ProductCategory::find($id);
            if (!$model) {
                return redirect()->route('admin.product-category.index')->withErrors(['alertMessage' => "ไม่พบข้อมูล"]);
            }
            $productModel = Product::where('p_pc_id', $id)->first();
            if ($productModel) {
                return redirect()->route('admin.product-category.index', $request->all())->withErrors(['alertMessage' => "ไม่สามารถลบข้อมูลได้เนื่องจากมีการใช้งานที่ Product {$productModel->p_name_th} {$productModel->p_serial_number}"]);
            }
            $model->delete();
            return redirect()->route('admin.product-category.index', $request->all())->withSuccess(['alertMessage' => "ลบข้อมูล {$model->pc_name_th} เรียบร้อย"]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function validateRequest(Request $request, $mode = 'create')
    {
        $fillModel   = ['pc_name_th', 'pc_name_en', 'pc_prefix_serial_number'];
        $rules       = ProductCategory::rules();
        if ($mode == 'update') {
            $fillModel = ['pc_name_th', 'pc_name_en'];
            $rules = ProductCategory::rules(['pc_prefix_serial_number'], [], $request->all());
        }
        $requestData = setPayload($request->all(), $fillModel, 'pc');
        $validator   = Validator::make($requestData, $rules);
        if ($validator->fails()) {
            return (object) ["data" => $validator->errors(), "error" => true];
        }
        if ($mode == 'create') {
            $requestData = array_merge($requestData, [
                'pc_created_by' => Auth::id(),
            ]);
        }
        $requestData = array_merge($requestData, [
            'pc_updated_by' => Auth::id(),
        ]);
        return (object) ["data" => $requestData, "error" => false];
    }
}
