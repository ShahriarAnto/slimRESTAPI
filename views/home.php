
<h1>Welcome to the slim page</h1>

<?php if(empty($_SESSION['user_id'])): ?>

    <a href = "/signup">Sign up for an API KEY</a>  
    or
    <a href = "/login">Log in</a> 

<?php else: ?>
    <a href="/profile">View profile</a>
    or
    <a href="/logout">Log out</a>
<?php endif; ?>