<?php

namespace App\Http\Controllers;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\Booking;
use Illuminate\Http\Request;

class BuyTicketsController extends Controller
{
    public function index()
    {
        $movies = Movie::select('id', 'title', 'show_date', 'show_time')
                        ->orderBy('show_date')
                        ->orderBy('show_time')
                        ->get();
        
        return view('buy-tickets.index', compact('movies'));
    }

    public function getSeats($movieId)
    {
        $allSeats = Seat::all();

        $bookedSeats = Booking::where('movie_id', $movieId)
                            ->pluck('seat_id')
                            ->toArray();

        $seats = $allSeats->map(function ($seat) use ($bookedSeats) {
            return [
                'id' => $seat->id,
                'seat_number' => $seat->seat_number,
                'is_booked' => in_array($seat->id, $bookedSeats),
            ];
        });

        return response()->json([
            'success' => true,
            'seats' => $seats
        ]);
    }


    public function bookSeat(Request $request)
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
            'movie_id' => 'required|exists:movies,id',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'User not authenticated.']);
        }

        $seatIds = $request->seat_ids;
        $movieId = $request->movie_id;

        $alreadyBooked = Booking::where('movie_id', $movieId)
                                ->whereIn('seat_id', $seatIds)
                                ->exists();

        if ($alreadyBooked) {
            return response()->json(['success' => false, 'error' => 'Some seats are already booked!']);
        }

        foreach ($seatIds as $seatId) {
            Booking::create([
                'user_id' => $user->id,
                'movie_id' => $movieId,
                'seat_id' => $seatId,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Seats booked successfully!']);
    }

}
