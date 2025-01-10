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
        ->select('tb_news_information.id','tb_news_information.news_type_id','tb_news_information.title_en','tb_news_information.title_km','tb_news_information.image','tb_news_information.url_video','tb_news_information.location','tb_news_information.description','tb_news_information.isUsed','tb_news_type.name_en','tb_news_type.name_km')
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

            $updateData = [
                'news_type_id' => $request->news_type_id,
                'title_en' => $request->title_en,
                'title_km' => $request->title_km,
                'url_video' => $request->url_video,
                'location' => $request->location,
                'description' => $request->description,
                'isUsed' => $request->isUsed,
            ];

            // Handle new image upload if provided
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(storage_path('images/NewsImages'), $filename);

                $updateData['image'] = $filename;

                // Delete old image if requested
                if ($request->image_delete) {
                    $oldImagePath = storage_path('images/NewsImages/' . $request->image_delete);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }
            }

            // Update the database
            DB::table('tb_news_information')
                ->where('id', $request->id)
                ->update($updateData);

        }catch(Exception $e){
            throw $e;
        }
    }

    public function delete(Request $request){
        try {
            dd($request);
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
