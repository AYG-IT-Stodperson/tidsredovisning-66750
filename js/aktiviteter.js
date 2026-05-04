window.addEventListener("load", () => {
    rensaListan();
    setDateInterval();
    getActivities();
});

function rensaListan() {
    document.getElementById("ul").innerHTML = "";
}

function setDateInterval() {
    let idag = new Date();
    let aktuellManad = idag.getMonth();

    let fromDatum = new Date(idag.getFullYear(), aktuellManad, 1);
    let toDatum = new Date(idag.getFullYear(), aktuellManad + 1, 0);

    document.getElementById("framDatum").value =
        fromDatum.toISOString().substring(0, 10);

    document.getElementById("tillDatum").value =
        toDatum.toISOString().substring(0, 10);
}

async function getActivities() {
    try {
        let response = await fetch("dummy/aktiviteter.json");

        if (!response.ok) {
            let message = null;

            try {
                message = await response.text();
            } finally {
                throw {
                    status: response.status,
                    text: response.statusText,
                    url: response.url,
                    message: message
                };
            }
        }

        let data = await response.json();
        fyllAktiviteter(data);

    } catch (error) {
        console.error(error);
    }
}


function fyllAktiviteter(data) {
    let table = document.getElementById("taskTablet");

    table.innerHTML = `
        <div class="box"><b>ID</b></div>
        <div class="box"><b>Aktivitet</b></div>
    `;

    data.tasks.forEach(task => {
        table.innerHTML += `
            <div class="box">${task.id}</div>
            <div class="box">${task.name || task.aktivitet}</div>
        `;
    });
}