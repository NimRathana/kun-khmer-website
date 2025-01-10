<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class CompanyProfileController extends Controller
{
    public function getCompanyProfile(){
        $data = DB::table('tb_company_profile')->get();
        return response()->json($data);
    }

    public function create(Request $request){
        try{

            $dataID = DB::table('tb_company_profile')->insertGetId([
                'name_km' => $request->name_km,
                'name_en' => $request->name_en,
                'logo' => $request->logo,
                'created_date' => $request->created_date,
                'location' => $request->location,
                'phone' => $request->phone,
                'email' => $request->email,
                'isUsed' => $request->isUsed,
                'remark' => $request->remark,
            ]);

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(storage_path('images/CompanyProfile'), $filename);

                DB::table('tb_company_profile')
                ->where('id', $dataID)
                ->update([
                    'logo' => $filename,
                ]);
            }

        }catch(Exception $e){
            throw $e;
        }
    }

    public function update(Request $request){
        try{

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(storage_path('images/CompanyProfile'), $filename);

                DB::table('tb_company_profile')
                ->where('id', $request->id)
                ->update([
                    'logo' => $filename,
                ]);
            }

            DB::table('tb_company_profile')
            ->where('id', $request->id)
            ->update([
                'name_km' => $request->name_km,
                'name_en' => $request->name_en,
                'created_date' => $request->created_date,
                'location' => $request->location,
                'phone' => $request->phone,
                'email' => $request->email,
                'isUsed' => $request->isUsed,
                'remark' => $request->remark,
            ]);

            if($request->logo_delete != null) {
                $imagePath = storage_path('images/CompanyProfile/' . $request->logo_delete);

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

            DB::table('tb_company_profile')
                ->where('id', $request->id)
                ->delete();

            if($request->logo != null) {
                $imagePath = storage_path('images/CompanyProfile/' . $request->logo);

                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

        } catch (Exception $e) {
            throw $e;
        }
    }
}
