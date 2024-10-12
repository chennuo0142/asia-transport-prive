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
        document.getElementById('invoice_companyName').value != ""
    ) {
        return true
    }

    return false;
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