<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class NewsInformationController extends Controller
{
    public function getNewsInformationGrid(){
        $data = DB::table('tb_news_information')
        ->leftJoin('tb_news_type', 'tb_news_type.id', '=', 'tb_news_information.news_type_id')
        ->get();
        return response()->json($data);
    }

    public function create(Request $request){
        try{

            $dataID = DB::table('tb_news_information')->insertGetId([
                'news_type_id' => $request->news_type_id,
                'title_en' => $request->title_en,
                'title_km' => $request->title_km,
                'url_video' => $request->url_video,
                'location' => $request->location,
                'description' => $request->description,
                'isUsed' => $request->isUsed,
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(storage_path('images/NewsImages'), $filename);

                DB::table('tb_news_information')
                ->where('id', $dataID)
                ->update([
                    'image' => $filename,
                ]);
            }

        }catch(Exception $e){
            throw $e;
        }
    }

    public function update(Request $request){
        try{

            DB::table('tb_news_information')
            ->where('id', $request->id)
            ->update([
                'news_type_id' => $request->news_type_id,
                'title_en' => $request->title_en,
                'title_km' => $request->title_km,
                'url_video' => $request->url_video,
                'location' => $request->location,
                'description' => $request->description,
                'isUsed' => $request->isUsed,
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(storage_path('images/NewsImages'), $filename);

                DB::table('tb_news_information')
                ->where('id', $request->id)
                ->update([
                    'image' => $filename,
                ]);
            }

            if($request->image_delete != null) {
                $imagePath = storage_path('images/NewsImages/' . $request->image_delete);

                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

        }catch(Exception $e){
            throw $e;
        }
    }

    public function delete(Request $request){
        try {

            DB::table('tb_news_information')
                ->where('id', $request->id)
                ->delete();

            if($request->image != null) {
                $imagePath = storage_path('images/NewsImages/' . $request->image);

                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

        } catch (Exception $e) {
            throw $e;
        }
    }
}
