<?php

namespace App\Http\Controllers;

use App\Models\CoverPage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CoverPageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'image' => 'nullable|image|max:2048'
        ]);

        // 👉 Find existing record (if any)
        $cover = CoverPage::where('program_id', $request->program_id)->first();

        $path = null;

        // 👉 If a new image is uploaded
        if ($request->hasFile('image')) {

            $request->validate([
                'image' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);

            // delete old image
            if ($cover && $cover->image && Storage::disk('public')->exists($cover->image)) {
                Storage::disk('public')->delete($cover->image);
            }

            // store image in storage/app/public/cover_pages
            $path = $request->file('image')->store('cover_pages', 'public');

            // save path in DB
            CoverPage::updateOrCreate(
                ['program_id' => $request->program_id],
                ['image' => $path]
            );
        } else {
            // 👉 No new image sent (just update existing record logic if needed)

            if (!$cover) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image uploaded.'
                ], 400);
            }

            // Nothing to update (keeps existing image)
        }

        return response()->json([
            'success' => true,
            'image_url' => asset('storage/' . $path),
        ]);
    }

    public function destroy($id){
        $cover = CoverPage::findOrFail($id);

        // Delete image file
        // if ($cover->image) {
        //     Storage::disk('public')->delete($cover->image);
        // }
        if ($cover->image) {
            $path = public_path($cover->image);

            if (file_exists($path)) {
                unlink($path);
            }
        }

        // Delete DB record
        $cover->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully'
        ]);
    }

    
}
