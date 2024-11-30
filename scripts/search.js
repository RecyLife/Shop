const bodyHeaderSearchInput = document.getElementById("bodyHeaderSearchInput");
const bodyHeaderSearchButton = document.getElementById("bodyHeaderSearchButton");

bodyHeaderSearchInput.addEventListener("input", function() {
  if (bodyHeaderSearchInput.value.length > 0) {
    bodyHeaderSearchButton.classList.add("active");
  } else {
    bodyHeaderSearchButton.classList.remove("active");
  }
});