<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
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
    public function test_api_successfully_returns_articles_list(): void
    {
        $articles = Article::factory(2)->create();

        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('index_article'));

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_api_does_not_return_any_articles(): void
    {
        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('index_article'));

        $response->assertStatus(404)
            ->assertJson(['message' => 'Articles not found']);
    }

    public function test_successfully_return_existing_article(): void
    {
        $article = Article::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('show_article', ['id' => $article->id]));

        $response->assertStatus(200);
    }

    public function test_can_not_find_existing_article(): void
    {
        $response = $this->actingAs($this->user, 'api')
            ->getJson(route('show_article', ['id' => 1]));

        $response->assertStatus(404)
            ->assertJson(['message' => 'Article not found']);
    }

    /***********************************
     * POST Routes
     ***********************************/
    public function test_store_new_article_successfully(): void
    {
        Category::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->postJson(
                route('store_article'),
                [
                    'category_id' => 1,
                    'title' => 'Test title 1',
                    'body' => 'Test body content',
                    'is_favorite' => false,
                ]
            );

        $response->assertStatus(201)
            ->assertJsonCount(1);
    }

    public function test_unsuccessfully_store_new_article_with_invalid_parameters(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->postJson(
                route('store_article'),
                [
                    'category_id' => $category->id,
                    'title' => '',
                    'body' => '',
                    'is_favorite' => false,
                ]
            );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'body']);
    }

    /***********************************
     * PUT Routes
     ***********************************/
    public function test_successfully_update_article(): void
    {
        $article = Article::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->putJson(route(
                'update_article',
                [
                    'id' => $article->id,
                    'category_id' => $category->id,
                    'title' => 'New Title',
                    'body' => 'New Body',
                    'is_favorite' => true,
                ]
            ));

        $response->assertStatus(201);
    }

    public function test_can_not_update_article_without_valid_parameters(): void
    {
        $article = Article::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->putJson(route('update_article', ['id' => $article->id]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id', 'title', 'body']);
    }

    /***********************************
     * DELETE Routes
     ***********************************/
    public function test_successfully_delete_existing_article(): void
    {
        $article = Article::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->deleteJson(route('destroy_article', ['id' => $article->id]));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Article deleted successfully']);
    }

    public function test_can_not_delete_article_that_does_not_exist(): void
    {
        $response = $this->deleteJson(route('destroy_article', ['id' => 1]));

        $response->assertStatus(401);
    }
}
