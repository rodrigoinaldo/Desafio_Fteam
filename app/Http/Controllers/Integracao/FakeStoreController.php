<?php

namespace App\Http\Controllers\Integracao;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http as FacadesHttp;
use Laravel\Pail\ValueObjects\Origin\Http as OriginHttp;
use Illuminate\Support\Facades\Http;
use Log;

class FakeStoreController extends Controller
{
    public function allEstoque()
    {
       try {
            // Sincroniza categorias
            $categoriasResponse = retry(3, function() {
                return Http::timeout(5)->get('https://fakestoreapi.com/products/categories');
            }, 100);

            $categorias = $categoriasResponse->json();

            $catalogos = [];

            // foreach ($categorias as $nome) {
            //     $catalogo = Catalog::updateOrCreate(
            //         ['name' => $nome],
            //         ['name' => $nome]
            //     );
            //     $catalogos[$nome] = $catalogo;
            // }

            foreach ($categorias as $nome){
                DB::table('catalogs')->updateOrInsert(
                    ['name' => $nome],
                    ['name' => $nome]
                );
                $catalogos[$nome] = Catalog::where('name', $nome)->first();
            }


            // Sincroniza produtos e vincula ao catálogo
            // tendo problema de timeout, coloquei retry
            $produtosResponse = retry(3, function() {
                return Http::timeout(5)->get('https://fakestoreapi.com/products');
            }, 100);
            
            $produtos = $produtosResponse->json();

            foreach ($produtos as $produto) {
                $catalogo = $catalogos[$produto['category']] ?? null;

                if ($catalogo) {
                    $product = DB::table('products')->updateOrInsert(
                        ['external_id' => $produto['id']],
                        [
                            'title' => $produto['title'],
                            'price' => $produto['price'],
                            'description' => $produto['description'],
                            'image' => $produto['image'],
                        ]
                    );
                    $product = Product::where('external_id', $produto['id'])->first();
                    DB::table('catalog_product')->updateOrInsert(
                        ['product_id' => $product->id, 'catalog_id' => $catalogo->id],
                        ['product_id' => $product->id, 'catalog_id' => $catalogo->id]
                    );
                }
            }

            return response()->json([
                'message' => 'Catálogos e produtos sincronizados com sucesso.'
            ], 200);

        } catch (\Throwable $th) {
            Log::error('Erro na integração com Fake Store API:',[
                'message' => $th->getMessage(),
            ]);

            return response()->json([
                'message' => 'Integração não autorizada',
                'error' => $th->getMessage()    
            ], 400);

            
        }
    }

    // public function teste()
    // {
    //     $dados =  Http::get("https://fakestoreapi.com/products/categories");
    //     return $dados->json();
    // }
}
