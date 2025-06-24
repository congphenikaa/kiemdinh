<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Teacher;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class FacultyControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->admin()->create();
    }

    /** @test */
    public function admin_can_view_faculties_index_page()
    {
        $response = $this->actingAs($this->admin)
                         ->get(route('faculties.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('teacher-management.faculties.index');
    }

    /** @test */
    public function admin_can_create_valid_faculty()
    {
        $data = [
            'name' => 'Công nghệ thông tin',
            'short_name' => 'CNTT',
            'description' => 'Khoa CNTT'
        ];

        $response = $this->actingAs($this->admin)
                         ->post(route('faculties.store'), $data);
        
        $response->assertRedirect(route('faculties.index'));
        $response->assertSessionHas('success', 'Khoa đã được tạo thành công.');
        $this->assertDatabaseHas('faculties', $data);
    }

    /** @test */
    public function cannot_create_faculty_with_empty_name()
    {
        $data = [
            'name' => '',
            'short_name' => 'KT',
            'description' => 'Mô tả bất kỳ'
        ];

        $response = $this->actingAs($this->admin)
                         ->post(route('faculties.store'), $data);
        
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('faculties', 0);
    }

    /** @test */
    public function cannot_create_faculty_with_empty_short_name()
    {
        $data = [
            'name' => 'Khoa Kinh tế',
            'short_name' => '',
            'description' => 'Mô tả bất kỳ'
        ];

        $response = $this->actingAs($this->admin)
                         ->post(route('faculties.store'), $data);
        
        $response->assertSessionHasErrors(['short_name']);
        $this->assertDatabaseCount('faculties', 0);
    }

    /** @test */
    public function cannot_create_faculty_with_duplicate_name()
    {
        Faculty::factory()->create([
            'name' => 'Công nghệ thông tin',
            'short_name' => 'CNTT'
        ]);

        $data = [
            'name' => 'Công nghệ thông tin',
            'short_name' => 'CNTT2',
            'description' => 'Mô tả'
        ];

        $response = $this->actingAs($this->admin)
                         ->post(route('faculties.store'), $data);
        
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('faculties', 1);
    }

    /** @test */
    public function cannot_create_faculty_with_duplicate_short_name()
    {
        Faculty::factory()->create([
            'name' => 'Khoa cũ',
            'short_name' => 'KC'
        ]);

        $data = [
            'name' => 'Khoa mới',
            'short_name' => 'KC',
            'description' => 'Mô tả'
        ];

        $response = $this->actingAs($this->admin)
                         ->post(route('faculties.store'), $data);
        
        $response->assertSessionHasErrors(['short_name']);
        $this->assertDatabaseCount('faculties', 1);
    }

    /** @test */
    public function cannot_create_faculty_with_long_short_name()
    {
        $data = [
            'name' => 'Khoa Mẫu',
            'short_name' => Str::random(51),
            'description' => 'Mô tả bất kỳ'
        ];

        $response = $this->actingAs($this->admin)
                         ->post(route('faculties.store'), $data);
        
        $response->assertSessionHasErrors(['short_name']);
        $this->assertDatabaseCount('faculties', 0);
    }

    /** @test */
    public function admin_can_delete_faculty_without_constraints()
    {
        $faculty = Faculty::factory()->create();

        $response = $this->actingAs($this->admin)
                         ->delete(route('faculties.destroy', $faculty));
        
        $response->assertRedirect(route('faculties.index'));
        $response->assertSessionHas('success', 'Khoa đã được xóa thành công.');
        $this->assertDatabaseMissing('faculties', ['id' => $faculty->id]);
    }

    /** @test */
    public function cannot_delete_faculty_with_teachers()
    {
        $faculty = Faculty::factory()->create();
        Teacher::factory()->create(['faculty_id' => $faculty->id]);

        $response = $this->actingAs($this->admin)
                         ->delete(route('faculties.destroy', $faculty));
        
        $response->assertRedirect(route('faculties.index'));
        $response->assertSessionHas('error', 'Không thể xóa khoa vì có giáo viên hoặc học phần đang sử dụng.');
        $this->assertDatabaseHas('faculties', ['id' => $faculty->id]);
    }

    /** @test */
    public function cannot_delete_faculty_with_courses()
    {
        $faculty = Faculty::factory()->create();
        Course::factory()->create(['faculty_id' => $faculty->id]);

        $response = $this->actingAs($this->admin)
                         ->delete(route('faculties.destroy', $faculty));
        
        $response->assertRedirect(route('faculties.index'));
        $response->assertSessionHas('error', 'Không thể xóa khoa vì có giáo viên hoặc học phần đang sử dụng.');
        $this->assertDatabaseHas('faculties', ['id' => $faculty->id]);
    }

    /** @test */
    public function admin_can_update_faculty()
    {
        $faculty = Faculty::factory()->create();
        $newData = [
            'name' => 'Tên mới',
            'short_name' => 'TM',
            'description' => 'Mô tả mới'
        ];

        $response = $this->actingAs($this->admin)
                         ->put(route('faculties.update', $faculty), $newData);
        
        $response->assertRedirect(route('faculties.index'));
        $response->assertSessionHas('success', 'Khoa đã được cập nhật thành công.');
        $this->assertDatabaseHas('faculties', $newData);
    }
}