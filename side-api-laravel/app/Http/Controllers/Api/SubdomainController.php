<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subdomain;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    public function index(Request $request)
    {
        $query = Subdomain::query();
        if ($domainId = $request->input('domain_id')) {
            $query->where('domain_id', $domainId);
        }
        // ...可加关键词筛选等
        $list = $query->orderByDesc('id')->paginate($request->input('per_page', 20));
        return response()->json($list);
    }
    public function show($id) {
        return response()->json(Subdomain::findOrFail($id));
    }
    public function store(Request $request) {
        return response()->json(Subdomain::create($request->all()), 201);
    }
    public function update(Request $request, $id) {
        $sd = Subdomain::findOrFail($id);
        $sd->update($request->all());
        return response()->json($sd);
    }
    public function destroy($id) {
        if (strpos($id, ',') !== false) {
            $ids = explode(',', $id);
            Subdomain::destroy($ids);
        } else {
            $sd = Subdomain::findOrFail($id);
            $sd->delete();
        }
        return response()->json(['message' => 'Deleted']);
    }
}
