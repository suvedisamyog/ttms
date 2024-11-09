<nav class="navbar navbar-expand-lg" style="<?php echo $nav_style ?>">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="#">TTMS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse custom-nav" id="navbarNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <a class="nav-link text-white" aria-current="page" href="index.php">Home</a>
          </li>
		  <?php
		 if($is_logged_in){
			?>
			 <li class="nav-item">
            	<a class="nav-link text-white" href="?page=recommendation">Recommended</a>
         	 </li>
			<?php
		 }
		  ?>

          <li class="nav-item">
            <a class="nav-link text-white" href="?page=trending">Trending</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="#">Policy</a>
          </li>
		  <?php
		 if($is_logged_in){
			?>
			<li class="nav-item">
            	<a class="nav-link text-white" href="#">Bookings</a>
          	</li>
			<?php
		 }
		  ?>

		  <?php
			if($is_logged_in){
				?>
				<li class="nav-item">
            		<a class="nav-link text-white" href="#">Profile</a>
          		</li>
				<?php
			}else{
				?>
				<li class="nav-item">
            		<a class="nav-link text-white" href="login.php">Login</a>
          		</li>
				<?php
			}
		  ?>

        </ul>
      </div>
	  <?php $page = (isset($_GET['page']) ? $_GET['page'] : 'home'); ?>
	  <div class="search">
		  <input class="form-control mr-sm-2" type="search" placeholder="Search" id="search_packages" aria-label="Search" data-page="<?php echo $page ?>">

    </form>
	  </div>
    </div>
  </nav>
