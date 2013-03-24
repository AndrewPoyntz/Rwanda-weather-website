<?php
header('Content-type: application/json');
require_once("class_lib.php");
$id = $_GET['warnId'];
if (isset($id)){
	$warning = new warning($id);
} else {
	$warning = new warning();
}
$action = $_GET["action"];
$out = array();
switch ($action) {
	case "list":
		// TODO move this into the class, & make this a one liner
		$warningList = array();
		$warnings = $db->query("SELECT * FROM warnings");
		while ($warning = $warnings->fetch()) {
			$warningList[] = array(
				'id' => $warning['id'],
				'title' => stripslashes($warning['title']),
				'description' => stripslashes($warning['description']),
				'validFrom' => date('jS M,  H:i', strtotime($warning['valid_from'])),
				'validTo' => date('jS M, H:i', strtotime($warning['valid_to'])),
				'type' => $warning['type']
			);
		}
		$out["warnings"] = $warningList;
	break;
	case "add":
		$title = $_GET['warnTitle'];
		$description = $_GET['warnDescription'];
		$validFrom = $_GET['warnValidFrom'];
		$validTo = $_GET['warnValidTo'];
		$type = $_GET['warnType'];
		$result = $warning->add($title, $description, $validFrom, $validTo, $type);
		if ($result){
			$out["result"] = true;
		} else {
			$out["result"] = false;
		}
	break;
	case "update":
		$id = $_GET['warnId'];
		$title = $_GET['warnTitle'];
		$description = $_GET['warnDescription'];
		$validFrom = $_GET['warnValidFrom'];
		$validTo = $_GET['warnValidTo'];
		$type = $_GET['warnType'];
		$result = $warning->update($id, $title, $description, $validFrom, $validTo, $type);
		if ($result){
			$out["result"] = true;
		} else {
			$out["result"] = false;
		}
	break;
	case "delete":
		$out["result"] = ($warning->delete($id) ? true : false);		
	break;
	default:
		$out["result"] = false;
}
echo json_encode($out);	
?>