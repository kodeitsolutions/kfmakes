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

function setRow(rowId, colNum, newValue)
{
  console.log('entre')
    //$('#descrption').find('tr#'+rowId).find('td:eq(+'+colNum+')').html(newValue);
    $('<td id="index_'+rowId+'">'+ (rowId+1) +'</td>').appendTo('#'+rowId);
};

function modalShow(id){
  $.get('/product/getProductComponents/' + id, function(response){

    $('#description-body tr').not(':first').not(':last').remove();
    var html = '';
    for(var i = 0; i < response.length; i++){
      html += '<tr class="table-row"><td>' + (i+1) + '</td><td>' + response[i].type_name + '</td><td>' + response[i].name + '</td><td>' + response[i].pivot.quantity + '</td></tr>';
    }
                
    $('#description tr').first().after(html);
  }); 
}

function submitForm(form) {
  //event.preventDefault(); 
  document.getElementById(form).submit();
}
