@extends('templates.index', [
    'entityName' => 'Cấu hình thanh toán',
    'routePrefix' => 'payment-configs',
    'createButton' => true
])

@section('table_headers')
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Năm học</th>
    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lương cơ bản / buổi</th>
    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($academicYears as $index => $academicYear)
        @if($academicYear->paymentConfigs->isNotEmpty())
            @foreach($academicYear->paymentConfigs as $config)
            <tr class="bg-white hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $academicYear->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ number_format($config->base_salary_per_session, 0, ',', '.') }} VNĐ
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                    <a href="{{ route('payment-configs.edit', $config) }}" 
                       class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors"
                       title="Chỉnh sửa">
                        <i class="fas fa-edit mr-1"></i> Sửa
                    </a>
                    <button class="btn-delete inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-red-700 bg-red-50 hover:bg-red-100 transition-colors" 
                            data-id="{{ $config->id }}"
                            title="Xóa">
                        <i class="fas fa-trash-alt mr-1"></i> Xóa
                    </button>
                </td>
            </tr>
            @endforeach
        @endif
    @empty
    <tr>
        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
            <div class="flex flex-col items-center justify-center py-8">
                <i class="fas fa-cogs text-4xl text-gray-400 mb-3"></i>
                <span class="text-gray-500 text-lg mb-2">Chưa có cấu hình thanh toán</span>
                <p class="text-gray-400 text-sm max-w-md text-center">
                    Vui lòng tạo cấu hình thanh toán để hệ thống có thể tính toán lương cho giảng viên
                </p>
            </div>
        </td>
    </tr>
    @endforelse
@endsection