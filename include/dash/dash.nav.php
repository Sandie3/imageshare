<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<style>
aside{
background-color: var(--color-d);
width:150px;
height:100%;
display:inline-block;
}
ul{
list-style:none;
margin:0;
padding:0;
}
ul a{
width:100%;

}
ul a li{
padding:10px;
border-bottom: 1px solid black;
}
ul a li:hover{
background-color: var(--color-b);
}
ul a li i{
padding-right:5px;
}
</style>
<aside>
	<ul>
		<a href="<?php echo $link ?>/include/dash/admin">
			<li>
				<i class="fas fa-columns"></i>
				Dashboard
			</li>
		</a>
		<a href="<?php echo $link ?>/include/dash/users">
			<li>
				<i class="fas fa-user"></i>
				Users
			</li>
		</a>
		<a href="<?php echo $link ?>/include/dash/follow">
			<li>
				<i class="fas fa-users"></i>
				Follows
			</li>
		</a>
		<a href="<?php echo $link ?>/include/dash/post">
			<li>
				<i class="fas fa-comment-alt"></i>
				Posts
			</li>
		</a>
	</ul>
</aside>
