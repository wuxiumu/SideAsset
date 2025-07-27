<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    // 列表
    public function index(Request $request)
    {
        $query = Server::query();
        // 支持搜索
        if ($keyword = $request->input('keyword')) {
            $query->where('name', 'like', "%$keyword%")
                  ->orWhere('ip', 'like', "%$keyword%")
                  ->orWhere('purpose', 'like', "%$keyword%")
                  ->orWhere('project', 'like', "%$keyword%");
        }
        $list = $query->orderByDesc('id')->paginate(20);
        return response()->json($list);
    }

    // 详情
    public function show($id)
    {
        $Server = Server::findOrFail($id);
        return response()->json($Server);
    }

    // 新增
    public function store(Request $request)
    {
        $Server = Server::create($request->all());
        return response()->json($Server, 201);
    }

    // 更新
    public function update(Request $request, $id)
    {
        $Server = Server::findOrFail($id);
        $Server->update($request->all());
        return response()->json($Server);
    }

    // 删除
    public function destroy($id)
    {
        // 判断 $id 是否包含逗号
        if (strpos($id, ',') !== false) {
            // 批量删除
            $ids = explode(',', $id);
            Server::destroy($ids);
        } else {
            // 单个删除
            $Server = Server::findOrFail($id);
            $Server->delete();
        }

        return response()->json(['message' => 'Deleted']);
    }
}
