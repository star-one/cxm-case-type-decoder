<?php
   if(isset($_FILES['theFile'])){
      $errors= array();
      $file_name = $_FILES['theFile']['name'];
      $file_size = $_FILES['theFile']['size'];
      $file_tmp = $_FILES['theFile']['tmp_name'];
      $file_type = $_FILES['theFile']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['theFile']['name'])));
      
      $extensions= array("json");
      
      if(in_array($file_ext,$extensions)=== false){
        $errors[]="extension not allowed, please choose a JSON file.";
      }
      
      if($file_size > 2097152) {
         $errors[]='File size must be exactly 2 MB';
      }
      
      if(empty($errors)==true) {
         move_uploaded_file($file_tmp,"files/".$file_name);
      }else{
         print_r($errors);
      }
   }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset=utf-8>
    <title>CXM Design document generator</title>
  </head>
  <body>
     <form action = "" method = "POST" enctype = "multipart/form-data">
         <input type = "file" name = "theFile" />
         <input type = "submit" />
     </form>
<?php
$json_url = "files/".$file_name;
$json = file_get_contents($json_url);
$json=str_replace('},

]',"}

]",$json);

$jsonData = json_decode($json, true);
$data = $jsonData["imported_resource"];

$statuses = $data["status_keys"];
$transitions = $data["transition_keys"];
$resources = $jsonData["resources"]["items"];
?>
    <h1>
      <?=$data["key"]["label"];?>
    </h1>
    <h2>
      Case details
    </h2>
    <strong>Cases visible to associated people: </strong><?=$data["auto_assign_users"];?><br />
    <strong>Maximum number of open cases per address: </strong><?=$data["case_limits"]["primary-address"];?><br />
    <strong>Allow deletion of personal data from cases: </strong><?=$data["personal_data_redaction"];?>
    <h2>
      Statuses
    </h2>
    <ul>
      <?php
        $i = 0;
        foreach ($statuses as $status)
        {
          echo "<li>" . $status["label"] ."</li>";
        }
        $i++;
      ?>
    </ul>
    <h2>
      Transitions
    </h2>
    <ul>
      <?php
        foreach ($transitions as $transition)
        {
          echo "<li>" . $transition["label"] ."</li>";
        }
      ?>
    </ul>
    
    <h2>
      Resources
    </h2>
    <ul>
      <?php
 //       foreach ($resources as $resource)
 //       {
 //         echo "<li>" . $resources[1] . "</li>";
 //       }
      ?>
    </ul>
    <?php echo "<pre>" . var_dump($resources) . "</pre>"; ?>
    
    <h2>
      File data
    </h2>
         <ul>
            <li>Sent file: <?php echo $_FILES['theFile']['name'];  ?>
            <li>File size: <?php echo $_FILES['theFile']['size'];  ?>
            <li>File type: <?php echo $_FILES['theFile']['type'] ?>
         </ul>

  </body>
</html>
