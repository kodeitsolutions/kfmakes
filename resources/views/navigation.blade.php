<!DOCTYPE html>
	<div class="row ">
		<div class="col-md-12">		
			<ul class="nav nav-tabs nav nav-justified">					
				<li class="nav-item {{ setActive('type')}}">
			    	<a class="nav-link" href="/type">Tipos</a>
			  	</li>
			  	<li class="nav-item {{ setActive('component')}}">
			    	<a class="nav-link" href="/component">Componentes</a>
			  	</li>
			 	<li class="nav-item {{ setActive('product')}} {{ setActive('product/create')}}">
			    	<a class="nav-link" href="/product">Piezas</a>
			  	</li>
			</ul>
		</div>
	</div>
</html>