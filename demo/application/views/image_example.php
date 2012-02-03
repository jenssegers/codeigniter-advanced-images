
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Codeigniter Advanced Images example</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
  </head>

  <body>

    <div class="container">

      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit" style="margin-top: 20px; color: #fff; background-image:url(<?php echo image("media/jolly.jpg", "hero"); ?>);">
        <h1 style="color: #fff;">Hero</h1>
        <p>Vestibulum id ligula porta felis euismod semper. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
        <p><a class="btn primary large">Learn more &raquo;</a></p>

      </div>
      
      <!-- Example row of columns -->
      <div class="row">
        <div class="span6">
          <h2>Medium</h2>
          <img src="<?php echo image("media/street.jpg", "medium"); ?>" />
          <p>Etiam porta sem malesuada magna mollis euismod. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        </div>
        
        <div class="span5">
          <h2>Small</h2>
          <img src="<?php echo image("media/clouds.jpg", "small"); ?>" />
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
       </div>
       
        <div class="span5">
          <h2>Long</h2>
          <img src="<?php echo image("media/dopeshow.jpg", "long"); ?>" />
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        </div>
      </div>
      
    </div>

  </body>
</html>