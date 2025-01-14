<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class SponsorController extends Controller
{
    public function getSponsorGrid(){
        $data = DB::table('tb_sponsor')->get();
        return response()->json($data);
    }

    public function create(Request $request){
        try{

            $dataID = DB::table('tb_sponsor')->insertGetId([
                'sponsor_name' => $request->sponsor_name,
                'contact' => $request->contact,
                'image' => $request->image??NULL,
                'isUsed' => $request->isUsed,
            ]);

            if ($request->hasFile('image')) {
                $images = $request->file('image');
                $extension = $images->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $images->move(storage_path('images/SponsorImages'), $filename);

                DB::table('tb_sponsor')
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
                'sponsor_name' => $request->sponsor_name,
                'contact' => $request->contact,
                'isUsed' => $request->isUsed,
            ];

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = uniqid(time() . '_', true) . '.' . $extension;
                $file->move(storage_path('images/SponsorImages'), $filename);
                $updateData['image'] = $filename;

                if($request->image_delete) {
                    $imagePath = storage_path('images/SponsorImages/' . $request->image_delete);

                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }
            }
            // Update the database
            DB::table('tb_sponsor')
                ->where('id', $request->id)
                ->update($updateData);

        }catch(Exception $e){
            throw $e;
        }
    }

    public function delete(Request $request){
        try {

            DB::table('tb_sponsor')
                ->where('id', $request->id)
                ->delete();

            if($request->image != null) {
                $imagePath = storage_path('images/SponsorImages/' . $request->image);

                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

        } catch (Exception $e) {
            throw $e;
        }
    }
}
