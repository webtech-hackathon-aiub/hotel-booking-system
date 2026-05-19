function checkInBooking(id){

    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){

            let response = JSON.parse(this.responseText);

            if(response.status == "success"){

                document.getElementById("bookingStatus").innerHTML = response.new_status;
                document.getElementById("actionArea").innerHTML = "<button class='btn' onclick='checkOutBooking("+id+")'>Check Out</button>";
                document.getElementById("message").innerHTML = "Checked in successfully";
                document.getElementById("message").className = "success";

            }else{

                document.getElementById("message").innerHTML = response.message;
                document.getElementById("message").className = "error";
            }
        }
    };

    xhttp.open("POST", "../../api/bookings/checkin.php", true);
    xhttp.setRequestHeader("content-type", "application/x-www-form-urlencoded");
    xhttp.send("booking_id="+id);
}

function checkOutBooking(id){

    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){

            let response = JSON.parse(this.responseText);

            if(response.status == "success"){
                location.reload();
            }else{
                alert(response.message);
            }
        }
    };

    xhttp.open("POST", "../../api/bookings/checkout.php", true);
    xhttp.setRequestHeader("content-type", "application/x-www-form-urlencoded");
    xhttp.send("booking_id=" + id);
}


function updateBookingStatus(id, status){

    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){
            let response = JSON.parse(this.responseText);

            if(response.status == "success"){
                location.reload();
            }else{
                alert("Status not updated");
            }
        }
    };

    xhttp.open("POST", "../../api/bookings/updateStatus.php", true);
    xhttp.setRequestHeader("content-type", "application/x-www-form-urlencoded");
    xhttp.send("booking_id=" + id + "&status=" + status);
}

    xhttp.open("POST", "../../api/bookings/updateStatus.php", true);

    xhttp.setRequestHeader(
        "content-type",
        "application/x-www-form-urlencoded"
    );

    xhttp.send(
        "booking_id=" + id + "&status=" + status
    );
}
