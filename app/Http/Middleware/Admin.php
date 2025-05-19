<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Kiểm tra quyền admin
        if (Auth::user()->usertype !== 'admin') {
            // Nếu đang ở trang chủ, hiển thị thông báo lỗi
            if ($request->is('/')) {
                return redirect('/')->with('error', 'Bạn không có quyền truy cập trang này.');
            }
            // Nếu không phải ở trang chủ, chuyển về trang chủ
            return redirect('/');
        }

        return $next($request);
    }
}
