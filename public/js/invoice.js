console.log('facture js');
let customerSelected = document.getElementById("customer-js");
let customer_info_facture = document.getElementById("customer-info-facture");
let addButton = document.getElementById("addButton");
let pannier_container = document.getElementById("panier-container-js");
let html = "";
let pannier = [];
let pannier_html = "";
let invoice_date_show = document.getElementById("invoice-date-show");
let invoice_date = document.getElementById("invoice-date-js");
let btn_js = '';
invoice_date.valueAsdate = new Date();

function getBtnDel() {
    return document.getElementsByClassName("btn-del-js");
}

function addLinkToBtn(btns_js) {
    for (let i = 0; i < btns_js.length; i++) {
        btns_js[i].addEventListener(
            'click',
            () => {
                //supprime element index
                delItem(i);

            }
        )
    }
}
//function supprime article avec la array methode splice 
function delItem(index) {
    //supprime element index
    pannier.splice(index, 1);
    //mettre a jour l'affichage
    show_pannier();
}

function getDateFr(date) {
    return date.toLocaleDateString("fr-FR");
}

function resetData() {
    //on efface le formulaire
    document.getElementById('invoice_firstName').value = "";
    document.getElementById('invoice_lastName').value = "";
    document.getElementById('invoice_adress').value = "";
    document.getElementById('invoice_companyName').value = "";

}

invoice_date_show.innerHTML = getDateFr(new Date());

invoice_date.addEventListener(
    'change', () => {

        const date = new Date(document.getElementById("invoice-date-js").value);

        invoice_date_show.innerHTML = getDateFr(date);
    }
)

customerSelected.addEventListener(
    'change',
    () => {
        const customerId = document.getElementById("customer-js").value
        console.log("selction change");
        console.log(customerId);
        if (customerId) {
            getData(customerId)
        } else {
            resetData()
        }

    }
)

async function getData(id) {

    const url = "https://127.0.0.1:8000/api/customer/" + id;



    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const customer = await response.json();
        console.log(customer);

        // invoice_firstName
        // invoice_lastName
        // invoice_adress
        //invoice_companyName
        // invoice_city
        // invoice_zipCode
        // invoice_country
        document.getElementById('invoice_firstName').value = customer.name;
        document.getElementById('invoice_lastName').value = customer.firstName;
        document.getElementById('invoice_adress').value = customer.adress;
        document.getElementById('invoice_city').value = customer.city;
        document.getElementById('invoice_zipCode').value = customer.zipCode;
        document.getElementById('invoice_country').value = customer.country;
        document.getElementById('invoice_companyName').value = customer.compagny;


        html += `Client: 
        <ul>
                            <li>${customer.name} ${customer.firstName}</li>
                            <li>${customer.compagny}</li>
                            <li>${customer.adress}</li>
                            <li>${customer.zipCode} ${customer.city}</li>
                            <li>${customer.country}</li>
                        </ul>`


        customer_info_facture.innerHTML = html;
        html = "";


    } catch (error) {
        console.error(error.message);
    }

}
//function verifie si les entree sont bien remplis et les donnees correspondent aux attente
function checkInputItem(description, price, quantity, tva) {
    if (description && price && quantity && tva) {
        if (isNaN(price)) {
            alert("Tarif doit d'etre numerique");
            return false
        }
        if (isNaN(quantity)) {
            alert("Quantite doit d'etre numerique");
            return false
        }
        if (isNaN(tva)) {
            alert("tva doit d'etre numerique");
            return false
        }

        return true;
    } else {
        alert('Veuillez remplir tous les champs');
        return false;
    }
}

addButton.addEventListener(
    'click', () => {
        //on recupere l'article 
        const description = document.getElementById("item-description").value;
        const price = document.getElementById("item-price").value;
        const quantity = document.getElementById("item-quantity").value;
        const tva = document.getElementById("item-tva").value;
        //verification les entrees input
        if (checkInputItem(description, price, quantity, tva)) {

            pannier.push({
                'designation': description,
                'Prix': price,
                'tva': tva,
                'quantity': quantity,
                'total': 77
            });
            console.log(pannier.length);
            console.log(pannier);
            show_pannier();
        }


    }
)
function show_pannier() {

    for (i = 0; i < pannier.length; i++) {
        pannier_html += `
            <tr class="pannier-item">
                    <td>${i}</td>
					<td>${pannier[i]['designation']}</td>
					<td>70</td>
					<td>1</td>
					<td>10</td>
					<td>77</td>
					<td>
						<button class="btn-del-js" data-index="${i}">X</button>
					</td>
			</tr>
            `;
        btn_js = getBtnDel();
    }
    console.log(pannier_html);
    pannier_container.innerHTML = pannier_html;
    pannier_html = "";

    console.log(btn_js);
    addLinkToBtn(btn_js);
}


