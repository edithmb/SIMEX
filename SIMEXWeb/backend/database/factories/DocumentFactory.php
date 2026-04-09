<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'document_type_id' => 1,
            'entity_type' => 'App\\Models\\Client',
            'entity_id' => 1,
            'file_name' => fake()->word() . '.pdf',
            'file_path' => '/uploads/' . fake()->uuid() . '.pdf',
            'file_size_bytes' => fake()->numberBetween(1024, 10485760),
            'mime_type' => 'application/pdf',
            'is_encrypted' => false,
            'encryption_key' => null,
            'uploaded_by' => User::factory(),
        ];
    }
}
