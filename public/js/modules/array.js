export function getBtnDel() {
    return document.getElementsByClassName("btn-del-js");
}

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

    return false;
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
    let artcile_json = "";
    for (let i = 0; i < pannier.length; i++) {

        artcile_json += JSON.stringify({
            'designation': pannier[i].designation,
            'price': pannier[i].price,
            'quantity': pannier[i].quantity,
            'tva': pannier[i].tva
        })

    }
    console.log(artcile_json);
    //injection dans dom
    document.getElementById("article-container").value = artcile_json;
}
export function calculatorTotal(pannier) {
    //initialise array total
    let total = { total: 0, total_ht: 0, total_ttc: 0, total_tva: 0 }
    //on parcour le pannier
    for (let i = 0; i < pannier.length; i++) {
        let tva = (pannier[i].price * pannier[i].quantity) / 100 * pannier[i].tva;
        let ht = pannier[i].price * pannier[i].quantity;
        let ttc = tva + ht;
        total.total_ht += ht;
        total.total_tva += tva;
        total.total_ttc += ttc;
    }
    console.log('function calcul total')
    console.log(pannier);
    console.log(total);
    //maj show total container
    document.getElementById("total-ht-js").innerHTML = total.total_ht;
    document.getElementById("total-tva-js").innerHTML = total.total_tva;
    document.getElementById("total-ttc-js").innerHTML = total.total_ttc;

    return total
}