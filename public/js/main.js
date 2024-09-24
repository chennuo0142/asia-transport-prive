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

async function getData() {
    const url = "https://127.0.0.1:8000/api/driver";
    let showContainer = document.getElementById("showContainer");
    let html = '';

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const data = await response.json();
        console.log(data);
        // on boucle sur data
        data.forEach(element => {
            console.log(element.name);
            html += ` <tr>
                            <td>${element.name}</td>
                            <td>${element.firstName}</td>
                            <td>${element.email}</td>
                            <td>${element.telephone}</td>
                        </tr>`
        });
        showContainer.innerHTML = html;

    } catch (error) {
        console.error(error.message);
    }
}
