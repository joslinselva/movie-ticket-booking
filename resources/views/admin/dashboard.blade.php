@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <h2>Admin Dashboard</h2>

    <h3 class="mt-4">Overview</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Users</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalUsers }}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Tickets Booked</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalBookings }}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Seats for each show</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalSeats }}</h5>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-4">Show List</h3>
    @foreach ($movies as $movie)
        <div class="card mt-3">
            <div class="card-header">
                <h4>{{ $movie['title'] }} ({{ $movie['show_date'] }} - {{ $movie['show_time'] }})</h4>
            </div>
            <div class="card-body">
                @if (count($movie['user_bookings']) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Seats Booked (Total : {{ $movie['booked_seats'] }}, Available : {{ $movie['available_seats'] }})</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movie['user_bookings'] as $user)
                                <tr>
                                    <td>{{ $user['user_name'] }}</td>
                                    <td>{{ implode(', ', $user['seats']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No bookings yet for this movie.</p>
                @endif
            </div>
        </div>
    @endforeach

    <!-- Table: Available Seats per Show -->
    <h3 class="mt-4">Available Seats per Show</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Movie</th>
                <th>Show Date</th>
                <th>Show Time</th>
                <th>Total Seats</th>
                <th>Booked Seats</th>
                <th>Available Seats</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movies as $movie)
                <tr>
                    <td>{{ $movie['title'] }}</td>
                    <td>{{ $movie['show_date'] }}</td>
                    <td>{{ $movie['show_time'] }}</td>
                    <td>{{ $movie['total_seats'] }}</td>
                    <td>{{ $movie['booked_seats'] }}</td>
                    <td>{{ $movie['available_seats'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
