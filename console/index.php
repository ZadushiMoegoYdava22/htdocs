<?php
  // Include Composer autoloader
  require __DIR__ . '/vendor/autoload.php';
  
  // Connect to MongoDB
  $database = (new MongoDB\Client("mongodb://127.0.0.1/27017"))->AirSIEM;
  $collection = $database->alerts;
  $cursor = $collection->find([]);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>SIEM_GAMMA</title>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0" />
    <meta http-equiv="refresh" content="5; URL=index.php">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>  
  </head>
  <body class="p-5">  
    <h1 class="mb-4">Эксперементальная панель SIEM_GAMMA</h1>
    <table class="table table-hover table-sm border">
      <thead>
        <tr class="table-success">
          <th scope="col">№</th>
          <th scope="col">Время</th>
          <th scope="col">Правило</th>
          <th scope="col">Уровень</th>
          <th scope="col">Источник</th>
          <th scope="col">Цель</th>
          <th scope="col">Сообщение</th>
        </tr>
      </thead>
      <tbody>
      
        <?php
          // Show alerts
          $counter = 0;
          foreach ($cursor as $alert) 
          {
            $counter++;
            if ($counter > 100) $collection->drop();
            
            // Specify colors for critical alerts
            $color_class = ""; 
            if ($alert['level'] == '1') $color_class = ' class="table-warning"';
            if ($alert['level'] == '2') $color_class = ' class="table-danger"';
        ?>
        
        <tr<?php echo $color_class; ?>>
          <th scope="row"><?php echo $counter; ?></th>
          <td><?php echo $alert['timestamp']; ?></td>
          <td><?php echo $alert['matching_rule_SID']; ?></td>
          <td><?php echo $alert['level']; ?></td>
          <td><?php echo $alert['src_IP']; ?></td>
          <td><?php echo $alert['dest_IP']; ?></td>
          <td><span data-toggle="tooltip" data-placement="right" title="<?php echo htmlspecialchars($alert['log_string']); ?>"><?php echo $alert['message']; ?></span></td>
        </tr>
        
        <?php
          }
        ?>
        
      </tbody>
    </table>
    
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>
  </body>
</html>