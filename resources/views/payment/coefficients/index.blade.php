@extends('templates.index', [
    'entityName' => 'Hệ số lớp học',
    'routePrefix' => 'class-size-coefficients',
    'createButton' => true
])

@section('table_headers')
    <th class="px-6 py-3 text-left">STT</th>
    <th class="px-6 py-3 text-left">Năm học</th>
    <th class="px-6 py-3 text-left">Khoảng sinh viên</th>
    <th class="px-6 py-3 text-left">Hệ số</th>
    <th class="px-6 py-3 text-right">Thao tác</th>
@endsection

@section('table_rows')
    @forelse($academicYears as $year)
        @if($year->classSizeCoefficients->isNotEmpty())
            <tr class="bg-gray-50">
                <td colspan="5" class="px-6 py-4 font-medium text-gray-900">
                    {{ $year->name }} ({{ $year->start_date->format('d/m/Y') }} - {{ $year->end_date->format('d/m/Y') }})
                </td>
            </tr>
            
            @foreach($year->classSizeCoefficients as $index => $coefficient)
            <tr class="bg-white hover:bg-gray-50">
                <td class="px-6 py-4">{{ $index + 1 }}</td>
                <td class="px-6 py-4"></td> <!-- Empty for alignment -->
                <td class="px-6 py-4">
                    Từ {{ $coefficient->min_students }} đến {{ $coefficient->max_students }} SV
                </td>
                <td class="px-6 py-4">{{ number_format($coefficient->coefficient, 2) }}x</td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('class-size-coefficients.edit', $coefficient) }}" 
                       class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                    <button class="btn-delete text-red-600 hover:text-red-900" 
                            data-id="{{ $coefficient->id }}">
                        <i class="fas fa-trash-alt"></i> Xóa
                    </button>
                </td>
            </tr>
            @endforeach
        @endif
    @empty
    <tr>
        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
            <div class="flex flex-col items-center justify-center py-8">
                <i class="fas fa-sliders-h text-4xl text-gray-400 mb-3"></i>
                <span class="text-lg mb-2">Chưa có hệ số lớp học nào</span>
                <p class="text-sm max-w-md">
                    Vui lòng thêm hệ số lớp học để hệ thống có thể tính toán lương theo quy mô lớp
                </p>
            </div>
        </td>
    </tr>
    @endforelse
@endsection