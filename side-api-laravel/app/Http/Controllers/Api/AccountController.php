<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

/**
 * AccountController
 *
 * 用于管理平台账号（如阿里云、GitHub等），支持分页、搜索、增删改查、批量删除。
 */
class AccountController extends Controller
{
    /**
     * 获取账号列表（分页+关键字搜索）
     */
    public function index(Request $request)
    {
        $query = Account::query();
        // 支持平台、账号、邮箱、绑定业务模糊搜索
        if ($keyword = $request->input('keyword')) {
            $query->where('platform', 'like', "%$keyword%")
                  ->orWhere('username', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%")
                  ->orWhere('bind_project', 'like', "%$keyword%");
        }
        $list = $query->orderByDesc('id')->paginate($request->input('per_page', 20));
        return response()->json($list);
    }

    /**
     * 获取账号详情
     */
    public function show($id)
    {
        $account = Account::findOrFail($id);
        return response()->json($account);
    }

    /**
     * 新增账号
     */
    public function store(Request $request)
    {
        $account = Account::create($request->all());
        return response()->json($account, 201);
    }

    /**
     * 更新账号
     */
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);
        $account->update($request->all());
        return response()->json($account);
    }

    /**
     * 删除账号（单个或批量）
     */
    public function destroy($id)
    {
        if (strpos($id, ',') !== false) {
            $ids = explode(',', $id);
            Account::destroy($ids);
        } else {
            $account = Account::findOrFail($id);
            $account->delete();
        }
        return response()->json(['message' => 'Deleted']);
    }
}
