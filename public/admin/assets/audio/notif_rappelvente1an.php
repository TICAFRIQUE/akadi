<?php
include('../controllers/dbclass.php');
include('../controllers/functionclass.php');
$dbutil = new DBController();
    $db = $dbutil->connectDB();
	 $fclass  = new FonctionClass();

if(isset($_POST['view_rappel1an'])){

$query = "SELECT * FROM ventes
  JOIN clients ON clients.client_id = ventes.vente_client
WHERE vente_statut =1 ORDER BY vente_id DESC
";							
$result = $db->query($query);
$output = '';
if($result->rowCount() > 0)
{
 while($row = $result->fetch())
 {
   $output .= '
	   <!-- Message -->
                                    
                                    <a href="#" class="py-3 px-2 border-bottom d-flex align-items-center text-decoration-none">
                                        <div class="user-img position-relative d-inline-block mr-2"> <span
                                                class="round text-white d-inline-block text-center rounded-circle bg-primary"><i
                                                    class="mdi mdi-email"></i></span>
                                        </div>
                                        <div class="w-75 d-inline-block v-middle pl-2">
                                            <h5 class="text-truncate mb-0">'.$row["client_nom"].' '.$row["client_prenoms"].'</h5>
                                           
                                              <span class="mail-desc font-12 text-truncate overflow-hidden text-nowrap d-block">
                                               Contact: '.$row["client_contact"].' / Email: '.$row["client_email"].' 
												</span>
                                      
                                    <span class="time font-12 text-truncate overflow-hidden text-nowrap d-block">
									'.$fclass->CalcAge(date('Y',strtoTime($row["client_birthday"])),date('Y')).' Ans
                                    </span>
                                        </div>
                                    </a>
  ';

 }
}
else{$output .= '<div class="drop-title">Aucun rappel</div>';}
     
$status_query = "SELECT * FROM ventes
  JOIN clients ON clients.client_id = ventes.vente_client
  WHERE vente_statut =1 ORDER BY vente_id DESC";
$result_query = $db->query($status_query);
$count = $result_query->rowCount();
$data = array(
    'notification_venterappel_1an' => $output,
    'count_notification_venterappel_1an'  => $count
			);

echo json_encode($data);

}

?>