Joomla.submitbutton = function(task) {
    let result;
    const fields = ["numero", "protocollo", "oggetto", "luogo"]

    switch (task) {
        case "edit.save":
            if (!(fields.every(validateField) && validateTesto())) {
                alert("Alcuni campi non sono stati inseriti correttamente")
            } else if (!validateDestinatari()) {
                alert("Devi selezionare almeno un destinatario")
            } else {
                result = confirm("Stai per pubblicare una circolare: confermi?")
            }
            break
        case "edit.cancel":
            result = confirm("Se chiudi il modulo perderai tutti i dati inseriti! Vuoi continuare?")
            break
    }

    if (result) {
        Joomla.submitform(task);
    }
}

const validateField = elementId => {
    const content = document.getElementById((elementId)).value
    return content ? true : false
}

const validateTesto = () => {
    return tinyMCE.get('testo').getContent() ? true : false
}

const validateDestinatari = () => {
    const destinatari = Array.from(document
        .getElementById("destinatari")
        .getElementsByTagName("li"))
        .slice(1) //Exclude "Tutti" checkbox
        .map(li => li.getElementsByTagName("input")[0])
        .map(checkbox => checkbox.checked)

    return destinatari.includes(true)
}

const addAllegato = () => {
    //Hidden input file tag to keep the file stored
    const input = document.createElement("INPUT")
    input.setAttribute("type", "file")
    input.setAttribute("name", "allegati[]")
    input.setAttribute("onchange", "confirmInput()")

    const newAllegati = document.getElementsByClassName("new-allegati")[0]
    newAllegati.append(input)

    const lastInput = Array.from(newAllegati.getElementsByTagName("input")).pop()
    lastInput.click()
}

const getFilenames = () => {
    const allegatiList = Array.from(document
        .getElementsByClassName("new-allegati-list")[0]
        .getElementsByTagName("li"))

    return allegatiList.map(li => li.innerText)
}

const confirmInput = () => {
    const allegatiInputs = Array.from(document
        .getElementsByClassName("new-allegati")[0]
        .getElementsByTagName("input"))

    const lastInputIndex = allegatiInputs.length - 1

    const lastInput = allegatiInputs.pop()
    const name = lastInput.value.split(/(\\|\/)/g).pop()

    if (!getFilenames().includes(name)) {
        const listItem = document.createElement("LI")
        listItem.appendChild(document.createTextNode(name))

        const removeListItem = document.createElement("INPUT")
        removeListItem.setAttribute("type", "button")
        removeListItem.setAttribute("value", "X")
        removeListItem.setAttribute("onclick", `removeNewListItem(${lastInputIndex})`)

        listItem.appendChild(removeListItem)

        document
            .getElementsByClassName("new-allegati-list")[0]
            .appendChild(listItem)
    } else {
        alert("Il file selezionato è già stato inserito")
    }
}

const removeNewListItem = index => {
    const listItem = Array.from(document
        .getElementsByClassName("new-allegati-list")[0]
        .getElementsByTagName("li"))[index]

    listItem.parentNode.removeChild(listItem)

    const inputItem = Array.from(document
        .getElementsByClassName("new-allegati")[0]
        .getElementsByTagName("input"))[index]

    inputItem.parentNode.removeChild(inputItem)
}

const removeAllegatoById = id => {
    const allegato = document.getElementById(id)
    allegato.parentNode.removeChild(allegato)
}

const addAllegatoToRemoveList = id => {
    const result = confirm("Stai per eliminare un allegato. Vuoi continuare?")

    if (result) {
        removeAllegatoById(id)

        const allegatiToDelete = document.getElementsByClassName("allegati-to-delete")[0]

        const input = document.createElement("INPUT")
        input.setAttribute("type", "hidden")
        input.setAttribute("name", "delete[]")
        input.setAttribute("value", id.replace("allegato-", ""))

        allegatiToDelete.appendChild(input)

    }
}

const selectAllDestinatari = () => {
    if (document.getElementsByName("tutti")[0].checked) {
        const destinatari = Array.from(document.getElementById("destinatari").getElementsByTagName("li"))
        destinatari.forEach(li => li.getElementsByTagName("input")[0].checked = true)
    }
}

const onActionChange = () => {
    const selectedAction = document.getElementsByName("action")[0].selectedIndex

    document
        .getElementsByClassName("date-picker")[0]
        .style
        .display = selectedAction > 0 ? "block" : "none"
}

document.addEventListener('DOMContentLoaded', onActionChange)