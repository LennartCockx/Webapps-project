<!-- I am currently on page '{page}' -->

<form action=<?php echo base_url() ?> method="POST">
    <input  <?php if ( $page == 'home' ) { ?>disabled<?php } ?> id="homebutton" class="btn btn-raised btn-default" type="submit" name="home" value="Home" style="width: 100%">
</form>

<form action=<?php echo base_url().'index.php/logout' ?> method="POST">
    <input class="btn btn-raised btn-default" type="submit" name="logout" value="Logout" style="width: 100%">
</form>
