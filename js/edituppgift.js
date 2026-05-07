let aktiviteter=[]
window.onload=() => {
    let queryString=window.location.search
    let parameters=new URLSearchParams(queryString)

    if(parameters.has('id')) {
        fyllForm(parameters.get('id'))
    } else {
        emptyForm()
    }

    getActivities()
     .finally(() => {
        if(parameters.has('id')) {
            fillForm(parameters.get('id'))
        } else {
            emptyForm()
        }
     })
}
        
    
    async function fetchActivities() {

}

async function fillForm(id) {


    fillDropdown(aktiviteter)
    //hämta uppgifter, just nu alla och välj rätt sedan hämta rätt.
    
    //fyll formulär
}

function emptyForm() {
    //dölj ID-fältet
    document.getElementById('labelId').style.display='none'

    //töm alla inputs
    document.getElementById('inputDatum').value=(new Date).toString().substring(0,10)
    document.getElementById('inputTid').value='12:00'
    document.getElementById('inputVaraktighet').value='01:00'
    document.getElementById('inputbeskrivning').value=''
    //aktivitet är en dropdown!
    document.getElementById('inputAktivitet').value=-1
}
