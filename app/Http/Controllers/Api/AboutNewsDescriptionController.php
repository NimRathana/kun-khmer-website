<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AboutNewsDescriptionController extends Controller
{
    public function getAboutNewsDescriptionGrid(Request $request){

        $query = DB::table('tb_about_news_description')
        ->leftJoin('tb_about_news_type', 'tb_about_news_type.id', '=', 'tb_about_news_description.about_news_type_id')
        ->leftJoin('tb_news_information', 'tb_news_information.id', '=', 'tb_about_news_description.news_information_id');

        $tabAboutNewsId = $request->input('tab_about_news_id');
        $newsDetailId = $request->input('news_detail_id');

        // Apply filters only if the parameters are present
        if ($tabAboutNewsId) {
            $query->where('tb_about_news_description.about_news_type_id', $tabAboutNewsId);
        }

        if ($newsDetailId) {
            $query->where('tb_about_news_description.news_information_id', $newsDetailId);
        }

        $data = $query->select([
            'tb_about_news_description.id',
            'tb_about_news_description.description',
            'tb_about_news_description.about_news_type_id',
            'tb_about_news_description.news_information_id',
            'tb_about_news_type.about_news_name_en',
            'tb_about_news_type.about_news_name_km',
            'tb_news_information.title_en',
            'tb_news_information.title_km',
        ])->get();

        return response()->json($data);
    }

    public function create(Request $request){
        try{

            DB::table('tb_about_news_description')->insert([
                'about_news_type_id' => $request->about_news_type_id,
                'news_information_id' => $request->news_information_id,
                'description' => $request->description,
            ]);

        }catch(Exception $e){
            throw $e;
        }
    }

    public function update(Request $request){
        try{

            DB::table('tb_about_news_description')
            ->where('id', $request->id)
            ->update([
                'about_news_type_id' => $request->about_news_type_id,
                'news_information_id' => $request->news_information_id,
                'description' => $request->description,
            ]);

        }catch(Exception $e){
            throw $e;
        }
    }

    public function delete(Request $request){
        try {
            DB::table('tb_about_news_description')
                ->where('id', $request->id)
                ->delete();

        } catch (Exception $e) {
            throw $e;
        }
    }
}
