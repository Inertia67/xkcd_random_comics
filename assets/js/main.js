window.onload = function() {
    const subscriberForm = document.querySelector("form[name=subscribe_form]");
    const subscribeButton = subscriberForm.querySelector("button[type=submit]");
	subscribeButton.onclick = (function(e) {
        e.preventDefault();
        var nameField = subscriberForm.querySelector("input[name=name]");
        var emailField = subscriberForm.querySelector("input[name=email]");
        const name = nameField.value.trim();
        const email =  emailField.value.trim();

        if(name == "") {
            alert("Please Enter Name");
        }else if(email == "" || !validateEmail(email)){
            alert("Please Enter Valid Email");
        }else{
            subscribeButton.disabled = true;
            nameField.disabled = true;
            emailField.disabled = true;
            document.querySelector(".greeting").style.display = "block";
            $data = {name, email};
            fetch('api/mail/send-comic.php', {
                method: 'post',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body:JSON.stringify($data)})
            .then(response => response.json())
            .then(data => {
                var message = '';
                if(data.status === 200){
                    document.querySelector(".name-tag").style.display = "none";
                    message = data.message
                }else{
                    subscribeButton.disabled = false;
                    nameField.disabled = false;
                    emailField.disabled = false;
                    message = 'Something went Wrong! Please try again after some time';
                }
                alert(message)
                document.querySelector(".greeting p").innerHTML = message;
            })
            .catch(e => {
                subscribeButton.disabled = false;
                nameField.disabled = false;
                emailField.disabled = false;
                alert("Something went Wrong! Please try again after some time")
            })
        }
	});

    function validateEmail(email) {
        var e = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return e.test(email);
    }
};