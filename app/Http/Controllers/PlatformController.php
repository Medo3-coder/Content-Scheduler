<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    //Retrieves all platforms with their activation status for the current user
    public function index(Request $request)
    {
        $user = $request->user();
        // Get all platforms and map them to include activation status for this user
        $platforms = Platform::all()->map(function ($platform) use ($user) {
            // Check if the platform is active for this user
            $isActive = $user->allPlatforms()->where('platform_id', $platform->id)->first()
            ?->pivot->active ?? false;

            return [
                'id'     => $platform->id,
                'name'   => $platform->name,
                'type'   => $platform->type,
                'active' => $isActive,
            ];
        });

        return response()->json($platforms);
    }

    //Toggles a platform's active status for the current user
    public function toggle(Request $request)
    {
        $request->validate([
            'platform_id' => 'required|exists:platforms,id',
            'active'      => 'required|boolean',
        ]);

        $user = $request->user();

        $user->allPlatforms()->syncWithoutDetaching([
            $request->platform_id => ['active' => $request->active],
        ]);

        return response()->json([
            'message' => 'Platform toggled successfully',
        ]);
    }
}
