<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ResourceSpeaker;
use Illuminate\Http\Request;

class ResourceSpeakerController extends Controller
{
    // ── Render the page ──────────────────────────────────────────
    public function page(Program $program)
    {
        return view('monitoring.resource-speaker.speaker', compact('program'));
    }

    // ── AJAX: list ───────────────────────────────────────────────
    public function index(Program $program)
    {
        $speakers = ResourceSpeaker::where('program_code', $program->program_code)
            ->orderBy('name')
            ->get();

        return response()->json([
            'speakers'    => $speakers,
            'has_batches' => $program->hasBatches(),
        ]);
    }

    // ── AJAX: store ──────────────────────────────────────────────
    public function store(Request $request, Program $program)
    {
        if (! $program->hasBatches()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot add a resource speaker. This program has no existing batch yet.',
            ], 422);
        }

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'position'     => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:50',
        ]);

        $speaker = ResourceSpeaker::create([
            'program_code' => $program->program_code,
            ...$validated,
        ]);

        return response()->json(['success' => true, 'message' => 'Resource speaker added.', 'speaker' => $speaker]);
    }

    // ── AJAX: show (for edit modal) ──────────────────────────────
    public function show(Program $program, ResourceSpeaker $resourceSpeaker)
    {
        return response()->json(['speaker' => $resourceSpeaker]);
    }

    // ── AJAX: update ─────────────────────────────────────────────
    public function update(Request $request, Program $program, ResourceSpeaker $resourceSpeaker)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'position'     => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:50',
        ]);

        $resourceSpeaker->update($validated);

        return response()->json(['success' => true, 'message' => 'Resource speaker updated.', 'speaker' => $resourceSpeaker->fresh()]);
    }

    // ── AJAX: destroy ────────────────────────────────────────────
    public function destroy(Program $program, ResourceSpeaker $resourceSpeaker)
    {
        $resourceSpeaker->delete();

        return response()->json(['success' => true, 'message' => 'Resource speaker deleted.']);
    }
}