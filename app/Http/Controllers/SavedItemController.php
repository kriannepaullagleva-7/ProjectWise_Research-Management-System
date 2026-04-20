<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use App\Models\SavedItem;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SavedItemController extends Controller
{
    /**
     * Show saved items
     */
    public function index(): View
    {
        $saved = SavedItem::where('user_id', auth()->id())
            ->with('researchProject', 'researchProject.user')
            ->latest()
            ->paginate(12);

        return view('library.index', ['saved' => $saved]);
    }

    /**
     * Toggle save status for a project
     */
    public function toggle(ResearchProject $researchProject): RedirectResponse|JsonResponse
    {
        $userId = auth()->id();

        // Check if already saved
        $savedItem = SavedItem::where('user_id', $userId)
            ->where('research_project_id', $researchProject->id)
            ->first();

        if ($savedItem) {
            // Remove from saved
            $savedItem->delete();
            $message = 'Removed from library';
        } else {
            // Add to saved
            SavedItem::create([
                'user_id' => $userId,
                'research_project_id' => $researchProject->id,
            ]);
            $message = 'Added to library';
        }

        // Return JSON if AJAX request
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'saved' => !$savedItem,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
