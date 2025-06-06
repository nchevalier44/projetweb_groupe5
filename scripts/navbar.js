//Logs
let login = "user"
let pwd = "groupe5"

document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    if(username == login && pwd == password){
        window.location.href = "../back";
    }

})