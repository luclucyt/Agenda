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

let shareInput = document.getElementsByClassName("share-display-form-input")[0]


//on input post to php, and return the result
shareInput.addEventListener("input", function(event) {
    updateShare(event.target.value)
})


function updateShare(username){
    let shareInputValue = username

    let xhr = new XMLHttpRequest()
    xhr.open("POST", "index.php", true)
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
    xhr.send("shareInputValue=" + shareInputValue)

    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            // Create a new HTML document from the response text
            let parser = new DOMParser();
            let htmlDoc = parser.parseFromString(this.responseText, 'text/html');

            // Get the first element with class "reponesForHTML"
            let reponesForHTML = htmlDoc.querySelector('.reponesForHTML');

            // Extract the HTML content of the element and its children
            let content = reponesForHTML.innerHTML;

            // Update the HTML content of an element on the page with the extracted content
            document.getElementById("result").innerHTML = content;

            document.getElementsByClassName(`share-display-form-input`)[0].value = username;
        }
    }
}




const div = document.querySelector('.main-main-agenda-wrapper');
const scrollThreshold = 300; // the number of pixels near the top/bottom edge to trigger auto-scrolling

let isMouseDown = false;
div.addEventListener('mousedown', () => {
    isMouseDown = true;
});

div.addEventListener('mouseup', () => {
    isMouseDown = false;
});

div.addEventListener('mousemove', (e) => {
    if (isMouseDown) {
        const rect = div.getBoundingClientRect();
        const topThreshold = rect.top + scrollThreshold;
        const bottomThreshold = rect.bottom - scrollThreshold;

        if (e.clientY < topThreshold) {
            div.scrollBy(0, -10); // scroll up by 10 pixels
        } else if (e.clientY > bottomThreshold) {
            div.scrollBy(0, 10); // scroll down by 10 pixels
        }
    }
});
