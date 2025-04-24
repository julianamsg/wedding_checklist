<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Period;
use App\Services\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
*  @OA\GET(
*      path="/api/items",
*      summary="Get all items",
*      description="Get all items",
*      tags={"Items"},
*      security={{"bearerAuth":{}}},
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function index()
    {
        $user = auth()->user();
        $items = Item::whereHas('period', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return ApiResponse::success($items);
    }

    /**
*  @OA\POST(
*      path="/api/items",
*      summary="Create a item",
*      description="Create a item",
*      tags={"Items"},
*      security={{"bearerAuth":{}}},
*      @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*           type="object",
*           required={"name", "period_id"},
*           @OA\Property(property="name", type="string"),
*           @OA\Property(property="active", type="string"),
*           @OA\Property(property="period_id", type="string"),
*           @OA\Property(property="done", type="string"),
*         )
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'period_id' => 'required'
        ]);

        try{

            $user_id = auth()->id();
            $period_id = Period::where('user_id', $user_id)->find($request->period_id);

            if(!$period_id){
                return ApiResponse::error("Não existe o period_id informado. Consulte o endpoint GET /api/periods");
            }

            $item = Item::create($request->all());

            return ApiResponse::success($item);

        }catch(ModelNotFoundException $e){

            return ApiResponse::error("Não foi possível incluir o item");

        }

        
    }

   /**
*  @OA\GET(
*      path="/api/items/{id}",
*      summary="Get item by id",
*      description="Get item by id",
*      tags={"Items"},
*      security={{"bearerAuth":{}}},
*      @OA\Parameter(
*         name="id",
*         in="path",
*         description="id",
*         required=true,
*         @OA\Schema(
*               type="integer",
*               format="int64"
*         )
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*      @OA\Response(
*          response=404,
*          description="Item not found",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function show(string $id)
    {
        $user = auth()->user();
    
        $item = Item::where('id', $id)
        ->whereHas('period', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->first();

        if($item){
            return ApiResponse::success($item);
        }

        return ApiResponse::error('Item não encontrado');
    }

    /**
*  @OA\PUT(
*      path="/api/items/{id}",
*      summary="Update a item",
*      description="Update a item",
*      tags={"Items"},
*      security={{"bearerAuth":{}}},
*      @OA\Parameter(
*          name="id",
*          in="path",
*          description="ID do item",
*          required=true,
*          @OA\Schema(
*              type="integer"
*          )
*      ),
*      @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*           type="object",
*           required={"name"},
*           @OA\Property(property="name", type="string"),
*           @OA\Property(property="active", type="string"),
*           @OA\Property(property="period_id", type="string"),
*           @OA\Property(property="done", type="string")
*         )
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*      @OA\Response(
*          response=404,
*          description="Item not found",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required'
        ]);

       

            $user = auth()->user();
    
            $item = Item::where('id', $id)
            ->whereHas('period', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

            if($item){

                $period = Period::where('user_id', $user->id)->find($request->period_id);

                if($period){

                    $item->update($request->all());

                    return ApiResponse::success($item);

                }else{
                    return ApiResponse::error('period_id não encontrado');
                }

            }else{
                return ApiResponse::error('Item não encontrado');
            }
    }

   
}
