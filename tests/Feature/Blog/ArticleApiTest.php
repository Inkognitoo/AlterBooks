<?php

namespace Tests\Feature\Blog;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ArticleApiTest extends TestCase
{
    use DatabaseMigrations;

    use DatabaseTransactions;

    /**
     * Проверяем успешное создание статьи
     *
     * @return void
     */
    public function testCreateArticle()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->post(route('api.blog.create') ,[
                'title' => 'test-title',
                'text' => 'test-text'
            ]);

        $response
            ->assertJson([
                'success' => true
            ]);
    }

    /**
     * Проверяем невозможность создания статьи без поля 'title'
     *
     * @return void
     */
    public function testCreateArticleNoFieldTitle()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->post(route('api.blog.create') ,[
                'text' => 'test-text'
            ]);

        $response
            ->assertJson([
                'success' => false
            ]);
    }

    /**
     * Проверяем невозможность создания статьи без поля 'text'
     *
     * @return void
     */
    public function testCreateArticleNoFieldText()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->post(route('api.blog.create') ,[
                'title' => 'test-title',
            ]);

        $response
            ->assertJson([
                'success' => false
            ]);
    }
}
