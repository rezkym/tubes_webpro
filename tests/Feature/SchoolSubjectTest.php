<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SchoolSubject;
use App\Models\TeacherProfile;
use App\Models\SchoolClass;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\SchoolSubjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SchoolSubjectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(SchoolSubjectSeeder::class);
    }

    /** @test */
    public function admin_can_view_subject_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('school-subjects.index'));

        $response->assertStatus(200)
            ->assertViewIs('school-subjects.index')
            ->assertViewHas('subjects')
            ->assertSee('Test Subject 1')
            ->assertSee('Test Subject 2');
    }

    /** @test */

    /** @test */
    public function student_cannot_access_subject_list()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->get(route('school-subjects.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function subject_list_shows_correct_relations()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('school-subjects.index'));

        $response->assertStatus(200)
            ->assertSee('Test Teacher 1')
            ->assertSee('Test Class 1')
            ->assertSee('Test Class 2');
    }

    /** @test */
    public function inactive_subjects_are_marked_correctly()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('school-subjects.index'));

        $response->assertStatus(200)
            ->assertSee('Active')
            ->assertSee('Inactive');
    }

    /** @test */
    public function admin_can_create_new_subject()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $teacher = TeacherProfile::first();
        $class = SchoolClass::first();

        $response = $this->actingAs($admin)->post(route('school-subjects.store'), [
            'name' => 'New Test Subject',
            'code' => 'NTS',
            'description' => 'New Test Subject Description',
            'teacher_id' => $teacher->user_id,
            'classes' => [$class->id],
            'is_active' => true
        ]);

        $response->assertRedirect(route('school-subjects.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('school_subjects', [
            'name' => 'New Test Subject',
            'code' => 'NTS'
        ]);
    }

    /** @test */
    public function cannot_create_subject_with_duplicate_code()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $teacher = TeacherProfile::first();
        $class = SchoolClass::first();

        $response = $this->actingAs($admin)->post(route('school-subjects.store'), [
            'name' => 'Duplicate Subject',
            'code' => 'TS1', // Already exists in seeder
            'description' => 'Test Description',
            'teacher_id' => $teacher->user_id,
            'classes' => [$class->id],
            'is_active' => true
        ]);

        $response->assertSessionHasErrors('code');
    }

    /** @test */
    public function admin_can_update_subject()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $subject = SchoolSubject::first();
        $teacher = TeacherProfile::first();

        $response = $this->actingAs($admin)->put(route('school-subjects.update', $subject), [
            'name' => 'Updated Subject Name',
            'code' => $subject->code,
            'description' => 'Updated Description',
            'teacher_id' => $teacher->user_id,
            'is_active' => true
        ]);

        $response->assertRedirect(route('school-subjects.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('school_subjects', [
            'id' => $subject->id,
            'name' => 'Updated Subject Name'
        ]);
    }

    /** @test */
    public function admin_can_delete_subject()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $subject = SchoolSubject::create([
            'name' => 'Subject to Delete',
            'code' => 'STD',
            'description' => 'This subject will be deleted',
            'is_active' => true
        ]);

        $response = $this->actingAs($admin)->delete(route('school-subjects.destroy', $subject));

        $response->assertRedirect(route('school-subjects.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted($subject);
    }

    /** @test */
    public function cannot_delete_subject_with_attendance_templates()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $subject = SchoolSubject::first();

        // Create attendance template for this subject
        $subject->attendanceTemplates()->create([
            'school_class_id' => SchoolClass::first()->id,
            'teacher_profile_id' => TeacherProfile::first()->id,
            'name' => 'Test Template',
            'day' => 'monday',
            'start_time' => '08:00',
            'end_time' => '09:00',
            'is_active' => true
        ]);

        $response = $this->actingAs($admin)->delete(route('school-subjects.destroy', $subject));

        $response->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('school_subjects', [
            'id' => $subject->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function subject_validation_rules_are_enforced()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('school-subjects.store'), [
            'name' => '', // Required
            'code' => '', // Required
            'teacher_id' => '', // Required
            'is_active' => 'invalid' // Boolean
        ]);

        $response->assertSessionHasErrors(['name', 'code', 'teacher_id', 'is_active']);
    }

    /** @test */
    public function can_assign_multiple_classes_to_subject()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $subject = SchoolSubject::first();
        $classes = SchoolClass::take(2)->get()->pluck('id')->toArray();
        $teacher = TeacherProfile::first();

        $response = $this->actingAs($admin)->put(route('school-subjects.update', $subject), [
            'name' => $subject->name,
            'code' => $subject->code,
            'description' => $subject->description,
            'teacher_id' => $teacher->user_id,
            'classes' => $classes,
            'is_active' => true
        ]);

        $response->assertRedirect(route('school-subjects.index'))
            ->assertSessionHas('success');

        foreach ($classes as $classId) {
            $this->assertDatabaseHas('class_subject', [
                'subject_id' => $subject->id,
                'class_id' => $classId
            ]);
        }
    }
}
