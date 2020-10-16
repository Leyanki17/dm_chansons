<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="<?  echo $this->router->getStyleUrl();?>" />
        <title><? echo $this->title?></title>
    </head>
    <body>
        <nav class="navbar">
           <? echo $this->menu;?>
        </nav>
        <div class="feed">
        <span >
            <?php echo $this->feedback ?>
        </span>    
         </div>

        <section>
            <?php echo $this->content;?>
        </section>
    </body>
</html>