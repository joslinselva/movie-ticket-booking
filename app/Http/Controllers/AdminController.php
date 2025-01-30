<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Seat;
use App\Models\Movie;

class AdminController extends Controller
{
    public function dashboard()
    {
        //exclude admin
        $totalUsers = User::where('is_admin', 0)->count();

        // Count only bookings made by normal users
        $totalBookings = Booking::whereHas('user', function ($query) {
            $query->where('is_admin', 0);
        })->count();

        $totalSeats = Seat::count();

        $bookings = Booking::with(['user', 'movie', 'seat'])
            ->whereHas('user', function ($query) {
                $query->where('is_admin', 0);
            })
            ->get();

        // Get available seats for each movie
        $movies = Movie::with(['bookings.user', 'bookings.seat'])->get()->map(function ($movie) use ($totalSeats) { 
            // Get booked seats by non-admin users
            $bookedSeats = Booking::where('movie_id', $movie->id)
                ->whereHas('user', function ($query) {
                    $query->where('is_admin', 0);
                })
                ->count();

            $availableSeats = $totalSeats - $bookedSeats;

            // Group users and their booked seats
            $userBookings = [];
            foreach ($movie->bookings as $booking) {
                if ($booking->user->is_admin == 1) continue; // Skip admin users

                $userId = $booking->user->id;
                $userName = $booking->user->name;

                if (!isset($userBookings[$userId])) {
                    $userBookings[$userId] = [
                        'user_id' => $userId,
                        'user_name' => $userName,
                        'seats' => []
                    ];
                }

                $userBookings[$userId]['seats'][] = $booking->seat->seat_number;
            }

            return [
                'id' => $movie->id,
                'title' => $movie->title,
                'show_date' => $movie->show_date,
                'show_time' => $movie->show_time,
                'total_seats' => $totalSeats,
                'booked_seats' => $bookedSeats,
                'available_seats' => $availableSeats,
                'user_bookings' => array_values($userBookings),
            ];
        });

        return view('admin.dashboard', compact('totalUsers', 'totalBookings', 'totalSeats', 'bookings', 'movies'));
    }

}
