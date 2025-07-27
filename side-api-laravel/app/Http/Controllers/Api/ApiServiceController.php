<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiService;
use Illuminate\Http\Request;

/**
 * ApiServiceController
 *
 * 负责 API 服务管理的CURD（创建、读取、更新、删除）接口
 */
class ApiServiceController extends Controller
{
    /**
     * 获取 API 服务列表（分页）
     *
     * 支持关键字搜索，可按需扩展更多筛选条件
     */
    public function index(Request $request)
    {
        $query = ApiService::query();

        // 搜索功能，支持 name、platform、bind_project 字段模糊查找
        if ($keyword = $request->input('keyword')) {
            $query->where('name', 'like', "%$keyword%")
                  ->orWhere('platform', 'like', "%$keyword%")
                  ->orWhere('bind_project', 'like', "%$keyword%");
        }

        // 分页，前端可传 page/per_page，默认每页20条
        $list = $query->orderByDesc('id')->paginate($request->input('per_page', 20));
        return response()->json($list);
    }

    /**
     * 获取单个 API 服务详情
     */
    public function show($id)
    {
        $apiService = ApiService::findOrFail($id);
        return response()->json($apiService);
    }

    /**
     * 新增 API 服务
     */
    public function store(Request $request)
    {
        $apiService = ApiService::create($request->all());
        return response()->json($apiService, 201);
    }

    /**
     * 更新 API 服务
     */
    public function update(Request $request, $id)
    {
        $apiService = ApiService::findOrFail($id);
        $apiService->update($request->all());
        return response()->json($apiService);
    }

    /**
     * 删除 API 服务（支持单个或批量，批量用逗号分隔id）
     */
    public function destroy($id)
    {
        // 判断是否批量删除
        if (strpos($id, ',') !== false) {
            // 批量删除
            $ids = explode(',', $id);
            ApiService::destroy($ids);
        } else {
            // 单个删除
            $apiService = ApiService::findOrFail($id);
            $apiService->delete();
        }
        return response()->json(['message' => 'Deleted']);
    }
}
