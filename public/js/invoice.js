import { calculatorTotal, resetData, getDateFr, checkInputItem, getBtnDel, isEmpty, getFormData, articleToJson } from './modules/array.js';
let customerSelected = document.getElementById("customer-js");
let customer_info_facture = document.getElementById("customer-info-facture");
let addButton = document.getElementById("addButton");
let pannier_container = document.getElementById("panier-container-js");
let show_total = document.getElementById("show_total");
let html = "";
let pannier = [];

let btn_js = '';
let pannier_html = "";
let invoice_date_show = document.getElementById("invoice-date-show");
let invoice_date = document.getElementById("invoice-date-js");

invoice_date.valueAsdate = new Date();
const submit_button = document.getElementById("button-submit-js");

//mettre la date du jour
document.getElementById("invoice-date-js").valueAsDate = new Date();
document.getElementById("invoice_dateOperation").valueAsDate = new Date();

invoice_date_show.innerHTML = getDateFr(new Date());

invoice_date.addEventListener(
    'change', () => {

        const date = new Date(document.getElementById("invoice-date-js").value);

        invoice_date_show.innerHTML = getDateFr(date);
    }
)
function shwo_total() {
    //on cree la structure du total
    let view = `<tr>
					<td>Total</td>
					<td>122</td>
				</tr>
				<tr>
					<td>Total HT</td>
					<td>100</td>
				</tr>
    
    `
}

async function postData() {
    const formData = getFormData();
    console.log(formData.adress);

    const url = "/api/invoice/post";
    await fetch(url, {
        method: 'POST',
        body: JSON.stringify({
            user: 1,
            company: { company: "Asia transport prive" },
            customer: {
                lastName: formData.lastName,
                firstName: formData.firstName,
                company: formData.company,
                adress: formData.adress,
                city: formData.city,
                zipCode: formData.zipCode,
                country: formData.country
            },
            product: {
                pannier
            },
            total: calculatorTotal(pannier),
            bank: {
                rib: "FR76 1234 5678 1234 5678"
            },
        }),
        headers: {
            'Content-type': 'application/json; charset=UTF-8',
        },
    }).then((data) => data.json())
        .then((json) => {
            if (json.status == 'ok') {
                console.log('la redirectio0n avec id de la facture');
                console.log(json[0].slug)

                window.location = `/gestion/facture/${json[0].id}/show`
            }
        })


}



//verifie si au moin un article est present
submit_button.addEventListener(
    'click',
    (e) => {
        if (pannier_container.innerHTML == "") {
            e.preventDefault();
            alert('Veuillez ajouter un article!');
        }
        //on verifie si tous les champs sont remplie
        if (isEmpty()) {
            e.preventDefault();
            //je doit passer ici une requette async qui save to base une copie de la facture
            postData();
        } else {
            e.preventDefault();
            alert('Veuillez remplir tous les champs');
        }

    }
)

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
    //maj total container show
    calculatorTotal(pannier);
}



customerSelected.addEventListener(
    'change',
    () => {
        const customerId = document.getElementById("customer-js").value

        if (customerId) {
            getData(customerId)
        } else {
            resetData()
        }

    }
)

async function getData(id) {

    const url = "/api/customer/" + id;

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status} `);
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
                'price': price,
                'tva': tva,
                'quantity': quantity,
                'total': (price * quantity) + (price * quantity / 100) * tva
            });
            console.log(pannier.length);
            console.log(pannier);
            //maj Total Array
            calculatorTotal(pannier);
            show_pannier();
        }


    }
)
function show_pannier() {
    articleToJson(pannier);
    for (let i = 0; i < pannier.length; i++) {
        pannier_html += `
        <tr class="pannier-item">
                    <td>${i}</td>
					<td>${pannier[i]['designation']}</td>
					<td>${pannier[i]['price']}</td>
					<td>${pannier[i]['quantity']}</td>
					<td>${pannier[i]['tva']}</td>
					<td>77</td>
					<td>
						<button class="btn-del-js" data-index="${i}">X</button>
					</td>
			</tr >
        `;
        btn_js = getBtnDel();
    }
    console.log(pannier_html);
    pannier_container.innerHTML = pannier_html;
    pannier_html = "";

    addLinkToBtn(btn_js);
}


