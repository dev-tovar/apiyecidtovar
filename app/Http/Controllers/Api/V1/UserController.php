<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Inicie sesión con correo electrónico y contraseña para obtener el token de autenticación",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     security={{"bearerAuth":{}}}, 
     *     tags={"Authorize"},
     *     path="/api/users",
     *     summary="Mostrar todos los usuarios.",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de pagina actual",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function index()
    {
        return UserResource::collection(User::paginate(5));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *     security={{"bearerAuth":{}}}, 
     *     tags={"Authorize"},
     *     path="/api/users",
     *     summary="Creación de usuarios.",
     *     @OA\RequestBody(
     *      required=true,
     *      description="Pass user credentials",
     *      @OA\JsonContent(
     *       required={"email","first_name"},
     *       @OA\Property(property="email", type="string", example="tyesid@hotmail.com"),
     *       @OA\Property(property="first_name", type="string", example="Yecid"),
     *       @OA\Property(property="last_name", type="string", example=""),
     *       @OA\Property(property="avatar", type="string", example=""),
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function store(Request $request)
    {



        DB::beginTransaction();
        try {


            $insertUser = new User();
            $insertUser->first_name = $request->first_name;
            $insertUser->last_name = $request->last_name ? $request->last_name : null;
            $insertUser->email = $request->email;
            $insertUser->avatar = $request->avatar ? $request->avatar : null;
            $insertUser->email_verified_at = now();
            $insertUser->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password
            $insertUser->remember_token = Str::random(10);
            $insertUser->save();

            DB::commit();

            return response()->json([
                'message' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'error',
                'text' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      security={{"bearerAuth":{}}}, 
     *     tags={"Authorize"},
     *     path="/api/users/{id}",
     *     summary="Mostrar detalle del usuario",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Identificador de usuario",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Put(
     *     security={{"bearerAuth":{}}}, 
     *     tags={"Authorize"},
     *     path="/api/users/{id}",
     *     summary="Actualizar usuarios",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Identificador de usuario",
     *         required=true,
     *      ),
     *     @OA\RequestBody(
     *      required=true,
     *      description="Pass user credentials",
     *      @OA\JsonContent(
     *       required={"email","first_name"},
     *       @OA\Property(property="email", type="string", example="tyesid@hotmail.com"),
     *       @OA\Property(property="first_name", type="string", example="Yecid Update"),
     *       @OA\Property(property="last_name", type="string", example="Tovar Update"),
     *       @OA\Property(property="avatar", type="string", example=""),
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function update(Request $request, User $user)
    {


        DB::beginTransaction();
        try {


            $updateUser = User::where('id', $user->id)->first();
            $updateUser->first_name = $request->first_name;
            if ($request->last_name) {
                $updateUser->last_name = $request->last_name;
            }
            $updateUser->email = $request->email;
            if ($request->avatar) {
                $updateUser->avatar = $request->avatar;
            }
            $updateUser->save();

            DB::commit();

            return response()->json([
                'message' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'error',
                'text' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
