window.onload = () => {
    rensaListan();
    getTasklist();
};

function rensaListan() {
    const ul = document.getElementById("ul");
    if (ul) ul.innerHTML = "";
}

function getTasklist() {
    fetch("dummy/aktiviteter.json")
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP fel: ${response.status}`);
            }
            return response.json();
        })
        .then(data => fyllAktiviteter(data))
        .catch(error => console.error("Fel vid hämtning:", error));
}

function fyllAktiviteter(data) {
    const table = document.getElementById("tasksTable");
    if (!table) return;

    let html = `
        <div class="box">id</div>
        <div class="box">aktivitet</div>
    `;

    data.tasks.forEach(task => {
        html += `
            <div class="box">${task.id}</div>
            <div class="box">${task.aktivitet}</div>
        `;
    });

    table.innerHTML = html;
}