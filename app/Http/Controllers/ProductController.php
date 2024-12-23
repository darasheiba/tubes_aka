<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    // Menampilkan semua produk
    public function index()
    {
        // Mendapatkan semua produk
        $products = Product::all();

        // Mengirimkan data produk ke view
        return view('products.index', compact('products'));
    }

    // Menampilkan form untuk membuat produk baru
    public function create()
    {
        return view('products.create');
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index');
    }

    // Menampilkan produk tertentu
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    // Menampilkan form untuk mengedit produk
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // Memperbarui produk
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('products.index');
    }

    // Menghapus produk
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index');
    }

    public function searchIterative($query)
    {
        $startTime = microtime(true);

        $products = \App\Models\Product::all();
        $results = [];

        foreach ($products as $product) {
            if (stripos($product->name, $query) !== false) {
                $results[] = $product;
            }
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        return response()->json([
            'results' => $results,
            'execution_time' => $executionTime,
        ]);
    }

    public function analyzePerformance()
    {
        $inputSizes = [1, 10, 100, 1000, 5000, 10000];
        $results = [];

        foreach ($inputSizes as $size) {
            // Query dummy untuk pengujian
            $query = 'test';

            $startTime = microtime(true);

            // Ambil sejumlah produk berdasarkan ukuran input
            $products = \App\Models\Product::take($size)->get();

            // Filter produk berdasarkan query
            $filtered = $products->filter(function ($product) use ($query) {
                return stripos($product->name, $query) !== false;
            });

            $endTime = microtime(true);

            // Simpan waktu eksekusi untuk tiap ukuran input
            $results[] = [
                'input_size' => $size,
                'execution_time' => $endTime - $startTime,
            ];
        }

        return view('performance-test', compact('results'));
    }

    public function exportToCsv(Request $request)
    {
        $results = $request->input('results'); // Ambil hasil pengujian dari input (misalnya dari session atau database)

        // Set the headers to let the browser know it's a CSV file
        $response = new StreamedResponse(function () use ($results) {
            $handle = fopen('php://output', 'w');

            // Menulis header CSV
            fputcsv($handle, ['Input Size', 'Execution Time (Seconds)']);

            // Menulis setiap hasil ke file CSV
            foreach ($results as $result) {
                fputcsv($handle, [$result['input_size'], $result['execution_time']]);
            }

            fclose($handle);
        });

        // Set the headers so the browser will download the file
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="performance-test-results.csv"');

        return $response;
    }

    private function searchRecursive($products, $query, $index = 0)
    {
        if ($index >= count($products)) {
            return [];
        }

        $currentProduct = $products[$index];
        $result = [];

        if (stripos($currentProduct['name'], $query) !== false) {
            $result[] = $currentProduct;
        }

        return array_merge($result, $this->searchRecursive($products, $query, $index + 1));
    }


    public function searchRecursiveHandler($query)
    {
        $startTime = microtime(true);

        $products = \App\Models\Product::all(); // Ambil semua data produk
        $results = $this->searchRecursive($products->toArray(), $query);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        
        return view('performance-test', compact('results'))->with(['results' => $results]);

        return response()->json([
            'results' => $results,
            'execution_time' => $executionTime,
        ]);
    }

    public function analyzePerformanceCombined()
    {
        $inputSizes = [1, 10, 100, 1000, 5000, 10000];
        $results = [];

        foreach ($inputSizes as $size) {
            $query = 'test';

            // Uji iteratif
            $iterativeStart = microtime(true);
            $products = \App\Models\Product::take($size)->get();
            $products->filter(function ($product) use ($query) {
                return stripos($product->name, $query) !== false;
            });
            $iterativeEnd = microtime(true);

            // Uji rekursif
            $recursiveStart = microtime(true);
            $this->searchRecursive($products->toArray(), $query);
            $recursiveEnd = microtime(true);

            // Simpan hasil
            $results[] = [
                'input_size' => $size,
                'iterative_time' => $iterativeEnd - $iterativeStart,
                'recursive_time' => $recursiveEnd - $recursiveStart,
            ];
        }
        return view('performance-test', compact('results'))->with(['results' => $results]);

        return view('performance-test', compact('results'));
    }
}
