let agenda_wrapper = document.getElementsByClassName('agenda-wrapper')[0];
let start_timeInverted = false;
let row_amount = 96;
let colom_amount = 7;

let is_dragging = false;

let week_start;
let day_offset;

let start_row = 0;
let start_time = 0;

let start_date;

let end_row = 0;
let end_time = 0;

let colom = 0;

agenda_wrapper.addEventListener('mousedown', function(event) {

    if (event.target === agenda_wrapper) {
        //mouse is pressed on the agenda
        is_dragging = true;
        start_row = get_row(event)[0] + 1;
        start_time = get_row(event)[1];

        colom = get_colom(event)[0] + 1;

        week_start = document.getElementById('week_start').value;
        week_start = new Date(week_start);

        start_date = new Date(week_start);
        start_date.setDate(week_start.getDate() + get_colom(event)[1]);

        start_date = start_date.toISOString().substring(0, 10)

        //document.getElementById('agenda-start-time').value = start_time;


        //remove all the temp agenda items
        let temp_items = document.querySelectorAll('.agenda-item-temp');
        temp_items.forEach(function (item) {
            item.remove();
        });
    }

});

agenda_wrapper.addEventListener('mousemove', function(event) {
    if(is_dragging == true){
        //mouse is moving on the agenda and is pressed
        end_row = get_row(event)[0] + 1;
        end_time = get_row(event)[1];

        //document.getElementById('agenda-eind-time').value = end_time;

        //remove all the temp agenda items
        let temp_items = document.querySelectorAll('.agenda-item-temp');
        temp_items.forEach(function(item) {
            item.remove();
        });


        let agenda_item_temp = document.createElement('div');
        agenda_item_temp.classList.add('agenda-item-temp');

        agenda_wrapper.appendChild(agenda_item_temp);

        agenda_item_temp.style.gridRowStart = start_row;
        agenda_item_temp.style.gridRowEnd = end_row;

        agenda_item_temp.style.gridColumn = colom;

        agenda_item_temp.style.backgroundColor = '#22007c';
        agenda_item_temp.style.border = '1px solid black';

        if (start_time > end_time){
            let temp = start_time;
            start_time = end_time;
            end_time = temp;

            start_timeInverted = true;
        }

        document.getElementsByClassName('agenda-item-temp')[0].innerHTML = `
            <form method="POST" action="../PHP/index.php" autocomplete="off" id="add-to-agenda-form">
                <div>
                    <input type="text" name="agenda-naam" placeholder="Titel" value="" id="agenda-naam" required oninvalid="this.setCustomValidity('Vul een titel in')" onchange="this.setCustomValidity('')"><br>       
                    
                    <input type="text" name="agenda-omschrijving" placeholder="Omschrijving" id="agenda-omschrijving" required oninvalid="this.setCustomValidity('Voer een omschrijving in')" onchange="this.setCustomValidity('')"> 
                    
                    <p id="end-start-time">` + start_time + ` - ` + end_time + `</p>             
                    

                    <label For="agenda-functie">Kies een functie: </label>
                    <select name="agenda-functie" id="agenda-functie">
                        <option value="werk">Werk</option>
                        <option value="school">School</option>
                        <option value="prive">Prive</option>
                    </select><br>
                    
                    <label for="agenda-kleur">AgendaKleur: </label>
                    <input type="color" name="agenda-kleur" placeholder="AgendaKleur" id="agenda-kleur" id="agenda-kleur" value="#22007c"><br>
                    
                    <input type="date" name="agenda-start-datum" placeholder="AgendaDatum" id="agenda-start-date" hidden>
                    <input type="time" name="agenda-start-tijd" placeholder="AgendaStartTijd" value="` + start_time + `" id="agenda-start-time" hidden><br>
                    <input type="time" name="agenda-eind-tijd" placeholder="AgendaEindTijd" value="` + end_time + `" id="agenda-eind-time" hidden><br>
                
                </div>
                <button type="submit" name="agenda-submit" id="agenda-submit">Voeg to aan de Agenda</button></form>`;

        if(start_timeInverted == true){
            let temp = start_time;
            start_time = end_time;
            end_time = temp;

            start_timeInverted = false;
        }

        document.getElementById('agenda-naam').addEventListener('input', function(event){

            let input = event.target.value;
            input = input.charAt(0).toUpperCase() + input.slice(1);
            console.log(input);
            document.getElementById('agenda-naam').value = input;
        });

        document.getElementById('agenda-omschrijving').addEventListener('input', function(event){
            let input = event.target.value;
            input = input.charAt(0).toUpperCase() + input.slice(1);
            console.log(input);
            document.getElementById('agenda-omschrijving').value = input;
        });
    }


});


agenda_wrapper.addEventListener('mouseup', function(event) {
    //mouse is not pressed on the agenda anymore
    is_dragging = false;

     document.getElementById('agenda-kleur').addEventListener('input', function(event){
        document.getElementsByClassName('agenda-item-temp')[0].style.backgroundColor = event.target.value;
     });
    document.getElementById('agenda-start-date').value = start_date;
});

function get_row(event){
    let rect = agenda_wrapper.getBoundingClientRect();
    let y = event.clientY - rect.top;
    let row_height = agenda_wrapper.clientHeight / row_amount;

    //calucate the time that corresponds to the row (1 row = 15 minutes)
    let time = Math.floor(y / row_height) * 15;
    let hours = Math.floor(time / 60);
    let minutes = time % 60;

    // format the time value so it can be used in the input field
    let formatted_time = hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');

    //console.log(formatted_time);

    return [Math.floor(y / row_height), formatted_time];
}

function get_colom(event){
    let rect = agenda_wrapper.getBoundingClientRect();
    let x = event.clientX - rect.left;
    let colom_width = agenda_wrapper.clientWidth / colom_amount;

    //calucate the day ofset that corresponds to the colom
    day_offset = Math.floor(x / colom_width);

    return [Math.floor(x / colom_width), day_offset];
}



