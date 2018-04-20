//$.noConflict();
$(document).ready(function(){
    setTimeout(function() {
      $('.alert').fadeOut('fast');
    }, 5000); 

    //$('.collapse').collapse({'toggle': false})
    var bootstrapButton = $.fn.button.noConflict() // return $.fn.button to previously assigned value
    $.fn.bootstrapBtn = bootstrapButton            // give $().bootstrapBtn the Bootstrap functionality

    $('.dropdown-submenu a.test').on("click", function(e){
      $(this).next('ul').toggle();
      e.stopPropagation();
      e.preventDefault();
    });
    
    //$('[data-toggle="tooltip"]').tooltip();
});

function upperCase(name) {
	name = name.slice(0,1).toUpperCase() + name.slice(1).toLowerCase();	    
    return name;
};

function modalDelete(module,id){
	var moduleUC = upperCase(module);
	
	$.get('/'+module+'/get'+moduleUC+'/' + id, function(response){
		console.log(response);
		$('label[id="name"]').text(response.name);	
    })
		
    $('form[id="delete"]').attr('action',module+'/' + id);
};


function modalEdit(module,id)
{
	var moduleUC = upperCase(module);

	$.get('/'+module+'/get'+moduleUC+'/' + id, function(response){
      	//console.log(response);
      	if(module === "type"){
      		$('input[id="name"]').val(response.name);	        
	        $('#kind').val(response.kind);
      	} 

      	if (module === "component") {
      		$('#type_id').val(response.type_id);
      		$('input[id="name"]').val(response.name);
      		$('input[id="cost"]').val(response.cost);	        
      	}

        if (module === "user") {
          $('input[id="name-edit"]').val(response.name);
          $('input[id="email-edit"]').val(response.email);
        }    	    
    })
    $('form[id="edit"]').attr('action',module+'/' + id);      	
};

function modalReset(id){
  $('form[id="reset"]').attr('action','/user/reset/' + id);
};