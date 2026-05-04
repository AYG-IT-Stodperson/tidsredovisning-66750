window.onload = () => {
    rensaListan();
 //   setDateInterval();
    getTasklist();
}

function rensaListan() { 
    document.getElementById("ul").innerHTML = "";
}

function setDateInterval() {
    let idag = new Date();
    let aktuellManad = idag.getMonth();

    let fromDatum = new Date(idag.getFullYear(), aktuellManad, 1);
    let toDatum = new Date(idag.getFullYear(), aktuellManad + 1, 0);

    document.getElementById("framDatum").value = fromDatum.toISOString().substring(0,10);
    document.getElementById("tillDatum").value = toDatum.toISOString().substring(0,10);
}

function getCompilation() {
    let data = {
        tasks: [
            { id:3, time: "3:00", name:"html" },
            { id:2, time: "2:15", name:"javascript" }
        ]
    };

    fyllLista(data);
    fyllUppgifter(data);
}

function getTasklist() {
    fetch("dummy/uppgifter.json")
        .then(response => {
            if(response.ok) { 
                return response.json();
            }

            //response är inte ok...
            return response.json()
                .catch(()=>null) //är svaret inte json händer inget
                .then(message=>{
                    let fel ={status:response.status,
                        text: response.statusText, 
                        url: response.url, 
                        message
                    }

                    throw fel 
                })
        })
        .then(data => {
            fyllUppgifter(data)
        })
        .catch(error => {
            console.error(error)
        })
}

function fyllUppgifter(data) {
    let table = document.getElementById("taskTable");

    table.innerHTML = `
        <div class="box">datum</div>
        <div class="box">tid</div>
        <div class="box">aktivitet</div>
        <div class="box">beskrivning</div>
    `;

    data.tasks.forEach(task => {
        table.innerHTML += `
            <div class="box">${task.date}</div>
            <div class="box">${task.time}</div>
            <div class="box">${task.aktivitet}</div>
            <div class="box">${task.description}</div>
        `;
    });
}