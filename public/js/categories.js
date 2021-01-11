
var cat1 = document.querySelector("#cat1")
var cat2 = document.querySelector("#cat2");
var cat3 = document.querySelector("#cat3");
var cat4 = document.querySelector("#cat4");
var cat5 = document.querySelector("#cat5");
var cat6 = document.querySelector("#cat6");

/*
cat1.addEventListener("click", function (event) {
    console.log("in");
    window.location = window.location.href + "categorie/1";
  }, false);*/
/*
cat2.addEventListener("click", function (event) {
    window.location = window.location.href + "categorie/2";
  }, true);
*/

document.getElementById("cat1").addEventListener("click", displayDate);

function displayDate() {
  window.location = window.location.href + "categorie/1";
}

