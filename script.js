// Gérer le mot de passe

let passwordChecklist = document.querySelectorAll('.list-item');
let passwordInp = document.querySelector('.password-input');

let validationRegex = [
    { regex: /.{8,}/ }, // min 8 letters,
    { regex: /[0-9]/ }, // numbers from 0 - 9
    { regex: /[a-z]/ }, // letters from a - z (lowercase)
    { regex: /[A-Z]/}, // letters from A-Z (uppercase),
    { regex: /[^A-Za-z0-9]/} // special characters
]

passwordInp.addEventListener('keyup', () => {
    validationRegex.forEach((item, i) => {

        let isValid = item.regex.test(passwordInp.value);

        if(isValid) {
            passwordChecklist[i].classList.add('checked');
        } else{
            passwordChecklist[i].classList.remove('checked');
        }

    })
})

// Faire apparaître ou non le mot de passe 

 // COPYRIGHT https://www.w3schools.com/howto/howto_js_toggle_password.asp
 function showpass(){
    var x = document.getElementById("passwd");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
    // passe du type "text" au type "value" l'input des mots de passes au check d'une checkbox !
    var y = document.getElementById("confirm_passwd") ;
    if (y.type === "password") {
      y.type = "text";
    } else {
      y.type = "password";
    }
  }

  // COPYRIGHT https://www.geeksforgeeks.org/how-to-validate-confirm-password-using-javascript/
   
  function validate_password() {
 
    let passwd = document.getElementById('passwd').value;
    let confirm_passwd = document.getElementById('confirm_passwd').value;
   // comparaison des values des deux inputs pour vérifier qu'elles possèdent bien les meme caractères, indique par un message si oui ou non ils sont similaires.
    if (passwd != confirm_passwd) {
        document.getElementById('wrong_pass_alert').style.color = 'red';
        document.getElementById('wrong_pass_alert').innerHTML = 'Utilisez le même mot de passe !';
        document.getElementById('create').disabled = true;
        document.getElementById('create').style.opacity = (0.4);
    } else {
        document.getElementById('wrong_pass_alert').style.color = 'green';
        document.getElementById('wrong_pass_alert').innerHTML = 'Mots de passes similaires !';
        document.getElementById('create').disabled = false;
        document.getElementById('create').style.opacity = (1);
    }
}
// Si les deux mots de passe sont les mêmes et que leur valeur n'est pas vide l'inscription est confirmée cependant lors de la modification d'un mot de passe si l'utilisateur ne souhaite pas changer de mot de passe on doit également lui laisser la possibilité de laisser les champs vides
function wrong_pass_alert() {
    if (document.getElementById('passwd').value != "" &&
        document.getElementById('confirm_passwd').value != "") {
        alert("Votre inscription a été confirmée");
    } else {
         alert("Remplissez complètement le formulaire !");
    }
}
function wrong_pass_alert_modif() {
    if (document.getElementById('passwd').value != "" &&
        document.getElementById('confirm_passwd').value != "") {
        alert("Vos modifications ont bien été prises en compte !");
    } else if (document.getElementById('passwd').value == "" &&
        document.getElementById('confirm_passwd').value == "") {
        alert("Vos modifications ont bien été prises en compte !");
    } else {
         alert("Remplissez complètement le formulaire !");
    }
}

// SIDE NOTES HEURE ET DATE

let date = new Date();
// récupérer la date actuelle 
  let heure = date.getHours()
// récupérer en extraire les heures
   
// Si il est plus de 8h ou - de 20h l'icone de la sidenote est un soleil et le bckground s'adapte en conséquence, dans le cas inverse l'icone sera une lune.
if (heure>=8 && heure<=19)  {
  document.body.style.backgroundColor = "#F3DEBE";
  document.getElementById("date").innerHTML="Bonjour "+document.getElementById('date').innerHTML;
  document.getElementById("timeicon").setAttribute("src", "assets/sun.png");

} 
else {
  document.body.style.backgroundColor = "#ACC5E3";
  document.getElementById("date").innerHTML="Bonsoir "+document.getElementById('date').innerHTML;
  document.getElementById("timeicon").setAttribute("src", "assets/moon.png");
}