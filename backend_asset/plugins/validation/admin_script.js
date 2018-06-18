	$().ready(function() {
	
  
		// validate signup form on keyup and submit
		$("#login").validate({
			rules: {
				title: "required",
				description: "required",
				name: "required",
				surname: "required",
				address: "required",
				cif: "required",
				person_of_contact: "required",
				telephone: {
                      required: true,
                       minlength: 10,
                       pattern: [/^[7-9]{1}[0-9]{9}$/]
                  },
                  date_of_birth:
						    {
						        required: true,
						        date: true
						    },
                  gender: {
                  	required: true,
                  },
				
				 username: {
					required: true,
					minlength: 2
				},
				email: {
					required: true,
					email: true
				},
				password: {
					required: true,
					minlength: 5
				},
				confirm_password: {
					required: true,
					minlength: 5,
					equalTo: "#password"
				}
				
				
			},
			messages: {
				title: "Please enter a title",
				description: "Please provide service description",
				name: "Please provide a name",
				surname: "Please provide your surname",
				address: "Please provide your address",
				cif: "Please provide your cif code",
				person_of_contact: "Please provide contact person name",
				telephone: {
                      required: "Please provide your contact number",
                       minlength: "Your contact number must be at least 10 digits long",
                       pattern: "your contact number number must be only digits"
                   },
                   date_of_birth:
					    {
					        required: "Please provode your date of birth",
					        date: "Can contain digits only"
					    },
                   gender: "Please provide your gender",
				
				 username: {
                     required: "Please provide  username",
					minlength: "Your name must be at least 2 characters long"
					    },
					    email: "Please enter a valid email address",
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				},
				confirm_password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long",
					equalTo: "Please enter the same password as above"
				}
				
				
			}
		});
	});