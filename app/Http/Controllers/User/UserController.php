<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allUsers():JsonResponse
    {
        try{
            
            $users = User::all();
            return response()->json([
                'status_code' => 200,
                'status' => 'Success!',
                'users' => UserResource::collection($users)
            ]);
        }catch(Throwable $e){
            return response()->json([
                'message' => 'failed!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findUserById($id)
    {
        try{

            $user = User::where('id',$id)->first();

            if(empty($user)){
                return response()->json([
                    'message' => 'Not found!'
                ]);
            }else{
                return response()->json([
                    'status_code' => 200,
                    'status' => 'Success',
                    'user' => new UserResource($user)
                ]);
            }
        }catch(Throwable $e){
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
