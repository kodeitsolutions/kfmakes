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

  $('#showButton').click(function(){
    $('dl[id="components"]').empty();
  });

  $('#filter a').click(function(event){
    console.log('aqui');
    event.preventDefault();
    document.getElementById('filter-form').submit();
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
		$('label[id="name"]').text(response.name);	
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
  })
  $('form[id="edit"]').attr('action','/'+module+'/' + id);      	
};

function modalReset(id){
  $('form[id="reset"]').attr('action','/user/reset/' + id);
};

function modalShow(id){
  $.get('/product/getProductComponents/' + id, function(response){
    
    console.log(response);
    for (var i = 0; i < response.length; i++) {
      $('<dt class="col-sm-3" id="'+i+'">'+ (i+1) +'.</dt>').appendTo("#components");

      $.get('/type/getType/' + response[i].type_id, function(type){      
        console.log(i)
        //$('<dd class="col-sm-3" id="type">'+type.name+'</dd>').appendTo('#index_'+type.id);
        $('dt[id="'+ i +'"]').append('<dd class="col-sm-3" id="type">'+type.name+'</dd>');
        //$('dt').prepend('<dd class="col-sm-3" id="type">'+type.name+'</dd>'); 
        //$('<dd class="col-sm-3" id="type">'+type.name+'</dd>').appendTo("dl");          
      }); 

      $('dt[id="'+ i +'"]').append('<dd class="col-sm-3" id="name">'+response[i].name+'</dd>');
      $('dt[id="'+ i +'"]').append('<dd class="col-sm-3" id="quantity">'+response[i].pivot.quantity+'</dd>');

      //$('dt').append('</br>');
    }
  });
}

function submit(form) {
  event.preventDefault(); 
  document.getElementById(form).submit()
}
