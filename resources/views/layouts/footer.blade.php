
 <style>
html, body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
}

main {
    flex-grow: 1;
}

footer {
    margin-top: auto;
}
</style>
 
 <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Movie Ticketing System</p>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <script>
    $(document).ready(function() {
        let selectedSeats = [];
        let selectedMovieId = null;

        // Open Modal & Load Available Seats
        $('.open-seats-modal').click(function() {
            selectedMovieId = $(this).data('movie-id');
            selectedSeats = [];
            $('#seatContainer').html('<p>Loading seats...</p>');

            $.ajax({
                url: `/buy-tickets/${selectedMovieId}`,
                method: 'GET',
                success: function(response) {
                    let seatHTML = '';
                    response.seats.forEach(seat => {
                        let seatClass = seat.is_booked ? 'booked' : 'available';
                        seatHTML += `<div class="seat ${seatClass}" data-seat-id="${seat.id}">${seat.seat_number}</div>`;
                    });

                    $('#seatContainer').html(seatHTML);
                },
                error: function() {
                    $('#seatContainer').html('<p>Error loading seats.</p>');
                }
            });

            $('#seatModal').modal('show');
        });

        // Select or Deselect Seats
        $(document).on('click', '.seat.available', function() {
            let seatId = $(this).data('seat-id');
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected').css('background-color', 'white');
                selectedSeats = selectedSeats.filter(id => id !== seatId);
            } else {
                $(this).addClass('selected').css('background-color', 'blue');
                selectedSeats.push(seatId);
            }
        });

        // Confirm Booking via AJAX
        $('#confirmBooking').click(function() {
            if (selectedSeats.length === 0) {
                alert('Please choose at least one seat.');
                return;
            }

            $.ajax({
                url: '/buy-tickets/book-seat',
                method: 'POST',
                data: {
                    seat_ids: selectedSeats,
                    movie_id: selectedMovieId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Seats booked successfully!');
                        $('#seatModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Booking failed: ' + response.error);
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error'));
                }
            });
        });
    });
    </script>
</body>
</html>