<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Services\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    /**
*  @OA\GET(
*      path="/api/periods",
*      summary="Get all periods",
*      description="Get all periods",
*      tags={"Periods"},
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
        $periods = $user->periods()->get();

        return ApiResponse::success($periods);
    }

    /**
*  @OA\POST(
*      path="/api/periods",
*      summary="Create a period",
*      description="Create a period",
*      tags={"Periods"},
*      security={{"bearerAuth":{}}},
*      @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*           type="object",
*           required={"title"},
*           @OA\Property(property="title", type="string"),
*           @OA\Property(property="active", type="string"),
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
            'title' => 'required'
        ]);

        try{

            $data = $request->all();
            $data['user_id'] = auth()->id();

            $period = Period::create($data);

            return ApiResponse::success($period);

        }catch(ModelNotFoundException $e){

            return ApiResponse::error("Não foi possível incluir o período");

        }

        
    }

   /**
*  @OA\GET(
*      path="/api/periods/{id}",
*      summary="Get period by id",
*      description="Get period by id",
*      tags={"Periods"},
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
*          description="User not found",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
public function show(string $id)
{

    $user_id = auth()->id();
    
    $period = Period::where('user_id', $user_id)->find($id);

    if($period){
        return ApiResponse::success($period);
    }

    return ApiResponse::error('Período não encontrado');
}

    /**
*  @OA\PUT(
*      path="/api/periods/{period}",
*      summary="Update a period",
*      description="Update a period",
*      tags={"Periods"},
*      security={{"bearerAuth":{}}},
*      @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*           type="object",
*           required={"title", "id"},
*           @OA\Property(property="title", type="string"),
*           @OA\Property(property="active", type="string"),
*           @OA\Property(property="id", type="string"),

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
*          description="User not found",
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
            'title' => 'required',
            'id' => 'required'
        ]);

        try{

            $user_id = auth()->id();
    
            $period = Period::where('user_id', $user_id)->findOrFail($request->id);

            $period->update($request->all());

            return ApiResponse::success($period);


        }catch(ModelNotFoundException $e){

            return ApiResponse::error('Período não encontrado');

        }
    }

   
}
