<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SelectResource;
use App\Imports\ProductImport;
use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use OpenApi\Annotations as OA;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/admin/product",
     *   summary="Get a list of Products",
     *   security={{"bearerAuth":{}}},  
     *   tags={"Product"},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="nameTh", type="string", example="ตู้เย็น LG"),
     *         @OA\Property(property="nameEn", type="string", example="LG Fridge"),
     *         @OA\Property(property="serialNumber", type="string", example="INVFR0000001"),
     *         @OA\Property(property="price", type="double", example="10000"),
     *         @OA\Property(property="category", type="object",
     *              @OA\Property(
     *              property="id",
     *              type="integer",
     *              example=1
     *              ),
     *              @OA\Property(
     *                  property="nameTh",
     *                  type="string",
     *                  example="ตู้เย็น"
     *              ),
     *              @OA\Property(
     *                  property="nameEn",
     *                  type="string",
     *                  example="ตู้เย็น"
     *              ),
     *              @OA\Property(
     *                  property="prefixSerialNumber",
     *                  type="string",
     *                  example="FR"
     *              )
     *          )
     *       )
     *     )
     *   ),
     *   @OA\Response(response=404, description="Products not found")
     * )
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
                ->orderBy('p_serial_number', 'ASC')
                ->paginate(config('app.paginate_perpage'))
                ->withQueryString();

            $wantsJson = apiResponse($request, ProductResource::collection($results), $results);
            if ($wantsJson) {
                return $wantsJson;
            }

            return Inertia::render('Admin/Product/Index', [
                'data' => ProductResource::collection($results),
                'requestData' => $request->all(),
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['alertMessage' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            return Inertia::render('Admin/Product/Create', [
                'select' => $this->getSelect(),
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['alertMessage' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *   path="/api/admin/product",
     *   summary="Create a new product",
     *   security={{"bearerAuth":{}}},  
     *   tags={"Product"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="nameTh", type="string", example="ตู้เย็น LG"),
     *       @OA\Property(property="nameEn", type="string", example="LG Fridge"),
     *       @OA\Property(property="serialNumber", type="string", example="INVFR0000001"),
     *       @OA\Property(property="price", type="number", format="double", example=10000),
     *       @OA\Property(property="pcId", type="integer", example=1, description="ID from product category")
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Product created successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="id", type="integer", example=1),
     *       @OA\Property(property="nameTh", type="string", example="ตู้เย็น LG"),
     *       @OA\Property(property="nameEn", type="string", example="LG Fridge"),
     *       @OA\Property(property="serialNumber", type="string", example="INVFR0000001"),
     *       @OA\Property(property="price", type="number", format="double", example=10000),
     *       @OA\Property(
     *         property="category",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="nameTh", type="string", example="ตู้เย็น"),
     *         @OA\Property(property="nameEn", type="string", example="Fridge"),
     *         @OA\Property(property="prefixSerialNumber", type="string", example="FR")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=400, description="Validation input error"),
     * )
     */
    public function store(Request $request)
    {
        try {
            $validator = $this->validateRequest($request);
            if ($validator->error) {
                $wantsJson = apiResponse($request, [$validator->data], null, 400);
                if ($wantsJson) {
                    return $wantsJson;
                }

                return redirect()->route('admin.product.create')->withErrors(['alertMessage' => [$validator->data]]);
            }

            $model = Product::create($validator->data);
            $wantsJson = apiResponse($request, new ProductResource($model), $model, 201);
            if ($wantsJson) {
                return $wantsJson;
            }

            return redirect()->route('admin.product.index')->withSuccess(['alertMessage' => "เพิ่มข้อมูล {$model->p_name_th} {$model->p_serial_number}เรียบร้อย"]);
        } catch (Exception $e) {
            $wantsJson =  apiResponse($request, $e->getMessage(), null, 500);
            if ($wantsJson) {
                return $wantsJson;
            }
            return redirect()->back()->withErrors(['alertMessage' => $e->getMessage()]);
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
     * @OA\Get(
     *   path="/api/admin/product/{id}/edit",
     *   summary="Retrieve a product for editing",
     *   security={{"bearerAuth":{}}},  
     *   tags={"Product"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Product ID",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Product retrieved successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="nameTh", type="string", example="ตู้เย็น LG"),
     *         @OA\Property(property="nameEn", type="string", example="LG Fridge"),
     *         @OA\Property(property="serialNumber", type="string", example="INVFR0000001"),
     *         @OA\Property(property="price", type="number", format="double", example=10000),
     *         @OA\Property(
     *              property="category",
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="nameTh", type="string", example="ตู้เย็น"),
     *              @OA\Property(property="nameEn", type="string", example="Fridge"),
     *              @OA\Property(property="prefixSerialNumber", type="string", example="FR")
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(response=404, description="Product not found"),
     *   @OA\Response(response=400, description="Validation input error")
     * )
     */
    public function edit(string $id, Request $request)
    {
        try {
            $model = Product::find($id);
            if (!$model) {
                $wantsJson =  apiResponse($request, [], null, 404);
                if ($wantsJson) {
                    return $wantsJson;
                }
                return redirect()->route('admin.product.index')->withErrors(['alertMessage' => "ไม่พบข้อมูล"]);
            }
            $wantsJson = apiResponse($request, new ProductResource($model), null, 200);
            if ($wantsJson) {
                return $wantsJson;
            }
            return Inertia::render('Admin/Product/Edit', [
                "data" => new ProductResource($model),
                "select" => $this->getSelect(),
            ]);
        } catch (Exception $e) {
            $wantsJson =  apiResponse($request, $e->getMessage(), null, 500);
            if ($wantsJson) {
                return $wantsJson;
            }
            return redirect()->back()->withErrors(['alertMessage' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *   path="/api/admin/product/{id}",
     *   summary="Update an existing product",
     *   security={{"bearerAuth":{}}},  
     *   tags={"Product"},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="Product ID",
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="nameTh", type="string", example="ตู้เย็น LG Updated"),
     *       @OA\Property(property="nameEn", type="string", example="LG Fridge Updated"),
     *       @OA\Property(property="price", type="number", format="double", example=12000),
     *       @OA\Property(property="_method", type="string", example="PUT", description="Default value for update")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Product updated successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="id", type="integer", example=1),
     *       @OA\Property(property="nameTh", type="string", example="ตู้เย็น LG Updated"),
     *       @OA\Property(property="nameEn", type="string", example="LG Fridge Updated"),
     *       @OA\Property(property="serialNumber", type="string", example="INVFR0000001"),
     *       @OA\Property(property="price", type="number", format="double", example=12000),
     *       @OA\Property(
     *         property="category",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="nameTh", type="string", example="ตู้เย็น"),
     *         @OA\Property(property="nameEn", type="string", example="Fridge"),
     *         @OA\Property(property="prefixSerialNumber", type="string", example="FR")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=404, description="Product not found"),
     *   @OA\Response(response=400, description="Validation input error")
     * )
     */
    public function update(Request $request, string $id)
    {
        try {
            $model = Product::find($id);
            if (!$model) {
                $wantsJson =  apiResponse($request, [], null, 404);
                if ($wantsJson) {
                    return $wantsJson;
                }
                return redirect()->route('admin.product.index')->withErrors(['alertMessage' => "ไม่พบข้อมูล"]);
            }
            $validator = $this->validateRequest($request, 'update');
            if ($validator->error) {
                $wantsJson =  apiResponse($request, [$validator->data], null, 400);
                if ($wantsJson) {
                    return $wantsJson;
                }
                return redirect()->route('admin.product.edit', ['product' => $id])->withErrors(['alertMessage' => [$validator->data]]);
            }
            $model->update($validator->data);

            $wantsJson = apiResponse($request, new ProductResource($model), $model, 202);
            if ($wantsJson) {
                return $wantsJson;
            }

            return redirect()->route('admin.product.index')->withSuccess(['alertMessage' => "อัพเดทข้อมูล {$model->p_name_th} {$model->p_serial_number} เรียบร้อย"]);
        } catch (Exception $e) {
            $wantsJson =  apiResponse($request, $e->getMessage(), null, 500);
            if ($wantsJson) {
                return $wantsJson;
            }
            return redirect()->back()->withErrors(['alertMessage' => $e->getMessage()]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/product/{id}",
     *     summary="Delete a product",
     *     description="Deletes a product by ID",
     *     operationId="deleteProduct",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},  
     *     
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Product deleted successfully")
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Product not found")
     *         )
     *     ),
     *     
     * )
     */
    public function destroy(string $id, Request $request)
    {
        try {
            $model = Product::find($id);
            if (!$model) {
                $wantsJson = apiResponse($request, [], null, 404);
                if ($wantsJson) {
                    return $wantsJson;
                }
                return redirect()->route('admin.product.index')->withErrors(['alertMessage' => "ไม่พบข้อมูล"]);
            }
            $model->delete();
            $wantsJson = apiResponse($request, new ProductResource($model), $model, 204);
            if ($wantsJson) {
                return $wantsJson;
            }

            return redirect()->route('admin.product.index', $request->all())->withSuccess(['alertMessage' => "ลบข้อมูล {$model->p_name_th} {$model->p_serial_number} เรียบร้อย"]);
        } catch (Exception $e) {
            $wantsJson =  apiResponse($request, $e->getMessage(), null, 500);
            if ($wantsJson) {
                return $wantsJson;
            }
            return redirect()->back()->withErrors(['alertMessage' => $e->getMessage()]);
        }
    }

    public function import(Request $request)
    {
        try {
            $rules = Product::rules([], ['file']);
            $validator   = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = [$validator->errors()];
                $wantsJson =  apiResponse($request, $errors, null, 400);
                if ($wantsJson) {
                    return $wantsJson;
                }
                return redirect()->route('admin.product.index', $request->all())->withErrors(['alertMessage' => $errors]);
            }

            Excel::import(new ProductImport, $request->file('file'));
            $wantsJson = apiResponse($request, null, null);
            if ($wantsJson) {
                return  $wantsJson;
            }
            return redirect()->route('admin.product.index', $request->all())->withSuccess(['alertMessage' => 'Import ข้อมูลสำเร็จ']);
        } catch (\Exception $e) {
            $wantsJson =  apiResponse($request, $e->getMessage(), null, 500);
            if ($wantsJson) {
                return $wantsJson;
            }
            return redirect()->back()->withErrors(['alertMessage' => $e->getMessage()]);
        }
    }

    public function export(Request $request)
    {
        try {
            $format = $request->input('format', 'csv');
            $fileName = 'products_' . date('Ymd_His');

            $export = new ProductExport();

            if (Product::count() === 0) {
                return redirect()->back()->withErrors(['alertMessage' => 'No data available to export.']);
            }

            return Excel::download($export, $fileName . '.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['alertMessage' => $e->getMessage()]);
        }
    }

    public function generateQrcode(string $id, Request $request)
    {
        try {
            $model = Product::find($id);
            if (!$model) {
                $wantsJson = apiResponse($request, [], null, 404);
                if ($wantsJson) {
                    return $wantsJson;
                }
                return redirect()->route('admin.product.index')->withErrors(['alertMessage' => "ไม่พบข้อมูล"]);
            }
            $storeFile = "temp/product/qrcode/" . $model->id . '-' . $model->p_serial_number . '.png';
            $fullUrl = route('admin.product.edit', ['product' => $model->id]);
            $qrcode = generateQrcode($fullUrl);
            Storage::disk('local')->put($storeFile, (string) $qrcode);
            $binaryQrcode = Storage::disk('local')->get($storeFile);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($binaryQrcode);
            $image->text("Serial Number " . $model->p_serial_number, 15, 15, function ($font) {
                $font->file(public_path('/fonts/Sarabun-Regular.ttf'));
                $font->color('#000000');
                $font->size(14);
            });
            Storage::disk('local')->delete($storeFile);
            $base64 = base64_encode($image->toPng());
            $base64WithMime = 'data:image/png;base64,' . $base64;

            $wantsJson = apiResponse($request, ['base64' => $base64WithMime], null);
            if ($wantsJson) {
                return $wantsJson;
            }
            return redirect()->route('admin.product.index', $request->all())->withSuccess(['qrcodeBase64' => $base64WithMime]);
        } catch (Exception $e) {
            $wantsJson =  apiResponse($request, $e->getMessage(), null, 500);
            if ($wantsJson) {
                return $wantsJson;
            }
            return redirect()->back()->withErrors(['alertMessage' => $e->getMessage()]);
        }
    }

    private function validateRequest(Request $request, $mode = 'create')
    {
        $fillModel   = ['p_name_th', 'p_name_en', 'p_price'];
        $rules       = Product::rules(['p_pc_id', 'file']);
        $payload = $request->all();
        if ($mode == 'create') {
            $fillModel = array_merge($fillModel, ['p_pc_id', 'p_created_by', 'p_serial_number']);
            $rules       = Product::rules(['file']);
            $productCategoryModel = ProductCategory::find($request->pcId);
            if (!$productCategoryModel) {
                return (object) ["data" => ['pcId' => 'pcId not found'], "error" => true];
            }

            $payload = array_merge($payload, [
                'p_created_by' => Auth::id(),
                'p_serial_number' => generateSerialNumber($payload['p_pc_id'])
            ]);
        }
        $requestData = setPayload($request->all(), $fillModel, 'p');
        $validator   = Validator::make($requestData, $rules);
        if ($validator->fails()) {
            return (object) ["data" => $validator->errors(), "error" => true];
        }
        $requestData = array_merge($requestData, [
            'p_updated_by' => Auth::id(),
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
