<?php

use App\Models\Document;
use App\Models\User;
test('document has correct fillable attributes', function () {
    $fillable = (new Document())->getFillable();

    expect($fillable)->toContain('document_type_id', 'entity_type', 'entity_id', 'file_name', 'file_path', 'file_size_bytes', 'mime_type', 'is_encrypted', 'uploaded_by');
});

test('document has UPDATED_AT set to null', function () {
    expect(Document::UPDATED_AT)->toBeNull();
});

test('is_encrypted is cast to boolean', function () {
    $doc = Document::factory()->create(['is_encrypted' => 1]);

    expect($doc->is_encrypted)->toBeBool()->toBeTrue();
});

test('file_size_bytes is cast to integer', function () {
    $doc = Document::factory()->create(['file_size_bytes' => '12345']);

    expect($doc->file_size_bytes)->toBeInt()->toBe(12345);
});

test('document belongs to uploader', function () {
    $user = User::factory()->create();
    $doc = Document::factory()->create(['uploaded_by' => $user->id]);

    expect($doc->uploader)->toBeInstanceOf(User::class);
    expect($doc->uploader->id)->toBe($user->id);
});
