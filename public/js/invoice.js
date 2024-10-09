console.log('facture js');
let customerSelected = document.getElementById("customer-js");
let customer_info_facture = document.getElementById("customer-info-facture");
let addButton = document.getElementById("addButton");
let html = "";
let pannier = [];
let invoice_date_show = document.getElementById("invoice-date-show");
let invoice_date = document.getElementById("invoice-date-js");
invoice_date.valueAsdate = new Date();

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
document.getElementById('')
let date_fr = getDateFr(new Date());

invoice_date_show.innerHTML = date_fr;

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

addButton.addEventListener(
    'click', () => {

        console.log('Add button clicked');
        //on recupere l'article 
        pannier.push({
            'designation': 'Transfert aeroport de paris',
            'Prix': 70,
            'tva': 10,
            'quantity': 10,
            'total': 77
        });
        console.log(pannier);
    }
)
