<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Validator;

class SupplierController extends Controller
{
    protected $rules = [
        'supplier_name' => 'required',
        'contact_person' => 'required',
        'mobile_number' => 'required',
        'products' => 'required|array',
        'products.*.name' => 'required',
        'products.*.price' => 'required|numeric',
        'products.*.image' => 'required|image|mimes:jpeg,png,jpg',
    ];

    public function index()
    {
        $suppliers = $this->getPaginatedSuppliers(10);

        return response()->json([
            'success' => true,
            'suppliers' => $suppliers,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $request->all();

        foreach ($validatedData['products'] as $key => $product) {
            if ($request->hasFile("products.$key.image")) {
                $image = $request->file("products.$key.image");

                $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

                $imagePath = $image->storeAs('products', $imageName, 'public');
                $validatedData['products'][$key]['image'] = $imagePath;
            }
        }

        $supplier = $this->createRepo($validatedData);

        return response()->json([
            'success' => true,
            'supplier' => $supplier,
        ], 201);
    }

    public function get($id)
    {
        $supplier = $this->findById($id);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'supplier' => $supplier,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $request->all();

        foreach ($validatedData['products'] as $key => $product) {
            if ($request->hasFile("products.$key.image")) {
                $image = $request->file("products.$key.image");

                $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

                $imagePath = $image->storeAs('products', $imageName, 'public');
                $validatedData['products'][$key]['image'] = $imagePath;
            }
        }


        $supplier = $this->updateRepo($id, $validatedData);

        return response()->json([
            'success' => true,
            'supplier' => $supplier,
        ], 201);
    }

    public function destroy($id)
    {
        $supplier = $this->findById($id);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found',
            ], 404);
        }

        $this->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully',
        ], 200);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->get('search');

        if (!$searchTerm) {
            $this->index();
        }

        $suppliers = $this->searchRepo($searchTerm);

        return response()->json([
            'success' => true,
            'suppliers' => $suppliers,
        ], 200);
    }


    // Repository methods
    public function createRepo(array $data)
    {
        $supplier = Supplier::create([
            'supplier_name' => $data['supplier_name'],
            'contact_person' => $data['contact_person'],
            'mobile_number' => $data['mobile_number'],
        ]);

        foreach ($data['products'] as $productData) {
            $supplier->products()->create([
                'product_name' => $productData['name'],
                'product_price' => $productData['price'],
                'product_image' => $productData['image'],
            ]);
        }

        return $supplier;
    }

    public function updateRepo($id, array $data)
    {
        $supplier = Supplier::findOrFail($id);

        if ($supplier) {

            $supplier->update([
                'supplier_name' => $data['supplier_name'],
                'contact_person' => $data['contact_person'],
                'mobile_number' => $data['mobile_number'],
            ]);

            $supplier->products()->delete();

            foreach ($data['products'] as $productData) {
                $supplier->products()->create([
                    'product_name' => $productData['name'],
                    'product_price' => $productData['price'],
                    'product_image' => $productData['image'],
                ]);
            }
        }

        return $supplier;
    }

    public function delete($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->products()->delete();
        $supplier->delete();
        return true;
    }

    public function findById($id)
    {
        return Supplier::with('products')->findOrFail($id);
    }

    public function getPaginatedSuppliers($perPage = 10)
    {
        return Supplier::withCount('products')->paginate($perPage);
    }

    public function searchRepo($query, $perPage = 10)
    {
        return Supplier::where('supplier_name', 'LIKE', "%{$query}%")
            ->orWhere('mobile_number', 'LIKE', "%{$query}%")
            ->withCount('products')
            ->paginate($perPage);
    }
}