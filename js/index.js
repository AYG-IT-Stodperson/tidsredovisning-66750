window.onload=() =>{
    //rensa listan
    alert("Tömmer listan")
    rensaLista()

    //sätt standardvärden från perioden 
    alert ("sätter standardvärde för perioden")
    setDateInterval()

    //hämta från API:et
    alert("hämtar data")
    getCompilation()

}

function rensaListan() { 
    let lista = document.getElementById("tom")
    lista.innerHTML = "";
}

function setDataINterval() {
    let idag = new Date();
    let aktuelManad =idag.getMonth();

    let fromDatum =new Date(idag.getFullYear(), aktuellManad, 1, 24);
    let toDatum =new Date(idag.getFullYear(), aktuellManad, +1, 0, 24);

    
    document.getElementById("framDatum").value=fromDatum.toISOString().substring(0,10)
    document.getElementById("tillDatum").value=toDatum.toISOString().substring(0,10)
}

function getCompilation() {
    let retur ={
        tasks:[
            {
                id:1,
            time: "3:00",
            name:"databas"
        },
        {
            id:3,
            time: "2:15",
            name:"API-anrop"
        },
        {
            id:4,
            time: "3:30",
            name:"javascript"
        },
        {
            id:5,
            time: "1:00",
            name:"atyling"
        },
            ]
    }
    fyllista(retur)
}

function fyllista(data) {
    let target=document.getElementById("tom")
    for(let i=0; i<data.tasks.length; i++) {
        let rad=document.getElementById("ul")
        rad.className="lista"
        rad.innerHTML=<li>${data.tasks[i].name}</li><li class="right">${data.tasks[i].time}</li>
        target.appendChild(rad)
    }
}