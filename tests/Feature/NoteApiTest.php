<?php

namespace Tests\Feature;

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NoteApiTest extends TestCase
{
    use RefreshDatabase;

    // ── List ────────────────────────────────────────────────────────────────

    public function test_can_list_notes(): void
    {
        Note::factory()->count(3)->create();

        $this->getJson('/api/notes')
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data'  => [['id', 'title', 'body', 'file_url', 'created_at', 'updated_at']],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta'  => ['total', 'per_page', 'current_page', 'last_page'],
            ]);
    }

    public function test_list_returns_empty_when_no_notes(): void
    {
        $this->getJson('/api/notes')
            ->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0);
    }

    public function test_can_search_notes_by_title(): void
    {
        Note::factory()->create(['title' => 'Meeting agenda']);
        Note::factory()->create(['title' => 'Shopping list']);

        $this->getJson('/api/notes?search=meeting')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Meeting agenda');
    }

    public function test_can_search_notes_by_body(): void
    {
        Note::factory()->create(['body' => 'Discussed Q4 targets']);
        Note::factory()->create(['body' => 'Buy milk and eggs']);

        $this->getJson('/api/notes?search=Q4')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    // ── Create ───────────────────────────────────────────────────────────────

    public function test_can_create_note(): void
    {
        $this->postJson('/api/notes', [
            'title' => 'Test note',
            'body'  => 'Test body',
        ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'Test note')
            ->assertJsonPath('data.body', 'Test body')
            ->assertJsonPath('data.file_url', null);

        $this->assertDatabaseHas('notes', ['title' => 'Test note']);
    }

    public function test_create_requires_title(): void
    {
        $this->postJson('/api/notes', ['body' => 'No title'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }

    public function test_create_requires_body(): void
    {
        $this->postJson('/api/notes', ['title' => 'No body'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['body']);
    }

    public function test_can_create_note_with_file(): void
    {
        Storage::fake('s3');

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->postJson('/api/notes', [
            'title' => 'Note with file',
            'body'  => 'Has attachment',
            'file'  => $file,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.title', 'Note with file');

        $this->assertNotNull($response->json('data.file_url'));
        Storage::disk('s3')->assertExists("notes/1/document.pdf");
    }

    public function test_create_rejects_invalid_file_type(): void
    {
        Storage::fake('s3');

        $file = UploadedFile::fake()->create('script.exe', 100, 'application/octet-stream');

        $this->postJson('/api/notes', [
            'title' => 'Bad file',
            'body'  => 'Has bad attachment',
            'file'  => $file,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file']);
    }

    public function test_create_rejects_file_over_5mb(): void
    {
        Storage::fake('s3');

        $file = UploadedFile::fake()->create('big.pdf', 6000, 'application/pdf');

        $this->postJson('/api/notes', [
            'title' => 'Big file',
            'body'  => 'Too large',
            'file'  => $file,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file']);
    }

    // ── Show ─────────────────────────────────────────────────────────────────

    public function test_can_get_single_note(): void
    {
        $note = Note::factory()->create();

        $this->getJson("/api/notes/{$note->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $note->id)
            ->assertJsonPath('data.title', $note->title);
    }

    public function test_returns_404_for_missing_note(): void
    {
        $this->getJson('/api/notes/999')->assertNotFound();
    }

    // ── Update ───────────────────────────────────────────────────────────────

    public function test_can_update_note_title(): void
    {
        $note = Note::factory()->create(['title' => 'Old title']);

        $this->putJson("/api/notes/{$note->id}", ['title' => 'New title'])
            ->assertOk()
            ->assertJsonPath('data.title', 'New title');

        $this->assertDatabaseHas('notes', ['id' => $note->id, 'title' => 'New title']);
    }

    public function test_can_update_note_with_new_file(): void
    {
        Storage::fake('s3');

        $note = Note::factory()->create(['file_url' => null]);

        $file = UploadedFile::fake()->image('photo.jpg');

        $this->putJson("/api/notes/{$note->id}", ['file' => $file])
            ->assertOk();

        $this->assertNotNull(Note::find($note->id)->file_url);
    }

    // ── Delete (soft) ────────────────────────────────────────────────────────

    public function test_can_soft_delete_note(): void
    {
        $note = Note::factory()->create();

        $this->deleteJson("/api/notes/{$note->id}")->assertNoContent();

        $this->assertSoftDeleted('notes', ['id' => $note->id]);
    }

    public function test_soft_deleted_note_excluded_from_list(): void
    {
        $note = Note::factory()->create();
        $note->delete();

        $this->getJson('/api/notes')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    // ── Restore ──────────────────────────────────────────────────────────────

    public function test_can_restore_soft_deleted_note(): void
    {
        $note = Note::factory()->create();
        $note->delete();

        $this->postJson("/api/notes/{$note->id}/restore")
            ->assertOk()
            ->assertJsonPath('data.deleted_at', null);

        $this->assertDatabaseHas('notes', ['id' => $note->id, 'deleted_at' => null]);
    }

    // ── Force delete ─────────────────────────────────────────────────────────

    public function test_can_force_delete_note(): void
    {
        Storage::fake('s3');

        $note = Note::factory()->create(['file_url' => null]);
        $note->delete();

        $this->deleteJson("/api/notes/{$note->id}/force")->assertNoContent();

        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

    // ── File upload ──────────────────────────────────────────────────────────

    public function test_can_upload_file_to_existing_note(): void
    {
        Storage::fake('s3');

        $note = Note::factory()->create(['file_url' => null]);
        $file = UploadedFile::fake()->create('report.pdf', 500, 'application/pdf');

        $this->postJson("/api/notes/{$note->id}/file", ['file' => $file])
            ->assertOk()
            ->assertJsonPath('data.id', $note->id);

        $this->assertNotNull(Note::find($note->id)->file_url);
        Storage::disk('s3')->assertExists("notes/{$note->id}/report.pdf");
    }

    public function test_upload_file_requires_file_field(): void
    {
        $note = Note::factory()->create();

        $this->postJson("/api/notes/{$note->id}/file", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['file']);
    }
}
