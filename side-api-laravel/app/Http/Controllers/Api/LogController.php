

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

/**
 * LogController
 *
 * 日志与提醒管理接口（支持增删查改和批量删除）。
 * 用于处理系统日志、到期提醒等相关数据。
 */
class LogController extends Controller
{
    /**
     * 获取日志列表（支持关键字搜索与分页）
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * 请求参数:
     * - keyword (string): 关键字模糊搜索（type/content/object等）
     * - page/per_page: 分页参数
     *
     * 返回结构:
     * - Laravel 分页对象
     */
    public function index(Request $request)
    {
        $query = Log::query();
        if ($keyword = $request->input('keyword')) {
            $query->where('type', 'like', "%$keyword%")
                  ->orWhere('content', 'like', "%$keyword%")
                  ->orWhere('object', 'like', "%$keyword%");
        }
        $list = $query->orderByDesc('id')->paginate($request->input('per_page', 20));
        return response()->json($list);
    }

    /**
     * 获取日志详情
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $log = Log::findOrFail($id);
        return response()->json($log);
    }

    /**
     * 新增日志
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * 请求体: 日志字段（type, content, object等）
     * 返回: 新增日志对象
     */
    public function store(Request $request)
    {
        $log = Log::create($request->all());
        return response()->json($log, 201);
    }

    /**
     * 更新日志
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * 请求体: 日志可更新字段
     * 返回: 更新后的日志对象
     */
    public function update(Request $request, $id)
    {
        $log = Log::findOrFail($id);
        $log->update($request->all());
        return response()->json($log);
    }

    /**
     * 删除日志（单条或批量，逗号分隔id）
     *
     * @param string $id 支持单个id或'1,2,3'批量
     * @return \Illuminate\Http\JsonResponse
     *
     * 返回: { message: 'Deleted' }
     */
    public function destroy($id)
    {
        if (strpos($id, ',') !== false) {
            $ids = explode(',', $id);
            Log::destroy($ids);
        } else {
            $log = Log::findOrFail($id);
            $log->delete();
        }
        return response()->json(['message' => 'Deleted']);
    }

    /**
     * 批量删除日志（推荐单独路由POST/DELETE /api/logs/batch）
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * 请求体: { "ids": [1,2,3] }
     * 返回: { message: 'Batch deleted' }
     */
    public function batchDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if ($ids) {
            Log::whereIn('id', $ids)->delete();
        }
        return response()->json(['message' => 'Batch deleted']);
    }
}
