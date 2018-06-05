<!DOCTYPE html>
	<div class="row">
		<div class="col-md-12">		
			<ul class="nav nav-tabs nav-fill justify-content-center">					
				<li class="nav-item ">
			    	<a class="nav-link {{ setActive('type')}} {{ setActive('type/search')}}" href="/type">Tipos</a>
			  	</li>
			  	<li class="nav-item ">
			    	<a class="nav-link {{ setActive('component')}} {{ setActive('component/search')}}" href="/component">Componentes</a>
			  	</li>
			 	<li class="nav-item ">
			    	<a class="nav-link {{ setActive('product')}} {{ setActive('product/create')}} {{ setActive('product/search')}}" href="/product">Piezas</a>
			  	</li>
			</ul>
		</div>
	</div>
</html>