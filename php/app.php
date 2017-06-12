<?php
function LoadPelatisData() {
// Basic Function that loads the table of register users
  $UserRows = '';
  $Data = new DBCON();
  $Query = 'SELECT * FROM tbl_userdata';
  $DATASET = $Data->RunQuery($Query);
  if (!$DATASET['Error']) {
    foreach ($DATASET['Data'] as $DATA_ROW) {
      if ($DATA_ROW['active'] == 0) {
        $active = 'NO';
      } else {
        $active = 'YES';
      }
      $UserRows .= '<tr>
        <td>'.$DATA_ROW['username'].'</td>
        <td>'.$DATA_ROW['usermail'].'</td>


        <td><button id="'.$DATA_ROW['userid'].'" class="btn btn-primary BtnEdit">Edit</button></td>
      </tr>';
    }
  } else {
    echo $DATASET['Data'];
    die;
  }
  unset($DATASET,$Data);
  return $UserRows;
}
function FindPelatisPhones($PelAA) {
  $PhoneRows = '';


  $Data = new DBCON();
  $Query = 'SELECT * FROM tbl_userdata WHERE userid ='.$PelAA;
  $DATASET = $Data->RunQuery($Query);
  if (!$DATASET['Error']) {
    foreach ($DATASET['Data'] as $DATA_ROW) {
      $name = $DATA_ROW['username'];
      $mail = $DATA_ROW['usermail'];
      $PhoneRows .= '<div id="DataDiv" class=""><label>User Name:</label><label><input type="text" id="username" class="Fld" value ='.$name.'></label><br><label>User Mail:</label><label><input type="text" id="username" class="Fld" value ='.$mail.'></label><br><button id="BtnSave" class="btn btn-primary">SAVE</button></div>';
    }
  }
  return $PhoneRows;
}

function TestData(){
  $TestRows = '';
  $Data = new DBCON();
  $Query = 'SELECT * FROM tbl_bordered';
  $DATASET = $Data->RunQuery($Query);
  if(!$DATASET['Error']){
    foreach($DATASET['Data'] as $DATA_ROW){
      $TestRows .= '<tr>
        <td>'.$DATA_ROW['page'].'</td>
        <td>'.$DATA_ROW['visits'].'</td>
        <td>'.$DATA_ROW['new_visits'].'</td>
        <td>'.$DATA_ROW['revenue'].'</td>
        <td><button class="btn btn-primary BtnEdit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
        <td><button class="btn btn-primary BtnDelete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
        </tr>';
    }
  }else{
     echo $DATASET['Data'];
    die;
  }
  unset($DATASET,$Data);
  return $TestRows;
}
function InsertData(){
  $Data = new DBCON();
  $new_page = mysqli_real_escape_string($Data,$_REQUEST['page']);
  $visits = mysqli_real_escape_string($Data,$_REQUEST['visits']);
  $new_visits = mysqli_real_escape_string($Data,$_REQUEST['new_visits']);
  $revenue = mysqli_real_escape_string($Data,$_REQUEST['revenue']);
  $sql = "INSERT INTO tbl_bordered (page, visits, new_visits, revenue)
          VALUES ('$new_page','$visits','$new_visits','$revenue')
          ";
  if(mysqli_query($Data, $sql)){

    echo "Records added successfully.";

  } else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($Data);
}        
}
?>
