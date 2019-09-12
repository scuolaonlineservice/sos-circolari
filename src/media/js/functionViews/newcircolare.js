/*Joomla.submitbutton = function(task) {

    let result;

    switch (task) {
        case "newcircolare.save":
            if (validateField("numero")) {
                result = confirm("Stai per pubblicare una circolare: confermi?")
            } else {
                alert("Alcuni campi non sono stati inseriti correttamente.")
            }
    }

    if (result) {
        Joomla.submitform(task);
    }
}
*/
const validateField = elementId => {
    const content = document.getElementById((elementId)).value
    return content ? true : false
}

const addAllegato = name => {
    console.log(name)
}

const selectAllDestinatari = () => {
    if(document.getElementsByName("tutti")[0].checked) {
        const destinatari = Array.from(document.getElementById("destinatari").getElementsByTagName("li"))
        destinatari.forEach(li => li.getElementsByTagName("input")[0].checked = true)
    }
}