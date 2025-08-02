<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    // 列表
    public function index(Request $request)
    {
        $query = Domain::query();
        if ($request->filled('keyword')) {
                    $query->where('registrar', 'like', '%' . $request->keyword . '%')
                    ->orWhere('domain', 'like', '%' . $request->keyword . '%')
                    ->orWhere('project', 'like', '%' . $request->keyword . '%');
                }
        if ($registrar = $request->input('registrar')) {
            $query->where('registrar', $registrar);
        }
        $list = $query->orderByDesc('id')->paginate(20);
        return response()->json($list);
    }

    // 详情
    public function show($id)
    {
        $domain = Domain::findOrFail($id);
        return response()->json($domain);
    }

    // 新增
    public function store(Request $request)
    {
        $domain = Domain::create($request->all());
        return response()->json($domain, 201);
    }

    // 更新
    public function update(Request $request, $id)
    {
        $domain = Domain::findOrFail($id);
        $domain->update($request->all());
        return response()->json($domain);
    }

    // 删除
    public function destroy($id)
    {
        // 判断 $id 是否包含逗号
        if (strpos($id, ',') !== false) {
            // 批量删除
            $ids = explode(',', $id);
            Domain::destroy($ids);
        } else {
            // 单个删除
            $domain = Domain::findOrFail($id);
            $domain->delete();
        }

        return response()->json(['message' => 'Deleted']);
    }
}
