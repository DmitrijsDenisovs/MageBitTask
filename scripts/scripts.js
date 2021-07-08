function validateInput(){
    if(document.getElementById("terms").checked == false){
        document.getElementById("error-message").style.display = "inline";
        document.getElementById("error-message").innerHTML = "You must accept the terms and conditions";
        return false;
    }
    else{
        var inputString = document.getElementById("input").value;
        if(inputString.length == 0){
            document.getElementById("error-message").style.display = "inline";
            document.getElementById("error-message").innerHTML = "Email address is required";
            return false;
        }
        else{ 
            var emailRegEx = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            if(emailRegEx.test(inputString) == false){
                document.getElementById("error-message").style.display = "inline";
                document.getElementById("error-message").innerHTML = "Please provide a valid e-mail address";
                return false;
            }
            else{
                if(inputString.endsWith("co") == true){
                    document.getElementById("error-message").style.display = "inline";
                    document.getElementById("error-message").innerHTML = "We are not accepting subscriptions from Colombia emails";
                    return false;
                }
                else{
                    /*document.getElementById("error-message").style.display = "none";
                    document.getElementById("error-message").innerHTML = "";

                    document.getElementById("success").style.display = "inline";

                    document.getElementById("heading").innerHTML = "Thanks for subscribing!";

                    document.getElementById("text").innerHTML = "You have successfully subscribed to our email listing. Check your email for the discount code.";

                    document.getElementById("form").style.display = "none";*/
                    successMessageOutput();
                    document.getElementById("form").submit();
                    return true;
                }
            }
        }
    }
}
function checkFormAndJs(){
    $("#form").validate();
    if($("#form").valid() == false)
        document.getElementById("button").disabled = true;
    document.cookie = "verification=true; path=/";
}

function successMessageOutput(){
    document.getElementById("error-message").style.display = "none";
    document.getElementById("error-message").innerHTML = "";

    document.getElementById("success").style.display = "inline";

    document.getElementById("heading").innerHTML = "Thanks for subscribing!";

    document.getElementById("text").innerHTML = "You have successfully subscribed to our email listing. Check your email for the discount code.";

    document.getElementById("form").style.display = "none";
}