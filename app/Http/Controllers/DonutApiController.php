<?php

namespace App\Http\Controllers;

use App\Models\DonutApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\DonutResource;
use Illuminate\Support\Facades\Storage;

class DonutApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort');
        $order = $request->query('order', 'asc');
        $page = $request->query('page', 1);

        $cacheKey = "donuts_{$sort}_{$order}_page_{$page}";

        $donuts = Cache::remember($cacheKey, 60, function () use ($sort, $order) {
            $query = DonutApi::query();

            if ($sort === 'name') {
                $query->orderBy('name', $order);
            } elseif ($sort === 'approval') {
                $query->orderBy('seal_of_approval', $order);
            }

            return $query->paginate(10);
        });

        return DonutResource::collection($donuts);
    }

    protected function clearDonutCache()
    {
        $orders = ['asc', 'desc'];
        $sorts = ['name', 'approval', ''];

        for ($page = 1; $page <= 10; $page++) {
            foreach ($sorts as $sort) {
                foreach ($orders as $order) {
                    $key = "donuts_{$sort}_{$order}_page_{$page}";
                    Cache::forget($key);
                }
            }
        }
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('donut_images', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $donut = DonutApi::create($validated);
        $this->clearDonutCache();
        return new DonutResource($donut);
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
        $this->clearDonutCache();
        return response()->json(['message' => 'Donut deleted']);
    }
}
