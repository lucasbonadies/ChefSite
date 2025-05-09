
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
			<span>
				<img alt="Image placeholder" class="img-responsive" style="max-width: 100%;" src="dist/img/Imagen_Perfil/<?php echo $_SESSION['imagen'] ?>" />
			</span>
            <li>
                <a href="index.php"> <i class="fa fa-home fa-fw"></i>Inicio</a>
            </li>
			<li>
			 <!-- Seccion de Menu Habilitado solo nivel permitido 3 -->
				<a href="#"><i class="fa fa-cutlery fa-fw"></i>Menú<span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li>
						<a href="panel_menu.php"> Ver menú </a>
					</li>
						<?php if($_SESSION['id_nivel']==3 or $_SESSION['id_nivel']==5){ ?>
					<li>
						<a href="insertar_articulo.php"> Crear menú </a>
					</li>
						<?php } ?>
						<?php if($_SESSION['id_nivel']==5 ){ ?>
					<li>
						<a href="modificar_menu.php"> Modificar menú </a>
					</li>
						<?php } ?>		
				</ul>
            </li>

            <li>
			 <!-- Seccion de reservas se ocultan <li> segun acceso por nivel -->
				<a href="#"><i class="fa fa-book fa-fw"></i>Reservas<span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
						<?php if($_SESSION['id_nivel'] !=4 && $_SESSION['id_nivel'] !=3 ){ ?>
					<li>
						<a href="panel_reserva.php"> Generar reserva </a>
					</li>
						<?php } ?>
						<?php if($_SESSION['id_nivel'] == 5 ){ ?>
					<li>
					<!-- dentro de esta pagina se tiene que poder ver la reserva / modificar o eliminar -->
						<a href="#"> Mis reservas </a>									
					</li>	
					<li>
						<a href="#"> Modificar reserva </a>
					</li>
						<?php } ?>			   
				</ul>
				<!-- /.nav-second-level -->
            </li>
           
            <li>
			 <!-- Seccion de Pedidos se ocultan <li> segun acceso por nivel -->
				<a href="#"><i class="fa fa-shopping-cart fa-fw"></i>Pedidos<span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
						<?php if($_SESSION['id_nivel'] !=4 && $_SESSION['id_nivel'] !=3){ ?>
					<!--<li>
						<a href="generar_pedido.php"> Generar pedido </a>
					</li>-->
						<?php } ?>
					<li>
						<!--  ver los pedidos / modificar o eliminar segun el estado del mismo -->
						<a href="mis_pedidos.php"><?php if($_SESSION['id_nivel'] ==1) { echo 'Mis Pedidos'; } 
						else if($_SESSION['id_nivel'] !=4 && $_SESSION['id_nivel'] !=1 ){ echo 'Pedidos'; } ?>
					 	</a>
					</li>
						<?php if($_SESSION['id_nivel'] ==5 ){ ?>
					<!--<li>
						<a href="#"> *Proximamente Funcion de Mesero Pedidos* </a>
					</li>-->
					<!-- El chef tiene que ver los pedidos y modificar el estado segun se vayan preparando -->
					<li>
						<a href="panel_pedidos_chef.php"> Ver pedidos </a>
					</li>
						<?php } ?>	
				</ul>
            </li>

				<?php if($_SESSION['id_nivel'] ==5){ ?>			
            <li>
			 <!-- Seccion de Mesas Habilitado solo nivel permitido 2 y 4 -->
				<a href="#"><i class="fa fa-bars fa-fw"></i>Mesas<span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
						<?php if($_SESSION['id_nivel'] !=1 or $_SESSION['id_nivel'] !=3){ ?>	
					<li>
						<a href="panel.php"> Ver mesas</a>
					</li>
					<li>
						<a href="panel.php"> Editar mesas </a>
					</li>
				<!--<li>
						<a href="Algo.php"> *Proximamente Otra Funcion de Mesas* </a>
					</li> -->
						<?php } ?>							 
				</ul>
            </li>
				<?php } ?>
				<?php if($_SESSION['id_nivel']== 4 or $_SESSION['id_nivel']== 5 ){ ?>			
            <li>
			 <!-- Seccion de caja Habilitado solo nivel permitido 4 -->
				<a href="#"><i class="fa fa-credit-card fa-fw"></i>Caja<span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<!--
					<li>
						<a href="cerrar_mesa.php"> Cerrar una mesa</a>
					</li>-->
					<li>
						<a href="cobrar.php"> Cobrar</a>
					</li>
				<!--	<li>
						<a href="Algo.php"> *Proximamente Otra Funcion de cajero* </a>
					</li>	-->			
				</ul>
            </li>
				<?php } ?>	
				<?php if($_SESSION['id_nivel'] ==5){ ?>			
            <li>
			 <!-- Seccion de Admin -->
				<a href="#"><i class="fa fa-bar-chart-o fa-fw"></i>Admin<span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
						<?php if($_SESSION['id_nivel'] !=1 or $_SESSION['id_nivel'] !=3){ ?>	
					<li>
						<a href="panel_reportes.php"> Reportes </a>
					</li>
				<!--	<li>
						<a href="Algo.php"> Prox. </a>
					</li>
					<li>
						<a href="Algo.php"> *Proximamente Otra Funcion * </a>
					</li> -->
						<?php } ?>							 
				</ul>
            </li>
				<?php } ?>
        </ul>
    </div>   
</div>    