<nav id="sidebar">
            <div class="sidebar-header">
                <h3>Bootstrap Sidebar</h3>
            </div>

            <ul class="list-unstyled components">
				<?php
					$menues = sidebar_menu();

					foreach ( $menues as $title =>$menu ) {

						if ( ! is_array($menu )){
							echo "<li><a href='$menu'>$title</a></li>";
						} else {
							echo "<li class=''><a href='#{$menu['id']}' data-bs-toggle='collapse' data-toggle='collapse' aria-expanded='false' class='dropdown-toggle'>$title</a>";
							echo "<ul class='collapse list-unstyled' id='{$menu['id']}'>";
							foreach ( $menu['submenu'] as $sub_title => $sub_url ) {
								echo "<li><a href='$sub_url'>$sub_title</a></li>";
							}
							echo "</ul></li>";
						}
					}
				?>
               
            </ul>

        </nav>
