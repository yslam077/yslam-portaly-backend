<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicApiDocumentationTest extends TestCase
{
    public function test_guest_can_open_the_documentation_ui_and_openapi_document(): void
    {
        $this->get('/docs/api')
            ->assertOk()
            ->assertSee('Yslam Portaly API');

        $this->getJson('/docs/api.json')
            ->assertOk()
            ->assertJsonPath('openapi', '3.1.2')
            ->assertJsonPath('info.title', 'Yslam Portaly API');
    }

    public function test_openapi_documents_each_api_route_and_only_secures_sanctum_routes(): void
    {
        $response = $this->getJson('/docs/api.json');
        $document = $response->json();
        $documentedRoutes = [];

        foreach ($document['paths'] as $path => $operations) {
            foreach (['get', 'post', 'put', 'patch', 'delete'] as $method) {
                if (isset($operations[$method])) {
                    $documentedRoutes[] = strtoupper($method).' '.$path;
                }
            }
        }

        $expectedRoutes = [
            'GET /api/v1/articles',
            'GET /api/v1/articles/featured',
            'GET /api/v1/articles/popular',
            'GET /api/v1/articles/trending',
            'GET /api/v1/articles/{slug}',
            'GET /api/v1/articles/{slug}/comments',
            'POST /api/v1/articles/{slug}/comments',
            'GET /api/v1/auth/me',
            'POST /api/v1/auth/logout',
            'POST /api/v1/auth/login',
            'POST /api/v1/auth/register',
            'POST /api/v1/auth/send-otp',
            'POST /api/v1/auth/verify-otp',
            'GET /api/v1/banners',
            'GET /api/v1/categories',
            'GET /api/v1/categories/{slug}/articles',
            'GET /api/v1/categories/{slug}/popular',
            'GET /api/v1/me',
            'PUT /api/v1/me',
            'GET /api/v1/tags',
        ];

        $response->assertOk();
        $this->assertEqualsCanonicalizing($expectedRoutes, $documentedRoutes);
        $this->assertSame(
            ['BearerAuth' => []],
            $document['paths']['/api/v1/me']['get']['security'][0]
        );
        $this->assertSame([], $document['paths']['/api/v1/articles']['get']['security']);
        $this->assertArrayHasKey('current_page', $document['paths']['/api/v1/articles']['get']['responses']['200']['content']['application/json']['example']['meta']);
        $this->assertArrayHasKey('translations', $document['paths']['/api/v1/categories']['get']['responses']['200']['content']['application/json']['example']['data'][0]);
    }

    public function test_openapi_json_allows_cross_origin_requests(): void
    {
        $response = $this->call('OPTIONS', '/docs/api.json', server: [
            'HTTP_ORIGIN' => 'https://client.example',
            'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'GET',
        ]);

        $response->assertNoContent();
        $response->assertHeader('Access-Control-Allow-Origin', '*');
    }
}
