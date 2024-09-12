document.addEventListener('DOMContentLoaded', function () {

    console.log("hello word");



})

function confirm_logout() {
    return confirm('are you sure?');
}

function myFunction() {
    console.log("inside hamburger click function");
    var x = document.getElementById("main-nav");
    if (x.className === "main-nav") {
        console.log("class name is main-nav");
        x.className += " responsive";
    } else {
        console.log("class name is not main-nav");
        x.className = "main-nav";
    }
}