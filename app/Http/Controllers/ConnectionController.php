<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function getConnection()
    {
        $userId = Auth::id();

        $connection = Connection::query()->where('user_id', $userId)->first();
        if (!$connection) {
            return response()->json(['error' => 'connection not found'], 404);
        }

        return response()->json(['data' => $connection], 200);
    }

    public function createDemoConnection()
    {
        $userId = Auth::id();

        $connection = Connection::query()->firstOrCreate(['user_id' => $userId]);

        if ($connection->demo_activated || $connection->is_demo) {
            return response()->json(['error' => 'demo already activated!'], 400);
        }

        $connection->is_demo = true;
        $connection->is_active = true;
        $connection->demo_activated = true;
        $connection->start_date = Carbon::now();
        $connection->end_date = Carbon::now()->addDays(3);
        $connection->save();

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
