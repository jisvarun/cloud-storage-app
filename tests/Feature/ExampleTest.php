<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_redirects_to_student_list(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('students.index'));
    }

    public function test_student_list_can_be_searched(): void
    {
        Student::create([
            'full_name' => 'Asha Patel',
            'email' => 'asha@example.com',
            'mobile' => '5551002000',
            'photo' => 'https://example.com/asha.jpg',
            'sign' => 'https://example.com/asha-sign.jpg',
        ]);

        Student::create([
            'full_name' => 'Rahul Singh',
            'email' => 'rahul@example.com',
            'mobile' => '5553004000',
            'photo' => 'https://example.com/rahul.jpg',
            'sign' => 'https://example.com/rahul-sign.jpg',
        ]);

        $response = $this->get(route('students.index', ['search' => 'asha']));

        $response->assertStatus(200);
        $response->assertSee('Asha Patel');
        $response->assertDontSee('Rahul Singh');
        $response->assertSee('value="asha"', false);
    }

    public function test_student_list_can_be_sorted(): void
    {
        Student::create([
            'full_name' => 'Zara Patel',
            'email' => 'zara@example.com',
            'mobile' => '5551002000',
            'photo' => 'https://example.com/zara.jpg',
            'sign' => 'https://example.com/zara-sign.jpg',
        ]);

        Student::create([
            'full_name' => 'Amit Singh',
            'email' => 'amit@example.com',
            'mobile' => '5553004000',
            'photo' => 'https://example.com/amit.jpg',
            'sign' => 'https://example.com/amit-sign.jpg',
        ]);

        $response = $this->get(route('students.index', [
            'sort' => 'full_name',
            'direction' => 'asc',
        ]));

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Amit Singh', 'Zara Patel']);
        $response->assertSee('value="full_name" selected', false);
        $response->assertSee('value="asc" selected', false);
    }

    public function test_invalid_sort_options_fall_back_to_defaults(): void
    {
        $response = $this->get(route('students.index', [
            'sort' => 'invalid_column',
            'direction' => 'sideways',
        ]));

        $response->assertStatus(200);
        $response->assertSee('value="created_at" selected', false);
        $response->assertSee('value="desc" selected', false);
    }
}
