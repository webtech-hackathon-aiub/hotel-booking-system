function toggleRoomStatus(id) {

  let xhttp = new XMLHttpRequest();

  xhttp.onreadystatechange = function () {

    if (this.readyState == 4 && this.status == 200) {

      let btn = document.getElementById("statusBtn" + id);
      let msg = document.getElementById("statusResponse" + id);

      if(this.responseText == "booked"){

        msg.innerHTML = " Booked room status cannot be changed";
        msg.style.color = "red";
        return;
      }

      btn.innerHTML = this.responseText;

      if(this.responseText == "available"){
        btn.className = "badge available";
      }else{
        btn.className = "badge maintenance";
      }

      msg.innerHTML = " Updated";
      msg.style.color = "green";
    }
  };

  xhttp.open("POST", "../../api/rooms/toggleStatus.php", true);
  xhttp.setRequestHeader("content-type", "application/x-www-form-urlencoded");
  xhttp.send("id=" + id);
}
