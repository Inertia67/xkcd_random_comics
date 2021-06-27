// document.addEventListener("DOMContentLoaded", function(event) {
//     // Your code to run since DOM is loaded and ready
//     console.log('ghgh')
// });
window.onload = function() {
    const subscriberForm = document.querySelector("form[name=subscribe_form]");
    const subscribeButton = subscriberForm.querySelector("button[type=submit]");
	subscribeButton.onclick = (function(e) {
        e.preventDefault();
        var nameField = subscriberForm.querySelector("input[name=name]");
        var emailField = subscriberForm.querySelector("input[name=email]");
        const name = nameField.value.trim();
        const email =  emailField.value.trim();
        console.log('name', name, 'email', email)

		if(name && email) {
            subscribeButton.disabled = true;
            nameField.disabled = true;
            emailField.disabled = true;
		    // document.querySelector(".greeting span").innerHTML = name;
		    document.querySelector(".greeting").style.display = "block";
		}
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
            console.log(data)
            if(data.status === 200){
                document.querySelector(".name-tag").style.display = "none";
                message = data.message
                // message = 'Random Comic sent to your email, please check your inbox';
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
            console.log(e)
            alert("Something went Wrong! Please try again after some time")
        })
	});
};