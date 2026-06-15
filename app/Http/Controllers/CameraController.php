<?php
 // app/Http/Controllers/CameraController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;

class CameraController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user(); // or Employee guard if different
        // Validate
        $data = $request->validate([
            'consented' => 'required|boolean',
            'available' => 'required|boolean',
            'snapshot'  => 'nullable|string' // base64 data url
        ]);

        $user->camera_consented = $data['consented'];
        $user->camera_available = $data['available'];

        if ($data['consented'] && $data['available'] && $data['snapshot']) {
            // snapshot comes as "data:image/png;base64,AAA..."
            $snapshot = $data['snapshot'];
            if (preg_match('/^data:image\/(\w+);base64,/', $snapshot, $type)) {
                $imageType = $type[1]; // png, jpeg...
                $snapshot = substr($snapshot, strpos($snapshot, ',') + 1);
                $snapshot = base64_decode($snapshot);
                if ($snapshot === false) {
                    return response()->json(['error' => 'invalid_image'], 422);
                }
                $filename = 'camera_snapshots/' . now()->format('Ymd') . '/' . Str::random(12) . '.' . $imageType;
                Storage::put($filename, $snapshot);
                // Optionally make it private and serve with controller; here using public disk
                $user->camera_snapshot_path = $filename;
            } else {
                return response()->json(['error' => 'invalid_data_url'], 422);
            }
        }

        $user->save();

        return response()->json(['ok' => true]);
    }
}
