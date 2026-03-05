<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /***********************************
     * GET Routes
     ***********************************/
    public function test_api_successfully_returns_categories_list(): void
    {
        Category::factory(10)->create();

        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('index_category'));

        $response->assertStatus(200)
            ->assertJsonCount(10);
    }

    public function test_api_does_not_return_any_categories(): void
    {
        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('index_category'));

        $response->assertStatus(404)
            ->assertJson(['message' => 'Categories not found']);
    }

    public function test_successfully_return_existing_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('show_category', ['id' => $category->id]));

        $response->assertStatus(200);
    }

    public function test_can_not_find_existing_category(): void
    {
        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('show_category', ['id' => 1]));

        $response->assertStatus(404);
    }

    /***********************************
     * POST Routes
     ***********************************/
    public function test_store_new_category_successfully(): void
    {
        $entity = Entity::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->postJson(
                route('store_category'),
                [
                    'title' => 'Test Title 1',
                    'entity_id' => $entity->id,
                ]
            );

        $response->assertStatus(201)
            ->assertJsonCount(1);
    }

    public function test_unsuccessfully_store_new_category_with_invalid_parameters(): void
    {
        $entity = Entity::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->postJson(
                route('store_category'),
                [
                    'title' => '',
                    'entity_id' => $entity->id,
                ]
            );

        $response->assertStatus(422);
    }

    /***********************************
     * PUT Routes
     ***********************************/
    public function test_successfully_update_category(): void
    {
        $category = Category::factory()->create();
        $entity = Entity::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->putJson(
                route('update_category', ['id' => $category->id]),
                [
                    'title' => 'New Title here',
                    'entity_id' => $entity->id,
                ]
            );

        $response->assertStatus(201)
            ->assertJsonCount(1);
    }

    public function test_can_not_update_category_without_valid_parameters(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->putJson(
                route('update_category', ['id' => $category->id]),
                [
                    'title' => 'New Title here',
                    'entity_id' => 9999,
                ]
            );

        $response->assertStatus(422);
    }

    /***********************************
     * DELETE Routes
     ***********************************/
    public function test_successfully_delete_existing_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->deleteJson(route('destroy_category', ['id' => $category->id]));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Category deleted successfully']);
    }

    public function test_delete_nonexistent_category_returns_error_response(): void
    {
        $response = $this->actingAs($this->user, 'api')
            ->deleteJson(route('destroy_category', ['id' => 99999]));

        $response->assertStatus(200);
    }
}
