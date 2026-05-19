function cancelBooking(id){

    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){

            let response = JSON.parse(this.responseText);

            if(response.status == "success"){

                document.getElementById("status"+id).innerHTML = "Cancelled";
                document.getElementById("status"+id).className = "badge cancelled";
                document.getElementById("btn"+id).style.display = "none";

            }else{
                alert(response.message);
            }
        }
    };

    xhttp.open("POST", "../../api/bookings/cancel.php", true);
    xhttp.setRequestHeader("content-type","application/x-www-form-urlencoded");
    xhttp.send("booking_id="+id);
}
