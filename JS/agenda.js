let agenda_view_users = document.getElementsByClassName("agenda-view-users")

// if any of the agenda-view-users elements gets checked (or unchecked)
// then loop troug .agenda-item and show them only if the have the same class as one of the checked elements
for (let i = 0; i < agenda_view_users.length; i++) {
    agenda_view_users[i].addEventListener("change", function() {

        let agenda_items = document.getElementsByClassName("agenda-item")
        for (let j = 0; j < agenda_items.length; j++) {

            agenda_items[j].style.opacity = "0.2"
            for (let k = 0; k < agenda_view_users.length; k++) {
                if (agenda_view_users[k].checked && agenda_items[j].classList.contains(agenda_view_users[k].value)) {
                    agenda_items[j].style.opacity = "1"
                }
            }
        }
    })
}