<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_home_and_articles(): void
    {
        $this->seed();

        $post = Post::firstOrFail();

        $this->get('/')->assertOk();
        $this->get(route('posts.index'))->assertOk();
        $this->get(route('posts.show', $post))->assertOk();
    }

    public function test_guest_cannot_open_create_page(): void
    {
        $this->get(route('posts.create'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Комнатные растения',
            'description' => 'Описание категории',
        ]);

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'Как ухаживать за фикусом',
            'content' => 'Подробный материал об освещении, поливе и подкормке домашнего фикуса.',
            'category_id' => $category->id,
        ]);

        $post = Post::where('title', 'Как ухаживать за фикусом')->firstOrFail();

        $response->assertRedirect(route('posts.show', $post));
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
    }

    public function test_post_requires_valid_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => '',
                'content' => 'Коротко',
                'category_id' => 999,
            ])
            ->assertSessionHasErrors(['title', 'content', 'category_id']);
    }

    public function test_user_cannot_edit_another_users_post(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::create(['name' => 'Букеты']);
        $post = Post::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Весенний букет',
            'slug' => 'vesenniy-buket',
            'content' => 'Содержательный текст статьи о составлении красивого весеннего букета.',
        ]);

        $this->actingAs($otherUser)
            ->get(route('posts.edit', $post))
            ->assertForbidden();
    }
}
