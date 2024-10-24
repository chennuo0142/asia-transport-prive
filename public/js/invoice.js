import { calculatorTotal, resetData, getDateFr, checkInputItem, isEmpty, getFormData, articleToJson, getData } from './modules/array.js';
let customerSelected = document.getElementById("customer-js");
// let customer_info_facture = document.getElementById("customer-info-facture");
let addButton = document.getElementById("addButton");
let pannier_container = document.getElementById("panier-container-js");
let show_total = document.getElementById("show_total");
let userArticle = document.getElementById("userArticles-js");

let priceIsTtc = false;
// let html = "";
let pannier = [];

// let btn_js = '';
let pannier_html = "";
let invoice_date_show = document.getElementById("invoice-date-show");
let invoice_date = document.getElementById("invoice-date-js");

invoice_date.valueAsdate = new Date();
const submit_button = document.getElementById("button-submit-js");

// changement sur le prix en ttc ou ht
document.getElementById("switchTTC-js").addEventListener(
    'click', (e) => {
        e.preventDefault();
        if (priceIsTtc == false) {
            priceIsTtc = true;
            document.getElementById('show-isTtc-js').innerHTML = ` Le tarif est en TTC`;
            show_pannier();
            calculatorTotal(pannier, priceIsTtc)
        } else {
            priceIsTtc = false;
            document.getElementById('show-isTtc-js').innerHTML = ` Le tarif est en HT`;
            show_pannier();
            calculatorTotal(pannier, priceIsTtc)
        }
    }
)

//article selectionner, remplir les champs articles
userArticle.addEventListener(
    'change', () => {
        getArticle(userArticle.value);
    }
)
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

async function getArticle(id) {
    const url = '/api/article/' + id;

    await fetch(url, {
        method: 'GET',
        headers: {
            'Content-type': 'application/json; charset=UTF-8',
        }
    }).then((response) => {
        return response.json()
    }).then((data) => {
        //insertion dans le formulaire
        document.getElementById("item-description").value = data.name
        document.getElementById("item-price").value = data.price
        document.getElementById("item-tva").value = data.tva
        document.getElementById("item-quantity").value = 1
        // set focus sur quantity
        document.getElementById("item-quantity").focus();
    })
}

async function postData() {
    const formData = getFormData();

    const timeOperation = document.getElementById("invoice_timeOperation").value;
    console.log(timeOperation);

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
            timeOperation: timeOperation,
            dateOperation: document.getElementById("invoice_dateOperation").value,
            invoiceDate: document.getElementById("invoice-date-js").value,
            product: pannier,
            total: calculatorTotal(pannier, priceIsTtc),
            bank: {
                rib: "FR76 1234 5678 1234 5678"
            },

            articlePriceTtc: priceIsTtc
        }),
        headers: {
            'Content-type': 'application/json; charset=UTF-8',
        },
    }).then((data) => data.json())
        .then((json) => {
            if (json.status == 'ok') {
                console.log('la redirectio0n avec id de la facture');
                console.log(json)

                window.location = `/gestion/facture/show/${json[0].slug}`
            }
        })
}

//verifie si au moin un article est present
submit_button.addEventListener(
    'click',
    (e) => {

        if (pannier.length == 0) {
            e.preventDefault();
            //stop execution
            return alert('Veuillez ajouter un article!');
        }
        //on verifie si tous les champs sont remplie
        if (isEmpty()) {
            e.preventDefault();
            //je doit passer ici une requette async qui save to base une copie de la facture
            postData();
        }
        // else {
        //     e.preventDefault();
        //     alert('Veuillez remplir tous les champs');
        // }

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
    calculatorTotal(pannier, priceIsTtc);
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
                // //total sur prix en ht
                'total': (price * quantity) + (price * quantity / 100) * tva,
                // //total sur prix en ttc, donc deduire la tva
                'totalOnTtc': price * quantity
            });
            console.log(pannier.length);
            console.log(pannier);
            //maj Total Array
            calculatorTotal(pannier, priceIsTtc);
            show_pannier();
        }


    }
)

function show_pannier() {
    //verifier si le tarif est en ttc ou ht
    //check la variable article price ttc
    console.log(priceIsTtc);
    articleToJson(pannier);
    for (let i = 0; i < pannier.length; i++) {
        if (priceIsTtc) {
            //affichage si price est ttc
            pannier_html += `
            <tr class="pannier-item">
                        <td>${i}</td>
                        <td>${pannier[i]['designation']}</td>
                        <td>${pannier[i]['price']}</td>
                        <td>${pannier[i]['quantity']}</td>
                        <td>${pannier[i]['tva']}</td>
                        <td>${pannier[i]['totalOnTtc']}</td>
                        <td>
                            <button class="btn-del-js" data-index="${i}">X</button>
                        </td>
                </tr >
            `;
        } else {
            pannier_html += `
            <tr class="pannier-item">
                        <td>${i}</td>
                        <td>${pannier[i]['designation']}</td>
                        <td>${pannier[i]['price']}</td>
                        <td>${pannier[i]['quantity']}</td>
                        <td>${pannier[i]['tva']}</td>
                        <td>${pannier[i]['total']}</td>
                        <td>
                            <button class="btn-del-js" data-index="${i}">X</button>
                        </td>
                </tr >
            `;
        }


    }

    pannier_container.innerHTML = pannier_html;
    pannier_html = "";

    addLinkToBtn(document.getElementsByClassName("btn-del-js"));
}

