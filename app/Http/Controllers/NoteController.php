<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Requests\UploadNoteFileRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

/**
 * @group Notes
 *
 * Manage notes and their file attachments.
 */
class NoteController extends Controller
{
    /**
     * List notes
     *
     * Returns a paginated list of all notes. Use the `search` query parameter to filter by title or body.
     *
     * @queryParam search string Filter notes by title or body. Example: meeting
     * @queryParam page int Page number (default: 1). Example: 1
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Meeting notes",
     *       "body": "Discussed Q4 targets.",
     *       "file_url": null,
     *       "deleted_at": null,
     *       "created_at": "2026-09-30T06:00:00.000000Z",
     *       "updated_at": "2026-09-30T06:00:00.000000Z"
     *     }
     *   ],
     *   "meta": {
     *     "total": 1,
     *     "per_page": 15,
     *     "current_page": 1,
     *     "last_page": 1
     *   }
     * }
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $notes = Note::search($request->query('search'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return NoteResource::collection($notes)
            ->additional([
                'meta' => [
                    'total'        => $notes->total(),
                    'per_page'     => $notes->perPage(),
                    'current_page' => $notes->currentPage(),
                    'last_page'    => $notes->lastPage(),
                ],
            ]);
    }

    /**
     * Create note
     *
     * Creates a new note. Optionally attach a file (jpg, png, or pdf, max 5 MB) which will be stored on S3.
     *
     * @response 201 {
     *   "data": {
     *     "id": 1,
     *     "title": "Meeting notes",
     *     "body": "Discussed Q4 targets.",
     *     "file_url": "https://haseeb-notes-files.s3.us-east-1.amazonaws.com/notes/1/agenda.pdf",
     *     "deleted_at": null,
     *     "created_at": "2026-09-30T06:00:00.000000Z",
     *     "updated_at": "2026-09-30T06:00:00.000000Z"
     *   }
     * }
     */
    public function store(StoreNoteRequest $request): NoteResource
    {
        $note = Note::create($request->only('title', 'body'));

        if ($request->hasFile('file')) {
            $note->file_url = $this->storeFile($request->file('file'), $note->id);
            $note->save();
        }

        return (new NoteResource($note))->response()->setStatusCode(201);
    }

    /**
     * Get note
     *
     * Returns a single note by ID.
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Meeting notes",
     *     "body": "Discussed Q4 targets.",
     *     "file_url": null,
     *     "deleted_at": null,
     *     "created_at": "2026-09-30T06:00:00.000000Z",
     *     "updated_at": "2026-09-30T06:00:00.000000Z"
     *   }
     * }
     * @response 404 {"message": "No query results for model [App\\Models\\Note]."}
     */
    public function show(Note $note): NoteResource
    {
        return new NoteResource($note);
    }

    /**
     * Update note
     *
     * Updates a note's title, body, or file. All fields are optional — only send what you want to change.
     * If a new file is uploaded, the old S3 file is deleted automatically.
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Updated title",
     *     "body": "Updated body.",
     *     "file_url": null,
     *     "deleted_at": null,
     *     "created_at": "2026-09-30T06:00:00.000000Z",
     *     "updated_at": "2026-09-30T07:00:00.000000Z"
     *   }
     * }
     */
    public function update(UpdateNoteRequest $request, Note $note): NoteResource
    {
        $note->fill($request->only('title', 'body'));

        if ($request->hasFile('file')) {
            $this->deleteS3File($note->file_url);
            $note->file_url = $this->storeFile($request->file('file'), $note->id);
        }

        $note->save();

        return new NoteResource($note);
    }

    /**
     * Delete note
     *
     * Soft-deletes a note. The note is hidden from the list but can be restored.
     * To permanently delete, use the force-delete endpoint.
     *
     * @response 204 {}
     */
    public function destroy(Note $note): JsonResponse
    {
        $note->delete();

        return response()->json(null, 204);
    }

    /**
     * Restore note
     *
     * Restores a previously soft-deleted note.
     *
     * @urlParam id integer required The ID of the trashed note. Example: 1
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Meeting notes",
     *     "body": "Discussed Q4 targets.",
     *     "file_url": null,
     *     "deleted_at": null,
     *     "created_at": "2026-09-30T06:00:00.000000Z",
     *     "updated_at": "2026-09-30T08:00:00.000000Z"
     *   }
     * }
     */
    public function restore(int $id): NoteResource
    {
        $note = Note::onlyTrashed()->findOrFail($id);
        $note->restore();

        return new NoteResource($note);
    }

    /**
     * Force-delete note
     *
     * Permanently deletes a soft-deleted note and removes its S3 file (if any). This action cannot be undone.
     *
     * @urlParam id integer required The ID of the trashed note. Example: 1
     *
     * @response 204 {}
     */
    public function forceDelete(int $id): JsonResponse
    {
        $note = Note::onlyTrashed()->findOrFail($id);
        $this->deleteS3File($note->file_url);
        $note->forceDelete();

        return response()->json(null, 204);
    }

    /**
     * Upload file
     *
     * Uploads or replaces the file attachment for a note. Accepted types: jpg, png, pdf. Max size: 5 MB.
     * The file is stored on S3 at `notes/{id}/{filename}` and the URL is saved on the note.
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "title": "Meeting notes",
     *     "body": "Discussed Q4 targets.",
     *     "file_url": "https://haseeb-notes-files.s3.us-east-1.amazonaws.com/notes/1/agenda.pdf",
     *     "deleted_at": null,
     *     "created_at": "2026-09-30T06:00:00.000000Z",
     *     "updated_at": "2026-09-30T09:00:00.000000Z"
     *   }
     * }
     */
    public function uploadFile(UploadNoteFileRequest $request, Note $note): NoteResource
    {
        $this->deleteS3File($note->file_url);
        $note->file_url = $this->storeFile($request->file('file'), $note->id);
        $note->save();

        return new NoteResource($note);
    }

    private function storeFile(\Illuminate\Http\UploadedFile $file, int $noteId): string
    {
        $path = $file->storeAs(
            "notes/{$noteId}",
            $file->getClientOriginalName(),
            's3'
        );

        return Storage::disk('s3')->url($path);
    }

    private function deleteS3File(?string $url): void
    {
        if (!$url) {
            return;
        }

        $parsed = parse_url($url);
        $path   = ltrim($parsed['path'] ?? '', '/');

        Storage::disk('s3')->delete($path);
    }
}
