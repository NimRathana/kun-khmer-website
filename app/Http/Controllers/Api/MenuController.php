<?php

namespace App\Http\Controllers\Api;

use Exception;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index(){
        return Inertia::render('Dashboard');
    }
    public function getGrid(){
        $data = [
            'status' => 'success',
            'message' => 'Dashboard data retrieved successfully',
            'data' => [
                'grid' => [
                    ['id' => 1, 'name' => 'Item 1'],
                    ['id' => 2, 'name' => 'Item 2'],
                    ['id' => 3, 'name' => 'Item 3'],
                ],
            ],
        ];
        return response()->json($data);
    }

    public function getMenu() {
        // Fetch all menus from the database
        $menus = DB::table('tb_menus')->get();

        // Group menus by parent_id
        $grouped = $menus->groupBy('parent_id');

        $buildTree = function ($parentId) use (&$buildTree, $grouped) {
            return $grouped->get($parentId, collect())->map(function ($menu) use (&$buildTree) {
                $children = $buildTree($menu->id); // Recursively fetch children

                // If children are not empty, add them to the result
                $menuData = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'url' => $menu->url,
                    'order' => $menu->order,
                    'icon' => $menu->icon,
                ];

                // Add 'children' only if it is not empty
                if ($children->isNotEmpty()) {
                    $menuData['children'] = $children;
                }

                return $menuData;
            });
        };

        // Start building the tree from the top-level menus (parent_id = null)
        $menuTree = $buildTree(null);
        return response()->json($menuTree);
    }

    public function getMenuGrid(){
        $menus = DB::table('tb_menus')->select('id','name','url','parent_id','order','icon')->get();
        return response()->json($menus);
    }

    public function createMenu(Request $request){
        try{
            $request->validate([
                'name' => 'required',
                'url' => 'required',
                'parent_id' => 'nullable|integer',
                'order' => 'required',
                'icon' => 'required',
            ],[
                'name.required' => 'Name is required',
                'url.required' => 'Url is required',
                'order.required' => 'Order is required',
                'icon.required' => 'Icon is required',
            ]);

            DB::table('tb_menus')->insert([
                'name' => $request->name,
                'url' => $request->url,
                'parent_id' => $request->parent_id,
                'order' => $request->order,
                'icon' => $request->icon,
            ]);

            // if ($menu) {
            //     return Inertia::render('Components/MenuSystem', [
            //         'message' => 'Menu created successfully',
            //         'status' => 'success',
            //     ]);
            // }

        }catch(Exception $e){
            throw $e;
        }
    }

    public function updateMenu(Request $request){
        try{
            $request->validate([
                'name' => 'required',
                'url' => 'required',
                'parent_id' => 'nullable|integer',
                'order' => 'required',
                'icon' => 'required',
            ],[
                'name.required' => 'Name is required',
                'url.required' => 'Url is required',
                'order.required' => 'Order is required',
                'icon.required' => 'Icon is required',
            ]);

            DB::table('tb_menus')
            ->where('id', $request->id)
            ->update([
                'name' => $request->name,
                'url' => $request->url,
                'parent_id' => $request->parent_id,
                'order' => $request->order,
                'icon' => $request->icon,
            ]);

        }catch(Exception $e){
            throw $e;
        }
    }

    public function deleteMenu(Request $request){
        try {
            DB::table('tb_menus')
                ->where('id', $request->id)
                ->delete();

        } catch (Exception $e) {
            throw $e;
        }
    }
}
