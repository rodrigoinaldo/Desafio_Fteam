<?php

namespace App\Http\Controllers\integracao;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('catalogs'); // Evita N+1

        if ($request->filled('categoria')) {
            $query->whereHas('catalogs', function ($q) use ($request) {
                $q->where('name', $request->categoria);
            });
        }

        if ($request->filled('preco_min')) {
            $query->where('price', '>=', $request->preco_min);
        }

        if ($request->filled('preco_max')) {
            $query->where('price', '<=', $request->preco_max);
        }

        if ($request->filled('busca')) {
            $query->where('title', 'like', '%' . $request->busca . '%');
        }

        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        }

        $perPage = $request->query('per_page', 10);
        $produtos = $query->paginate($perPage);

        return response()->json($produtos);

    }

    public function show($id)
    {
        $produto = Product::with('catalog')->find($id);

        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        return response()->json($produto);
    }
}
