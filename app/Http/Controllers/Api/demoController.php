<?php

namespace App\Http\Controllers\Api;
use Inertia\Inertia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class demoController extends Controller
{
    public function index(){
        // dd('ddsf');
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
}
