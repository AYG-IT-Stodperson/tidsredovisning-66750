window.onload = () => {
    rensaListan();
    setDateInterval();
    getTasklist();
};

function rensaListan() {
    const ul = document.getElementById("ul");
    if (ul) ul.innerHTML = "";
}

function setDateInterval() {
    let idag = new Date();
    let aktuellManad = idag.getMonth();

    let fromDatum = new Date(idag.getFullYear(), aktuellManad, 1);
    let toDatum = new Date(idag.getFullYear(), aktuellManad + 1, 0);

    const from = document.getElementById("framDatum");
    const to = document.getElementById("tillDatum");

    if (from) from.value = fromDatum.toISOString().substring(0, 10);
    if (to) to.value = toDatum.toISOString().substring(0, 10);
}

function getTasklist() {
    fetch("dummy/uppgifter.json")
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP fel: ${response.status}`);
            }
            return response.json();
        })
        .then(data => fyllUppgifter(data))
        .catch(error => console.error("Fel vid hämtning:", error));
}

function fyllUppgifter(data) {
    const table = document.getElementById("taskTable");
    if (!table) return;

    let html = `
        <div class="box">datum</div>
        <div class="box">tid</div>
        <div class="box">aktivitet</div>
        <div class="box">beskrivning</div>
    `;

    data.tasks.forEach(task => {
        html += `
            <div class="box">${task.date}</div>
            <div class="box">${task.time}</div>
            <div class="box">${task.aktivitet}</div>
            <div class="box">${task.description}</div>
        `;
    });

    table.innerHTML = html;
}

function aktiveraAlternativ(ev) {
    try {

    if(ev.target.value==='sida'){
        //aktivera rätt kontroller
        document.getElementById('sidnr').disabled=false;
        document.getElementById('hamtaSida').disabled=false;
        hamtadida()
        //avktivera övriga kontroller
        document.getElementById('framDatum').disabled=true;
        document.getElementById('tillDatum').disabled=true;
        document.getElementById('hamtaSida').disabled=true;
        hamtadatum()
    } else {
        //aktivera rätt kontroller
        document.getElementById('framDatum').disabled=false;
        document.getElementById('tillDatum').disabled=false;
        document.getElementById('hamtaSida').disabled=false;
        hamtadatum()
        //avktivera övriga kontroller
        document.getElementById('sidnr').disabled=true;
        document.getElementById('hamtaSida').disabled=true;
        hamtasida()
    }
    } catch (error) {
        console.error(error);
         //aktivera standard kontroller
        document.getElementById('framDatum').disabled=false;
        document.getElementById('tillDatum').disabled=false;
        document.getElementById('hamtaSida').disabled=false;
        hamtadatum()
        //avktivera övriga kontroller
        document.getElementById('sidnr').disabled=true;
        document.getElementById('hamtaSida').disabled=true;
        hamtasida()
    }
}

//skapa händelselyssnare för knapparna
document.getElementById("hamtaDatum").addEventListener("click", hamtaNyDatum)
document.getElementById("hamtaSida").addEventListener("click", hamtaNySida)

//rensa lista
rensaLista();

//sätt standardvärde för perioden
setDateInterval();

//hämta från API:et
hamtaDatum();

function rensaLista() {
    let lista = document.getElementById("ul");
    lista.innerHTML = "";
}