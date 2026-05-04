window.onload = () => {
    alert("Tömmer listan");
    rensaListan();

    //alert("sätter standardvärde för perioden");
    setDateInterval();

    //alert("hämtar data");
    getCompilation();
}

function rensaListan() { 
    let lista = document.getElementById("ul");
    lista.innerHTML = "";
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
    let retur = {
        tasks: [
            { id:3, time: "3:00", name:"html" },
            { id:2, time: "2:15", name:"javascript" }
        ]
    };
    fyllista(retur);
}

function fyllista(data) {
    let ul = document.getElementById("ul");

    for (let i = 0; i < data.tasks.length; i++) { 
        let li = document.createElement("li");
        li.innerHTML = `${data.tasks[i].name} <span class="right">${data.tasks[i].time}</span>`;
        ul.appendChild(li);
    }
}   