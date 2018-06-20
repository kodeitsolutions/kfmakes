$(document).ready(function() {

  setTimeout(function() {
    $('.alert').fadeOut('fast');
  }, 5000); 

  $('[data-toggle="tooltip"]').tooltip();

  $('.modal').on('hidden.bs.modal', function(){
    //$(this).find('form')[0].reset();
    $('input').val('');
    $('select').val('');
  });
  
  $('#export').click(function(){
    $('#myModalExport').modal('hide');
  });

  $('#showButton').click(function(event){
    $('.table-row').remove();
  });

  $('#all').click(function(event){
    event.preventDefault(); 
    if($('input[id="type"]').is(':checked') && $('input[id="type"]:checked').length === $('input[id="type"]').length) {
      $('input[id="type"]').prop('checked',false);
      $("#all").text('Todos');
    }
    else {
      $('input[id="type"]').prop('checked',true);
      $("#all").text('Quitar');
    }
    event.stopPropagation();
  })

  $('#filter-button').click(function(event){
    event.preventDefault();
  }); 

  $('.collapse').on('shown.bs.collapse', function(){
    $(this).parent().find(".fa-chevron-down").removeClass("fa-chevron-down").addClass("fa-chevron-up");
  })
  $('.collapse').on('hidden.bs.collapse', function(){
    $(this).parent().find(".fa-chevron-up").removeClass("fa-chevron-up").addClass("fa-chevron-down");
  });
});

function upperCase(name) {
	name = name.slice(0,1).toUpperCase() + name.slice(1).toLowerCase();	    
    return name;
};


function modalDelete(module,id){
	var moduleUC = upperCase(module);
	
	$.get('/'+module+'/get'+moduleUC+'/' + id, function(response){
		//console.log(response);
    if (module === "product") {
      $('label[id="name"]').text(response.type_name + ' ' + response.name);
    } else {
      $('label[id="name"]').text(response.name);
    }			
  })
		
  $('form[id="delete"]').attr('action','/'+module+'/' + id);
};


function modalEdit(module,id)
{
	var moduleUC = upperCase(module);

	$.get('/'+module+'/get'+moduleUC+'/' + id, function(response){
  	//console.log(response);
  	if(module === "type"){
  		$('input[id="name"]').val(response.name);	
      $('[name=kind]').val(response.kind);
  	} 

  	if (module === "component") {
      $('[name=type_id]').val(response.type_id);
  		$('input[id="name"]').val(response.name);
  		$('input[id="cost"]').val(response.cost);	        
  	}

    if (module === "user") {
      $('input[id="name_edit"]').val(response.name);
      $('input[id="email_edit"]').val(response.email);
    } 

    if (module === "category") {
      $('input[id="name"]').val(response.name);
    }   	    
  })
  $('form[id="edit"]').attr('action','/'+module+'/' + id);      	
};

function modalReset(id){
  $('form[id="reset"]').attr('action','/user/reset/' + id);
};

function modalShow(id){
  $.get('/product/getProductComponents/' + id, function(components){
    
    $('#description-body tr').not(':first').not(':last').remove();
    var html = '';
    for(var i = 0; i < components.length; i++){
      html += '<tr class="table-row"><td>' + (i+1) + '</td><td>' + components[i].type_name + '</td><td>' + components[i].name + '</td><td>' + components[i].pivot.quantity + '</td></tr>';
    }
                
    $('#description tr').first().after(html);
  }); 
  $.get('/product/getProduct/' + id, function(product){
    
    $('#title-show').text(product.type_name + ' ' +product.name);
  });
}

function submitForm(form) {
  document.getElementById(form).submit();
};