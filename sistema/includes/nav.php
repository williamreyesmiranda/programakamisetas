<nav>
			<ul>
				<li><a href="index.php">Inicio</a></li>
				
				<?php
				if($_SESSION['idrol']==1 || $_SESSION['idrol']==4 || $_SESSION['idrol']==3){//bodega
					echo "<li >
					<a href=\"ingresopedidos.php\">Ingreso Pedidos</a>
				</li>";}
				if($_SESSION['idrol']==1 || $_SESSION['idrol']==4){//bodega
					echo "<li >
					<a href=\"listabodegageneral.php\">Bodega</a>
				</li>";}
				if($_SESSION['idrol']==1 || $_SESSION['idrol']==3){//corte
					echo "<li >
					<a href=\"listacortegeneral.php\">Corte</a>
				</li>";}
				if($_SESSION['idrol']==1 || $_SESSION['idrol']==2){//confeccion
					echo "<li >
					<a href=\"listaconfecciongeneral.php\">Confección</a>
				</li>";}
				if($_SESSION['idrol']==1 || $_SESSION['idrol']==5){//sublimacion
					echo "<li >
					<a href=\"listasublimaciongeneral.php\">Sublimación</a>
				</li><li >
				<a href=\"listaestampaciongeneral.php\">Estampación</a></li>";}
				
				
				
				if($_SESSION['idrol']==1){
					echo "
					<li class=\"derecha\">
					<a href=\"\">Administrador</a>
						<ul>
							<li><a href=\"lista_empleados.php\">Lista Empleados</a></li>
							<li><a href=\"lista_entidades.php\">Lista Entidades</a></li>
							<li><a href=\"lista_estudios.php\">Lista Estudios</a></li>
							<li><a href=\"eliminar_citas.php\">Eliminar Citas</a></li>
							<li><a href=\"menu.php\">Editar Menú</a></li>
						</ul> 
					</li>
					";
				}
			    ?>

					<!-- <li class="principal">
					<a href="lista_citas.php">Citas</a>
					 <ul>
						<li><a href="#">Nuevo Cliente</a></li>
						<li><a href="#">Lista de Clientes</a></li>
					</ul> 
				</li> -->
			</ul>
		</nav>