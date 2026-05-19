function loadRooms(){

    let params = new URLSearchParams(window.location.search);

    let checkin = params.get("checkin");
    let checkout = params.get("checkout");
    let guests = params.get("guests");

    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){

            let rooms = JSON.parse(this.responseText);

            let output = "";

            if(rooms.length == 0){
                output = "<p class='empty-message'>No room available for selected dates.</p>";
            }

            for(let i=0; i<rooms.length; i++){

                let amenities = "";
                if(rooms[i].amenities){
                    amenities = rooms[i].amenities.join(", ");
                }

                output += `
                <div class='room-card'>
                    <img src='${rooms[i].thumbnail}' alt='Room Image'>
                    <div class='room-card-body'>
                        <h3>${rooms[i].name}</h3>
                        <p>${rooms[i].description}</p>
                        <p><strong>Amenities:</strong> ${amenities}</p>
                        <p><strong>Price/Night:</strong> ${rooms[i].price}</p>
                        <p><strong>Total:</strong> ${rooms[i].total_price}</p>
                        <a class='btn' href='bookingForm.php?room_type_id=${rooms[i].id}&checkin=${checkin}&checkout=${checkout}&total=${rooms[i].total_price}'>Book Now</a>
                    </div>
                </div>
                `;
            }

            document.getElementById("rooms").innerHTML = output;
        }
    };

    xhttp.open(
        "GET",
        "../../api/rooms/available.php?checkin="+checkin+"&checkout="+checkout+"&guests="+guests,
        true
    );

    xhttp.send();
}
