export function resetData() {
    //on efface le formulaire
    document.getElementById('invoice_firstName').value = "";
    document.getElementById('invoice_lastName').value = "";
    document.getElementById('invoice_adress').value = "";
    document.getElementById('invoice_companyName').value = "";

}

export function getDateFr(date) {
    return date.toLocaleDateString("fr-FR");
}

export function isEmpty() {
    if (
        document.getElementById('invoice_firstName').value != "" &&
        document.getElementById('invoice_lastName').value != "" &&
        document.getElementById('invoice_adress').value != "" &&
        document.getElementById('invoice_city').value != "" &&
        document.getElementById('invoice_zipCode').value != "" &&
        document.getElementById('invoice_country').value != "" &&
        document.getElementById('invoice_companyName').value != "" &&
        document.getElementById('invoice_dateOperation').value != "" &&
        document.getElementById('invoice-date-js').value != ""
    ) {
        return true
    }

    return alert('Veuillez remplir tous les champs');
}
//function recupere les donnees du formulaire
export function getFormData() {
    const firstName = document.getElementById('invoice_firstName').value;
    const lastName = document.getElementById('invoice_lastName').value;
    const adress = document.getElementById('invoice_adress').value;
    const city = document.getElementById('invoice_city').value;
    const zipCode = document.getElementById('invoice_zipCode').value;
    const country = document.getElementById('invoice_country').value;
    const company = document.getElementById('invoice_companyName').value;
    const dateOperation = document.getElementById('invoice_dateOperation').value;
    const date = document.getElementById('invoice-date-js').value;

    return {
        firstName, lastName, adress, city, zipCode, country, company, dateOperation, date
    }

}

//function verifie si les entree sont bien remplis et les donnees correspondent aux attente
export function checkInputItem(description, price, quantity, tva) {
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

//function cree une chaine string json article
export function articleToJson(pannier) {
    console.log(pannier);
    let article_json = "";
    for (let i = 0; i < pannier.length; i++) {

        article_json += JSON.stringify({
            'designation': pannier[i].designation,
            'price': pannier[i].price,
            'quantity': pannier[i].quantity,
            'tva': pannier[i].tva,
            'total': pannier[i].total
        })

    }
    console.log(article_json);
    //injection dans dom, input hidden?? pk faire?
    document.getElementById("article-container").value = article_json;
}

export function calculatorTotal(pannier, priceIsTtc) {
    //initialise array total
    let total = { total: 0, total_ht: 0, total_ttc: 0, total_tva: 0, totalOnTtc: 0, htOnTtc: 0, tvaOnTtc: 0 }

    //on parcour le pannier
    for (let i = 0; i < pannier.length; i++) {
        let taux_tva = pannier[i].tva;
        let quantity = pannier[i].quantity;
        let price = pannier[i].price;

        let tva = (pannier[i].price * pannier[i].quantity) / 100 * pannier[i].tva;
        let ht = pannier[i].price * pannier[i].quantity;
        let ttc = tva + ht;

        // on calcule le total sur tarif en ttc
        let total_sur_tarif_ttc = pannier[i].price * pannier[i].quantity;

        //tva sur total_sur_tarif_ttc: total / (1+taux%)
        let total_ht_sur_tarif_ttc = total_sur_tarif_ttc / (1 + (pannier[i].tva / 100));

        //montant tva: total_sur_tarif_ttc - total_ht_sur_tarif_ttc?
        let total_tva_sur_tarif_ttc = total_sur_tarif_ttc - total_ht_sur_tarif_ttc;

        total.total_ht += ht;
        total.total_tva += tva;
        total.total_ttc += ttc;

        total.totalOnTtc += total_sur_tarif_ttc;
        total.htOnTtc += total_ht_sur_tarif_ttc;
        total.tvaOnTtc += total_tva_sur_tarif_ttc;

    }

    //maj show total container
    // .toFixed() permet de arrondir le nombre a deux chiffre apres le virgule
    if (priceIsTtc) {
        document.getElementById("total-ht-js").innerHTML = total.htOnTtc.toFixed(2);
        document.getElementById("total-tva-js").innerHTML = total.tvaOnTtc.toFixed(2);
        document.getElementById("total-ttc-js").innerHTML = total.totalOnTtc.toFixed(2);
    } else {
        document.getElementById("total-ht-js").innerHTML = total.total_ht.toFixed(2);
        document.getElementById("total-tva-js").innerHTML = total.total_tva.toFixed(2);
        document.getElementById("total-ttc-js").innerHTML = total.total_ttc.toFixed(2);
    }


    console.log(total);
    return total
}

export async function getData(id) {
    let html = "";
    let customer_info_facture = document.getElementById("customer-info-facture");
    const url = "/api/customer/" + id;

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status} `);
        }

        const customer = await response.json();

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