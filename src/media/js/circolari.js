const publishCircolare = id => {
    const result = confirm("Stai per pubblicare una circolare. Confermi?")

    if (result) {
        const publishInput = document.createElement("INPUT")
        publishInput.setAttribute("type", "hidden")
        publishInput.setAttribute("name", "publish")
        publishInput.setAttribute("value", id)

        const form = document.getElementById("adminForm")
        form.appendChild(publishInput)

        Joomla.submitform("edit.directPublish")
    }
}