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