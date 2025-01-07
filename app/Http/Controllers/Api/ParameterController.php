<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ParameterController extends Controller
{
    public function getParameter(){
        $data = DB::table('tb_parameter')->get();
        return response()->json($data);
    }

    public function create(Request $request){
        try{

            DB::table('tb_parameter')->insert([
                'parameter_code' => $request->parameter_code,
                'value' => $request->value,
                'text_km' => $request->text_km,
                'text_en' => $request->text_en,
            ]);

        }catch(Exception $e){
            throw $e;
        }
    }

    public function update(Request $request){
        try{

            DB::table('tb_parameter')
            ->where('id', $request->id)
            ->update([
                'parameter_code' => $request->parameter_code,
                'value' => $request->value,
                'text_km' => $request->text_km,
                'text_en' => $request->text_en,
            ]);

        }catch(Exception $e){
            throw $e;
        }
    }

    public function delete(Request $request){
        try {
            DB::table('tb_parameter')
                ->where('id', $request->id)
                ->delete();

        } catch (Exception $e) {
            throw $e;
        }
    }
}
