<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoCardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_index_page_loads()
    {
        $response = $this->get('/photo-card');
        $response->assertStatus(200);
    }

    public function test_api_generate_creates_image()
    {
        $image = UploadedFile::fake()->image('test.jpg', 1200, 630);

        $response = $this->postJson('/api/photo-card', [
            'title' => 'Test News Title - বাংলা সংবাদ',
            'image' => $image,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ])
            ->assertJsonStructure([
                'status',
                'image_url',
            ]);
    }

    public function test_preview_returns_image()
    {
        $image = UploadedFile::fake()->image('test.jpg', 1200, 630);

        $response = $this->post('/photo-card/preview', [
            'title' => 'Preview Test - প্রিভিউ টেস্ট',
            'image' => $image,
        ]);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_generate_saves_image()
    {
        $image = UploadedFile::fake()->image('test.jpg', 1200, 630);

        $response = $this->post('/photo-card/generate', [
            'title' => 'Generate Test - জেনারেট টেস্ট',
            'image' => $image,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);
    }

    public function test_preview_with_brand_name()
    {
        $image = UploadedFile::fake()->image('test.jpg', 1200, 630);

        $response = $this->post('/photo-card/preview', [
            'title' => 'Test with Brand Name',
            'image' => $image,
            'brand_name' => 'Daily News',
        ]);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_preview_with_custom_template()
    {
        $image = UploadedFile::fake()->image('test.jpg', 1200, 630);

        $response = $this->post('/photo-card/preview', [
            'title' => 'Custom Template Test',
            'image' => $image,
            'template' => 'fullwidth',
        ]);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_preview_with_custom_color()
    {
        $image = UploadedFile::fake()->image('test.jpg', 1200, 630);

        $response = $this->post('/photo-card/preview', [
            'title' => 'Color Test',
            'image' => $image,
            'overlay_color' => '#1e3a8a',
            'overlay_opacity' => 80,
        ]);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_preview_with_logo_position()
    {
        $image = UploadedFile::fake()->image('test.jpg', 1200, 630);

        $response = $this->post('/photo-card/preview', [
            'title' => 'Logo Position Test',
            'image' => $image,
            'logo_position' => 'bottom-right',
        ]);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_preview_with_text_position()
    {
        $image = UploadedFile::fake()->image('test.jpg', 1200, 630);

        $response = $this->post('/photo-card/preview', [
            'title' => 'Text Position Test',
            'image' => $image,
            'text_position' => 'center',
        ]);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_preview_with_split_template()
    {
        $image = UploadedFile::fake()->image('test.jpg', 1200, 630);

        $response = $this->post('/photo-card/preview', [
            'title' => 'Split Template Test',
            'image' => $image,
            'template' => 'split-left',
        ]);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_preview_with_minimal_template()
    {
        $image = UploadedFile::fake()->image('test.jpg', 1200, 630);

        $response = $this->post('/photo-card/preview', [
            'title' => 'Minimal Template Test',
            'image' => $image,
            'template' => 'minimal',
        ]);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'image/png');
    }

    public function test_validation_requires_title()
    {
        $image = UploadedFile::fake()->image('test.jpg', 1200, 630);

        $response = $this->postJson('/api/photo-card', [
            'image' => $image,
        ]);

        $response->assertStatus(422);
    }

    public function test_validation_requires_image()
    {
        $response = $this->postJson('/api/photo-card', [
            'title' => 'Test Title',
        ]);

        $response->assertStatus(422);
    }
}
