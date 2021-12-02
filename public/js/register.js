let form = document.querySelector('#registerForm');

form.first_name.addEventListener('change', function() {
    validName(this, 'prénom');
});

form.last_name.addEventListener('change', function() {
    validName(this, 'nom');
});

form.pseudo.addEventListener('change', function() {
    validPseudo(this);
});

form.email.addEventListener('change', function() {
    validEmail(this);
});

form.password.addEventListener('change', function() {
    validPass(this);
});

form.confirm_password.addEventListener('change', function() {
    validConfirmPass(this)
})

const validName = function(inputName, type) {
    let nameRegexp = new RegExp("^(?=.*[A-Z])[a-zA-z\\s\\-\\']+$", 'g');

    let testName = nameRegexp.test(inputName.value);

    if(!testName) {
        alert("le " + type + " n\'est pas valide");
    }
};

const validPseudo = function(inputPseudo) {
    let pseudoRegexp = new RegExp('^(?=.*[a-zA-Z]{4,})[\\w]{8,32}$', 'g');

    let testPseudo = pseudoRegexp.test(inputPseudo.value);

    if(!testPseudo) {
        alert('le pseudo doit comporter: \n - entre 4 et 32 caractères : \n - au moins 4 lettres minuscules ou majuscules \n Il peut également comporter: \n - des chifres \n - des underscores');
    }
};

const validEmail = function(inputEmail) {
    let emailRegexp = new RegExp('^[a-zA-Z0-9.-_]+[@]{1}[a-zA-Z0-9.-_]+[.]{1}[a-z]{2,4}$', 'g');

    let testEmail = emailRegexp.test(inputEmail.value);

    if(!testEmail) {
        alert('l\'adresse email renseignée n\'est pas valide');
    }
};

const validPass = function(inputPass) {
    let passRegexp = new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[!@#$%\\-+])[\\w!@#$%\\-+]{8,50}$', 'g');

    let testPass = passRegexp.test(inputPass.value);

    if(!testPass) {
        alert('le mot de passe doit comporter: \n - entre 8 et 50 caractères : \n - au moins 1 lettres minuscule \n - au moins 1 lettres majuscule \n - au moins un chiffre \n - au moins un caractère spécial parmis les suivants: !@#$%-+');
    }
};

const validConfirmPass = function() {
    let pass = form.password.value;
   
    let confirm = form.confirm_password.value;

    if(pass != confirm) {
        alert('les mot de passe ne correspondent pas');
    }
}

function avatar() {    
    if(form.avatar_id.value != 2 && form.avatar_id.value != 3) {   
        alert('vous devez choisir un avatar');
        return false;
    }
}   