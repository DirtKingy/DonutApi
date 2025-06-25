<?php

namespace App\Http\Controllers;

use App\Models\DonutApi;
use Illuminate\Http\Request;

class DonutApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
        {
            $sort = $request->query('sort');
            $order = $request->query('order', 'asc');

            $query = DonutApi::query();

            if ($sort === 'name') {
                $query->orderBy('name', $order);
            } elseif ($sort === 'approval') {
                $query->orderBy('seal_of_approval', $order);
            }

            return response()->json($query->get());
        }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:donuts|max:255',
            'seal_of_approval' => 'required|integer|between:1,5',
            'price' => 'required|numeric|min:0',
        ]);

        $donut = DonutApi::create($validated);

        return response()->json($donut, 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $donut = DonutApi::find($id);

        if (!$donut) {
            return response()->json(['message' => 'Donut not found'], 404);
        }

        $donut->delete();

        return response()->json(['message' => 'Donut deleted']);
    }
}
