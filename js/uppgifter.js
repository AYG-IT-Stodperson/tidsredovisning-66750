window.onload = () => {

    document.getElementById('hamtaDatum').addEventListener("click", hamtaDatum)
    document.getElementById('hamtaSida').addEventListener("click", hamtaSida)

    rensaLista()

    setDateInterval()

    hamtaDatum()

}

function rensaLista() {
    let lista = document.getElementById("tom");
    lista.innerHTML = "";
}

function setDateInterval() {
    let idag = new Date();
    let aktuellManad = idag.getMonth();

    let fromDatum = new Date(idag.getFullYear(), aktuellManad, 1, 24);
    let toDatum = new Date(idag.getFullYear(), aktuellManad + 1, 0, 24);

    document.getElementById("franDatum").value = fromDatum.toISOString().substring(0, 10);
    document.getElementById("tillDatum").value = toDatum.toISOString().substring(0, 10);

}

function hamtaDatum() {

    fetch("dummy/uppgifter.json")
        .then(response => {
            if (response.ok) {
                return response.json()
            }

            return response.json()
                .catch(() => null) 
                .then(message => {
                    let fel = {
                        status: response.status,
                        text: response.statusText,
                        url: response.url,
                        message
                    }

                    throw fel
                })
        })
        .then(data => {
            fyllLista(data)
        })
        .catch(error => {
            console.error(error)
        })
}

function hamtaSida() {
    
    fetch("dummy/uppgifter.json")
        .then(response => {
            if (response.ok) {
                return response.json()
            }

            
            return response.json()
                .catch(() => null) 
                .then(message => {
                    let fel = {
                        status: response.status,
                        text: response.statusText,
                        url: response.url,
                        message
                    }

                    throw fel
                })
        })
        .then(data => {
            fyllLista(data)
        })
        .catch(error => {
            console.error(error)
        })
}

function fyllLista(data) {
   
    rensaLista()

    let target = document.getElementById("tom")
    
    for (let i = 0; i < data.tasks.length; i++) {
        let rad = document.createElement("ul")
        rad.className = "lista"
        let content = `<li>${data.tasks[i].date}</li>`
        content += `<li class="right">${data.tasks[i].time}</li>`
        content += `<li>${data.tasks[i].activity}</li>`
        content += `<li>${data.tasks[i].description ?? ''}</li>`
        content += `<li class="right"><a href="editUppgift.html?id=${data.tasks[i].id}"><img class="edit" src="images/edit.png"></a>`
        content += `<img class="delete" onclick="alertDelete(${data.tasks[i].id})" src="images/delete.png"></li>`
        rad.innerHTML = content
        target.appendChild(rad)
    }
}

function alertDelete(id) {
    if (confirm('Vill du radera posten med id=' + id + '?')) {
        alert("Raderar")
    }
}

function aktiveraAlternativ(ev) {
    try {
        if (ev.target.value === 'sida') {
          
            document.getElementById('sidnr').disabled = false;
            document.getElementById('hamtaSida').disabled = false;
            hamtaSida()
            
            document.getElementById('franDatum').disabled = true;
            document.getElementById('tillDatum').disabled = true;
            document.getElementById('hamtaDatum').disabled = true;
        } else {
            
            document.getElementById('franDatum').disabled = false;
            document.getElementById('tillDatum').disabled = false;
            document.getElementById('hamtaDatum').disabled = false;
            hamtaDatum()
            
            document.getElementById('sidnr').disabled = true;
            document.getElementById('hamtaSida').disabled = true;
        }
    } catch (error) {
        console.error(error)
        
        document.getElementById('franDatum').disabled = false;
        document.getElementById('tillDatum').disabled = false;
        document.getElementById('hamtaDatum').disabled = false;
        hamtaDatum()

        document.getElementById('sidnr').disabled = true;
        document.getElementById('hamtaSida').disabled = true;
    }
}