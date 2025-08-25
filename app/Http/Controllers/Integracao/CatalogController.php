<?php

namespace App\Http\Controllers\integracao;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Product;
use Cache;
use DB;
use Illuminate\Http\Request;
use Log;



class CatalogController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (Cache::has('produtos_' . md5($request->fullUrl())) == true) {
                $produtos = Cache::get('produtos_' . md5($request->fullUrl())); 
                return response()->json($produtos);
            }
            
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

            $perPage = $request->query('per_page', 10); // itens por página
            $produtos = $query->paginate($perPage);

            // o md5 é para evitar que a chave fique muito grande
            Cache::set('produtos_' . md5($request->fullUrl()), $produtos, 36000); // salva por 1 hora

            return response()->json($produtos);
            
        } catch (\Throwable $th) {
            Log::error('Erro ao buscar produtos: ' . $th->getMessage());
            return response()->json([
                'message' => 'Erro ao buscar produtos',
                'error' => $th->getMessage()
            ], 500);
        }
        

    }

    public function show($id)
    {
        try {
            
            if (Cache::has('produto_' . $id) == true) {
                $produto = Cache::get('produto_' . $id); // se tiver no cache, retorna
                return response()->json($produto); // retor
            }

            $produto = DB::select("
                SELECT p.*, c.name as category_name
                FROM products p
                JOIN catalog_product cp ON p.id = cp.product_id
                JOIN catalogs c ON cp.catalog_id = c.id
                WHERE p.id = ?
            ", [$id]);


            if (!$produto) {
                return response()->json(['message' => 'Produto não encontrado'], 404);
            }

            Cache::set('produto_' . $id, $produto, 36000); // salva por 1 hora

            return response()->json($produto);
        } catch (\Throwable $th) {
            Log::error('Erro ao buscar produto: ' . $th->getMessage());
            return response()->json([
                'message' => 'Erro ao buscar produto',
                'error' => $th->getMessage()
            ], 500);
        }

    }
}
