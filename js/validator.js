$(document).ready(function(){
    $("#userForm").validate({
	errorClass:"help-block",
	rules:{
	    firstname:"required",
	    lastname:"required",
	    password:{
		required: true,
		minlength:5
	    },
	    confirm_password:{
		required: true,		
		equalTo:"#password"
	    },
	    email: {
		required: true,
		email: true
	    }
	},
	messages:{
	    firstname:"Veuillez entrer un prénom",
	    lastname:"Veuillez entrer un nom de famille",
	    password:{
		required: "Veuillez entrer un mot de passe",
		minlengh: "Le mot de passe doit faire au moins 5 caractères"
	    },
	    confirm_password:{
		required: "Veuillez confirmer le mot de passe",
		equalTo: "Veuillez entrer le meme mot de passe qu'au dessus"
	    },
	    email:{
		required:"Veuillez entrer un email",
		email:"L'adresse email n'est pas valide"
	    }
	},
	highlight: function(element,errorClass) {
$(element).closest('.form-group').addClass("has-error");
},

unhighlight: function(element,errorClass) {
$(element).closest('.form-group').removeClass("has-error")
}









    });
});
