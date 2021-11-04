let down = document.getElementById("down"),
    up = document.getElementById("up"),
    voteNumber = document.getElementById("vote-number")

up.addEventListener("click", (e) => {
  e.preventDefault();
  updateData('up', up.dataset.id);
});

down.addEventListener("click", (e) => {
  e.preventDefault();
  updateData('down', up.dataset.id);
});

const updateData = (action) => {
  fetch(`${up.dataset.id}/${action}`)
    .then((response) => response.json())
    .then((data)=> {
        voteNumber.textContent = data;
  });
}