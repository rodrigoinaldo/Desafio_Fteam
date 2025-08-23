<?php

namespace App\Http\Controllers\Integracao;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http as FacadesHttp;
use Laravel\Pail\ValueObjects\Origin\Http as OriginHttp;
use Illuminate\Support\Facades\Http;

class FakeStoreController extends Controller
{
    public function sync()
    {
        //dd("iu");
        try {
            return Http::get("https://reqres.in/api/users?page=2");    


            // $categoriasResponse = Http::get('https://fakestoreapi.com/products/categories');
            // $categorias = $categoriasResponse->json();

            // foreach ($categorias as $nome) {
            //     Catalog::updateOrCreate(
            //         ['nome' => $nome],
            //         ['nome' => $nome]
            //     );
            // }


            
            //             // 2. Sincroniza produtos e vincula ao catálogo
            // $produtosResponse = Http::timeout(5)->get('https://fakestoreapi.com/products');
            // $produtos = $produtosResponse->json();

            // foreach ($produtos as $produto) {
            //     $catalogo = $catalogos[$produto['category']] ?? null;

            //     if ($catalogo) {
            //         Product::updateOrCreate(
            //             ['external_id' => $produto['id']],
            //             [
            //                 'title' => $produto['title'],
            //                 'price' => $produto['price'],
            //                 'description' => $produto['description'],
            //                 'image' => $produto['image'],
            //                 'catalog_id' => $catalogo->id,
            //             ]
            //         );
            //     }
            // }

            // return response()->json([
            //     'message' => 'Catálogos e produtos sincronizados com sucesso.'
            // ], 200);


        } catch (\Throwable $th) {
            response ()->json([
                'message' => 'Integração não autorizada'
            ], 400);
        }
        


    }
}
