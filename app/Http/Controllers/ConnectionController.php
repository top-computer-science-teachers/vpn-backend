<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Services\MarzbanService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    protected MarzbanService $marzban;
    public function __construct(MarzbanService $marzban)
    {
        $this->marzban = $marzban;
    }

    public function getConnection()
    {
        $userId = Auth::id();

        $connection = Connection::query()->where('user_id', $userId)->first();
        if (!$connection) {
            return response()->json(['error' => 'connection not found'], 404);
        }

        return response()->json(['data' => $connection], 200);
    }

    public function createDemoConnection(Request $request)
    {
        $user = Auth::user();

        $input = $request->validate([
            'months' => 'required|integer|min:1',
            'is_demo' => 'required|boolean',
        ]);

        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJhZG1pbiIsImFjY2VzcyI6InN1ZG8iLCJpYXQiOjE3NDMwNDUzNTksImV4cCI6MTc0MzEzMTc1OX0.GPKUGrW_tJz3PIIUXrHqdSqvkstpo-1hQHl00ldIm1o';

        $existConnection = Connection::query()->where('user_id', $user->id)->first();

        if ($existConnection) {
            return response()->json(['error' => 'already connected'], 409);
        }

        $marzban = $this->marzban->createUser($user, $input, $token);

        if (!$marzban['success']) {
            return response()->json(['error' => $marzban['error']], 500);
        }

        $connection = Connection::query()->updateOrCreate(['user_id' => $user->id], [
            'is_demo' => true,
            'is_active' => true,
            'demo_activated' => true,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(3),
        ]);

        return response()->json(['message' => 'demo activated!'], 200);
    }

    public function createConnection(Request $request)
    {
        $userId = Auth::id();

        $input = $request->validate([
            'type' => 'required|string',
        ]);

        $connection = Connection::query()->firstOrCreate(['user_id' => $userId]);

        $connection->is_demo = false;
        $connection->is_active = true;
        $connection->type = $input['type'];
        $connection->start_date = Carbon::now();
        $connection->end_date = Carbon::now()->addDays(3);
        $connection->save();

        return response()->json(['message' => 'connection created!'], 200);
    }

    public function cancelConnection()
    {
        $userId = Auth::id();

        $connection = Connection::query()->where('user_id', $userId)->first();
        if (!$connection) {
            return response()->json(['error' => 'connection not found'], 404);
        }

        $connection->is_active = false;
        $connection->end_date = Carbon::now();
        $connection->save();

        return response()->json(['message' => 'connection cancelled!'], 200);
    }
}
