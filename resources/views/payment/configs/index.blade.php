@extends('templates.index', [
    'entityName' => 'Cấu hình thanh toán',
    'routePrefix' => 'payment-configs'
])

@section('table_headers')
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lương cơ bản / buổi</th>
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hệ số buổi thực hành</th>
    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @if($config->exists)
    <tr class="bg-white hover:bg-gray-100 transition-colors">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($config->base_salary_per_session, 0, ',', '.') }} VNĐ</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $config->practice_session_rate }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
            <a href="{{ route('payment-configs.edit', $config) }}" 
               class="text-blue-600 hover:text-blue-900 transition-colors"
               title="Chỉnh sửa">
                <i class="fas fa-edit"></i>
            </a>
        </td>
    </tr>
    @else
    <tr>
        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
            <div class="flex flex-col items-center justify-center py-4">
                <i class="fas fa-inbox text-3xl text-gray-400 mb-2"></i>
                <span class="text-gray-500">Chưa có cấu hình thanh toán nào</span>
            </div>
        </td>
    </tr>
    @endif
@endsection
