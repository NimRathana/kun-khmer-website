<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class NewsTypeController extends Controller
{
    public function getNewTypeGrid(){
        $data = DB::table('tb_news_type')->get();
        return response()->json($data);
    }

    public function create(Request $request){
        try{
            DB::table('tb_news_type')->insert([
                'name_en' => $request->name_en,
                'name_km' => $request->name_km,
                'order' => $request->order,
                'isUsed' => $request->isUsed,
            ]);

        }catch(Exception $e){
            throw $e;
        }
    }

    public function update(Request $request){
        try{

            DB::table('tb_news_type')
            ->where('id', $request->id)
            ->update([
                'name_en' => $request->name_en,
                'name_km' => $request->name_km,
                'order' => $request->order,
                'isUsed' => $request->isUsed,
            ]);

        }catch(Exception $e){
            throw $e;
        }
    }

    public function delete(Request $request){
        try {
            DB::table('tb_news_type')
                ->where('id', $request->id)
                ->delete();

        } catch (Exception $e) {
            throw $e;
        }
    }
}
