<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AboutNewsTypeController extends Controller
{
    public function getAboutNewsType(){
        $data = DB::table('tb_about_news_type')
        ->leftJoin('tb_news_type', 'tb_news_type.id', '=', 'tb_about_news_type.news_type_id')
        ->select('tb_news_type.name_en','tb_news_type.name_km','tb_about_news_type.id','tb_about_news_type.about_news_name_en','tb_about_news_type.about_news_name_km','tb_about_news_type.news_type_id','tb_about_news_type.order','tb_about_news_type.isUsed')
        ->get();
        return response()->json($data);
    }

    public function getAboutNewsTypeID(Request $request){
        $validated = $request->validate([
            'news_type_id' => 'required|integer', // Ensure the input is provided and is an integer
        ]);
        $data = DB::table('tb_about_news_type')
        ->where('tb_about_news_type.news_type_id', $validated['news_type_id'])
        ->get();
        return response()->json($data);
    }

    public function create(Request $request){
        try{

            DB::table('tb_about_news_type')->insert([
                'about_news_name_en' => $request->about_news_name_en,
                'about_news_name_km' => $request->about_news_name_km,
                'news_type_id' => $request->news_type_id,
                'order' => $request->order,
                'isUsed' => $request->isUsed,
            ]);

        }catch(Exception $e){
            throw $e;
        }
    }

    public function update(Request $request){
        try{

            DB::table('tb_about_news_type')
            ->where('id', $request->id)
            ->update([
                'about_news_name_en' => $request->about_news_name_en,
                'about_news_name_km' => $request->about_news_name_km,
                'news_type_id' => $request->news_type_id,
                'order' => $request->order,
                'isUsed' => $request->isUsed,
            ]);

        }catch(Exception $e){
            throw $e;
        }
    }

    public function delete(Request $request){
        try {
            DB::table('tb_about_news_type')
            ->where('id', $request->id)
            ->delete();

        } catch (Exception $e) {
            throw $e;
        }
    }
}
