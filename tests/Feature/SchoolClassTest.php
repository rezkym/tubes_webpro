<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SchoolClassTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'teacher']);
        Role::create(['name' => 'student']);
        Permission::create(['name' => 'manage classes']);

        // Create an admin user and assign permissions
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        $this->admin->givePermissionTo('manage classes');
    }

    public function test_index()
    {
        $response = $this->actingAs($this->admin)->get(route('school-classes.index'));
        $response->assertStatus(200);
        $response->assertViewIs('school-classes.index');
    }

    public function test_create()
    {
        $response = $this->actingAs($this->admin)->get(route('school-classes.create'));
        $response->assertStatus(200);
        $response->assertViewIs('school-classes.create');
    }

    public function test_store()
    {
        $data = [
            'name' => 'Test Class',
            'code' => 'CLS-1234',
            'description' => 'Test Description',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post(route('school-classes.store'), $data);
        $response->assertRedirect(route('school-classes.index'));
        $this->assertDatabaseHas('school_classes', ['name' => 'Test Class']);
    }

    public function test_edit()
    {
        $class = SchoolClass::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('school-classes.edit', $class));
        $response->assertStatus(200);
        $response->assertViewIs('school-classes.edit');
    }

    public function test_update()
    {
        $class = SchoolClass::factory()->create();
        $data = [
            'name' => 'Updated Class',
            'code' => 'CLS-5678',
            'description' => 'Updated Description',
            'is_active' => false,
        ];

        $response = $this->actingAs($this->admin)->put(route('school-classes.update', $class), $data);
        $response->assertRedirect(route('school-classes.index'));
        $this->assertDatabaseHas('school_classes', [
            'name' => 'Updated Class',
            'is_active' => 0,
        ]);
    }

    public function test_destroy()
    {
        $class = SchoolClass::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('school-classes.destroy', $class));
        $response->assertRedirect(route('school-classes.index'));
        $this->assertSoftDeleted('school_classes', ['id' => $class->id]);
    }
}
