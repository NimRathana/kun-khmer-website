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
                $images = $request->file('image');
                $uploadedImages = [];

                foreach ($images as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = uniqid(time() . '_', true) . '.' . $extension;
                    $image->move(storage_path('images/NewsImages'), $filename);
                    $uploadedImages[] = $filename;
                }

                // Optionally, store image names in the database
                DB::table('tb_news_information')
                    ->where('id', $dataID)
                    ->update([
                        'image' => json_encode($uploadedImages),
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

            if ($request->hasFile('image')) {
                $uploadedImages = [];

                // Process the new uploaded images
                foreach ($request->file('image') as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $filename = uniqid(time() . '_', true) . '.' . $extension;
                    $image->move(storage_path('images/NewsImages'), $filename);
                    $uploadedImages[] = $filename;
                }

                if (count($uploadedImages) > 0) {
                    $updateData['image'] = json_encode($uploadedImages);
                }

                // Check if images need to be deleted
                if (is_array($request->image_delete) && count($request->image_delete) > 0) {
                    foreach ($request->image_delete as $imageToDelete) {
                        $filePath = storage_path('images/NewsImages/' . $imageToDelete);

                        // Check if the file exists before attempting to delete
                        if (File::exists($filePath)) {
                            // Delete the image file
                            File::delete($filePath);
                        }
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
