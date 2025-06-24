<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Faculty;
use App\Http\Controllers\FacultyController;
use Illuminate\Http\RedirectResponse;
use Mockery;

class FacultyControllerTest extends TestCase
{
    /** @test */
    public function destroy_faculty_with_teachers_or_courses_should_fail()
    {
        // Giả lập faculty có teachers hoặc courses
        $facultyMock = Mockery::mock(Faculty::class);
        $facultyMock->shouldReceive('teachers->exists')->andReturn(true);
        $facultyMock->shouldReceive('courses->exists')->andReturn(false); // courses ko check nữa vì teachers đã true

        $controller = new FacultyController();
        $response = $controller->destroy($facultyMock);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url(route('faculties.index')), $response->getTargetUrl());
        $this->assertEquals(session('error'), 'Không thể xóa khoa vì có giáo viên hoặc học phần đang sử dụng.');
    }

    /** @test */
    public function destroy_faculty_without_teachers_or_courses_should_pass()
    {
        // Giả lập faculty không có teachers và courses
        $facultyMock = Mockery::mock(Faculty::class);
        $facultyMock->shouldReceive('teachers->exists')->andReturn(false);
        $facultyMock->shouldReceive('courses->exists')->andReturn(false);
        $facultyMock->shouldReceive('delete')->once();

        $controller = new FacultyController();
        $response = $controller->destroy($facultyMock);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url(route('faculties.index')), $response->getTargetUrl());
        $this->assertEquals(session('success'), 'Khoa đã được xóa thành công.');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
