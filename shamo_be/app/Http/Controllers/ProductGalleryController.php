<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductGallery;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\ProductGalleryRequest;

class ProductGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        if (request()->ajax()) {
            $query = ProductGallery::where('products_id', $product->id);

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                        <form class="inline-block" action="' . route('dashboard.gallery.destroy', $item->id) . '" method="POST">
                        <button class="border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                            Hapus
                        </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>';
                })
                ->editColumn('url', function ($item) {
                    return '<img style="max-width: 150px;" src="' . $item->url . '"/>';
                })
                ->editColumn('is_featured', function ($item) {
                    return $item->is_featured ? 'Yes' : 'No';
                })
                ->rawColumns(['action', 'url'])
                ->make();
        }

        return view('pages.dashboard.gallery.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Product $product)
    {
        return view('pages.dashboard.gallery.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductGalleryRequest $request, Product $product)
    {
        $files = $request->file('files');

        if ($request->hasFile('files')) {
            foreach ($files as $file) {
                $path = $file->store('public/gallery');

                ProductGallery::create([
                    'products_id' => $product->id,
                    'url' => $path
                ]);
            }
        }

        return redirect()->route('dashboard.product.gallery.index', $product->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductGallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductGallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductGallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductGallery $gallery)
    {
        // Simpan URL gambar yang akan dihapus
        $hashedFile = $gallery->url;

        // Hapus gallery dari database
        $gallery->delete();

        // Ubah nama file yang di-hash menjadi nama aslinya
        $originalFileName = basename($hashedFile);
        // Hapus file dari penyimpanan
        Storage::delete('public/gallery/' . $originalFileName);

        return redirect()->route('dashboard.product.gallery.index', $gallery->products_id);
    }
}
