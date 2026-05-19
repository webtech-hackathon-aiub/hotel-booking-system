function validateRoomType(){

    let name = document.getElementById("name").value;
    let price = document.getElementById("price").value;
    let capacity = document.getElementById("capacity").value;

    if(name == "" || price == "" || capacity == ""){

        document.getElementById("error").innerHTML =
        "Name, price and capacity are required";

        return false;
    }

    if(price <= 0){

        document.getElementById("error").innerHTML =
        "Price must be greater than 0";

        return false;
    }

    if(capacity <= 0){

        document.getElementById("error").innerHTML =
        "Capacity must be greater than 0";

        return false;
    }

    return true;
}