<!-- This partial view is used to show the navbar for navigating to other pages -->
<div class="top-navbar">
	<div class="inner-nav">
		<div class="nav-item-left">
		<ul class="navbar-menu-items">
		
			<li class="nav-item"><a href="home">Home</a></li>
			
			@if(Session::get('sp'))
			<li class="nav-item"><a class="nav-item" href="getProfile">User Profile</a></li>
			<li class="nav-item"><a class="nav-item" href="getJobPostings">Job Postings</a></li>
			<li class="nav-item"><a class="nav-item" href="getGroups">Groups</a></li>
			
				@if(Session::get('sp')->getRole() != 0)
				<li class="nav-item"><a class="nav-item" href="getAllUsers">All Users</a></li>
				@endif
				
				<li class="nav-item"><a href="processLogout">Logout</a></li>
			@else
			<li class="nav-item"><a href="login">Login</a></li>
			<li class="nav-item"><a href="register">Register</a></li>
			@endif
			
		</ul>
		</div>
		<div class="nav-item-right">
			<ul class="navbar-menu-items">
				@if(Session::get('sp'))
				<li>{{Session::get('sp')->getFirst_name()}} |</li> 
				@endif
				<li>Connections</li>
			</ul>
		</div>
	</div>
</div>