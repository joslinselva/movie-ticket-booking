@extends('layouts.master')

@section('content')

<style>
    .seat {
        display: inline-block;
        width: 50px;
        height: 50px;
        margin: 5px;
        text-align: center;
        line-height: 50px;
        border: 1px solid #000;
        cursor: pointer;
    }
    .available { background-color: white; color: black; }
    .selected { background-color: blue; color: white; }
    .booked { background-color: red; color: white; cursor: not-allowed; }
</style>

<div class="container mt-5">
    <h1>Book Tickets</h1>
    <div class="row">
    @foreach ($movies as $movie)
    <div class="col-md-4 mb-4">
        <div class="card mb-3">
            <div class="card-body">
                <h2>{{ $movie->title }}</h2>
                <p>Date: {{ $movie->show_date }} | Time: {{ $movie->show_time }}</p>
                <button class="btn btn-primary open-seats-modal" data-movie-id="{{ $movie->id }}">Book Seats</button>
            </div>
        </div>
    </div>
    @endforeach
    </div>
</div>

<!-- Seat Selection Modal -->
<div class="modal fade" id="seatModal" tabindex="-1" aria-labelledby="seatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="seatModalLabel">Select Your Seats</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="seats text-center" id="seatContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="confirmBooking">Confirm Booking</button>
            </div>
        </div>
    </div>
</div>

@endsection

