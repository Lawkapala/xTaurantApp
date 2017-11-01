<?php
	session_start();
	
	Class Customers {
		private $db_obj;
		
		function __construct() {
			require_once '../config/dbconn.php';
			
			$this->db_obj = new DBConfig();
		}
		
		function get_customers($id = 0) {
			$customers = array("status" => 0, "msg" => "No Customers found");
			
			$sql = "SELECT c.cid, c.title, c.surname, c.middlename, c.firstname, c.gender, c.cardno, c.enroleeid, c.phoneno1, c.phoneno2, c.email1, c.email2, c.rship_type, c.rship_account, c.dob, c.age, c.address, c.address_area, c.address_state, c.occupation, 
									 c.dob_day, c.dob_month, c.nok_fname, c.nok_lname, c.nok_email, c.nok_phone, c.nok_relationship, ch.casenote
						FROM sc_customers c
							left join sc_chistory ch on c.cid = ch.cid";
						
			if($id != 0)
				$sql .= " WHERE c.cid=?;";
			
			try {
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				
				if($id != 0)
					$stmt->bind_param('d', $id);
				
				$stmt->execute();
				$stmt->bind_result($cid, $title, $sname, $mname, $fname, $gender, $cardno, $enroleeid, $phone1, $phone2, $email1, $email2, $rship_type, $rship_acct, $dob, $age, $address, $area, $state, $occupation, 
												$dob_day, $dob_month, $nok_fname, $nok_lname, $nok_email, $nok_phone, $nok_relationship, $casenote);
				
				while($stmt->fetch()){
					$result[] = array("cid" => $cid,
												"title" => $title,
												"fname" => $fname,
												"mname" => $mname,
												"lname" => $sname,
												"gender" => $gender,
												"cardno" => $cardno,
												"enroleeid" => $enroleeid,
												"phone1" => $phone1,
												"phone2" => $phone2,
												"email1" => $email1,
												"email2" => $email2,
												"rship_type" => $rship_type,
												"rship_account" => $rship_acct,
												"dob" => $dob,
												"dob_day" => $dob_day,
												"dob_month" => $dob_month,
 												"age" => $age,
												"address" => $address,
												"address_area" => $area,
												"address_state" => $state,
												"occupation" => $occupation,
												"nok_fname" => $nok_fname, 
												"nok_lname" => $nok_lname, 
												"nok_email" => $nok_email, 
												"nok_phone" => $nok_phone, 
												"nok_relationship" => $nok_relationship, 
												"casenote" => $casenote);
				}
				
				if(isset($result))
					$customers = $result;
				
				mysqli_close($conn);
			}
			catch (Exception $e){
				
			}
			
			return $customers;
		}		
		
		function search_all($term) {
			$customers = array("status" => 0, "msg" => "No Results found");
			$term = '%'.$term.'%';
			
			$sql = "SELECT cid, title, surname, middlename, firstname, gender, cardno, enroleeid, phoneno1, phoneno2, email1, email2, rship_type, rship_account, dob, age, address, address_area, address_state, occupation, nok_fname
						FROM sc_customers
						WHERE concat(ifnull(cid, ''),  ' ',
												ifnull(title, ''),  ' ',
												ifnull(surname, ''), ' ',  
												ifnull(middlename, ''),  ' ', 
												ifnull(firstname, ''),  ' ',
												ifnull(gender, ''),  
												ifnull(cardno, ''),  
												ifnull(enroleeid, ''),  
												ifnull(phoneno1, ''),  
												ifnull(phoneno2, ''),  
												ifnull(email1, ''),  
												ifnull(email2, ''),  
												ifnull(rship_type, ''),  
												ifnull(rship_account, ''),  
												ifnull(dob, ''),  
												ifnull(age, ''),  
												ifnull(address, ''),  
												ifnull(address_area, ''),  
												ifnull(address_state, ''),  
												ifnull(occupation, ''),  
												ifnull(nok_fname, '')
									) LIKE ?
						ORDER BY surname, firstname
						LIMIT 0, 10;";
									
			try {
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('s', $term);
				$stmt->execute();
				$stmt->bind_result($cid, $title, $sname, $mname, $fname, $gender, $cardno, $enroleeid, $phone1, $phone2, $email1, $email2, $rship_type, $rship_acct, $dob, $age, $address, $area, $state, $occupation, $nok);
				
				while($stmt->fetch()){
					$result[] = array("cid" => $cid,
												"title" => $title,
												"fname" => $fname,
												"mname" => $mname,
												"lname" => $sname,
												"gender" => $gender,
												"cardno" => $cardno,
												"enroleeid" => $enroleeid,
												"phone1" => $phone1,
												"phone2" => $phone2,
												"email1" => $email1,
												"email2" => $email2,
												"rship_type" => $rship_type,
												"rship_account" => $rship_acct,
												"dob" => $dob,
												"age" => $age,
												"address" => $address,
												"address_area" => $area,
												"address_state" => $state,
												"occupation" => $occupation,
												"nok" => $nok);
				}
				
				if(isset($result))
					$customers = $result;
				
				mysqli_close($conn);
			}
			catch (Exception $e){
				
			}
			
			return $customers;
		}
	
		function add_customer(){
			$_POST = array_map( 'stripslashes', $_POST );
			
			$title = $_POST["title"];
			$lname = $_POST["lname"];
			$mname = $_POST["mname"];
			$fname = $_POST["fname"];
			$gender = $_POST["gender"];
			$cardno = $_POST["cardno"];
			$enroleeid = $_POST["cardno"];
			$phone1 = $_POST["phone1"];
			$phone2 = $_POST["phone2"];
			$email1 = $_POST["email1"];
			$email2 = $_POST["email2"];
			$rship_type = $_POST["rship_type"];
			$rship_account = $_POST["rship_account"];
			//$dob = $_POST["dob"];
			$dob_day = $_POST["dob_day"];
			$dob_month = $_POST["dob_month"];
			$age = $_POST["age"];
			$address = $_POST["address"];
			$area = $_POST["area"];
			$state = $_POST["state"];
			$occupation = $_POST["occupation"];
			$nok_fname = $_POST["nok_fname"];
			$nok_lname = $_POST["nok_lname"];
			$nok_email = $_POST["nok_email"];
			$nok_phone = $_POST["nok_phone"];
			$nok_relationship = $_POST["nok_rel"];
			
			$sql = "INSERT INTO sc_customers (title, surname, middlename, firstname, gender, cardno, enroleeid, 
												phoneno1, phoneno2, email1, email2, rship_type, rship_account, dob_day, dob_month, age, address, address_area, address_state, occupation, 
												nok_fname, nok_lname, nok_phone, nok_email, nok_relationship)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
			
			try{
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('sssssssssssssssssssssssss', $title, 
																			  $lname, 
																			  $mname,
																			  $fname, 
																			  $gender, 
																			  $cardno, 
																			  $enroleeid, 
																			  $phone1, 
																			  $phone2,
																			  $email1,
																			  $email2,
																			  $rship_type,
																			  $rship_account, 
																			  $dob_day,
																			  $dob_month,
																			  $age,
																			  $address,
																			  $area,
																			  $state,
																			  $occupation,
																			  $nok_fname,
																			  $nok_lname,
																			  $nok_phone,
																			  $nok_email,
																			  $nok_relationship);
				if($stmt->execute() ){
					$msg = array("status" => 1, "msg" => "Your record inserted successfully");
				}
				else {
					$msg = array("status" => 0, "msg" => "Error: " . $sql ."". mysqli_error($conn)); 
				}
				
				mysqli_close($conn);
			}
			catch(Exception $e){
				$msg = array("status" => 0, "msg" => "Error: " . $e->message() . "");
			}		
			
			return array_filter($msg);
		}
		
		function update_customer($id){	
			$_POST = array_map( 'stripslashes', $_POST );
			
			$title = $_POST["title"];
			$lname = $_POST["lname"];
			$mname = $_POST["mname"];
			$fname = $_POST["fname"];
			$gender = $_POST["gender"];
			$cardno = $_POST["cardno"];
			$enroleeid = $_POST["cardno"];
			$phone1 = $_POST["phone1"];
			$phone2 = $_POST["phone2"];
			$email1 = $_POST["email1"];
			$email2 = $_POST["email2"];
			$rship_type = $_POST["rship_type"];
			$rship_account = $_POST["rship_account"];
			//$dob = $_POST["dob"];
			$dob_day = $_POST["dob_day"];
			$dob_month = $_POST["dob_month"];
			$age = $_POST["age"];
			$address = $_POST["address"];
			$area = $_POST["area"];
			$state = $_POST["state"];
			$occupation = $_POST["occupation"];
			$nok_fname = $_POST["nok_fname"];
			$nok_lname = $_POST["nok_lname"];
			$nok_phone = $_POST["nok_phone"];
			$nok_email = $_POST["nok_email"];
			$nok_relationship = $_POST["nok_rel"];
			
			$sql = "UPDATE sc_customers 
						SET title = ?, 
							   surname = ?, 
							   middlename = ?, 
							   firstname = ?, 
							   gender = ?, 
							   cardno = ?, 
							   enroleeid = ?, 
							   phoneno1 = ?, 
							   phoneno2 = ?, 
							   email1 = ?, 
							   email2 = ?, 
							   rship_type = ?, 
							   rship_account = ?, 
							   dob_day = ?,
							   dob_month = ?,
							   age = ?, 
							   address = ?, 
							   address_area = ?, 
							   address_state = ?, 
							   occupation = ?, 
							   nok_fname = ?,
							   nok_lname = ?,
							   nok_phone = ?,
							   nok_email = ?,
							   nok_relationship = ?,
							   date_updated = now()
						WHERE cid = ?;";
			
			try{
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('sssssssssssssssssssssssssd', $title, 
																			  $lname, 
																			  $mname,
																			  $fname, 
																			  $gender, 
																			  $cardno, 
																			  $enroleeid, 
																			  $phone1, 
																			  $phone2,
																			  $email1,
																			  $email2,
																			  $rship_type,
																			  $rship_account, 
																			  $dob_day,
																			  $dob_month,
																			  $age,
																			  $address,
																			  $area,
																			  $state,
																			  $occupation,
																			  $nok_fname,
																			  $nok_lname,
																			  $nok_phone,
																			  $nok_email,
																			  $nok_relationship,
																			  $id);
				if($stmt->execute() ){
					$msg = array("status" => 1, "msg" => "Your record udpated successfully");
				}
				else {
					$msg = array("status" => 0, "msg" => "Error: " . $sql ."". mysqli_error($conn)); 
				}
				
				mysqli_close($conn);
			}
			catch(Exception $e){
				$msg = array("status" => 0, "msg" => "Error: " . $e->message() . "");
			}		
			
			return array_filter($msg);
		}
	
		function update_customer_by_doctor($id){
			$_POST = array_map( 'stripslashes', $_POST );
			
			$age = $_POST["age"];
			$occupation = $_POST["occupation"];
			
			$sql = "UPDATE sc_customers 
						SET age = ?, 
							   occupation = ?
						WHERE cid = ?;";
			
			try{
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ssd', $age,
														$occupation,
														$id);
				if($stmt->execute() ){
					$msg = array("status" => 1, "msg" => "Your record udpated successfully");
				}
				else {
					$msg = array("status" => 0, "msg" => "Error: " . $sql ."". mysqli_error($conn)); 
				}
				
				mysqli_close($conn);
			}
			catch(Exception $e){
				$msg = array("status" => 0, "msg" => "Error: " . $e->message() . "");
			}		
			
			return array_filter($msg);
		}
	
		function delete_customer($id) {
			$msg = array("status" => 1, "msg" => "Deleted "+ $id);
			
			return $msg;
		}
	
		function book_appointment(){
			$_POST = array_map( 'stripslashes', $_POST );
			
			$card_no = $_POST["ccard"];
			$date = $_POST["cdate"];
			$notes = $_POST["cnotes"];
			$status = "New";
			$branch = $_SESSION['sc_branch'];
			
			$sql = "INSERT INTO sc_appointments (customer_cardno, appointment_date, appointment_notes, appointment_status, appointment_branch, date_created)
						VALUES (?, ?, ?, ?, ?, now());";
			
			try{
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('sssss', $card_no,
														 $date,
														 $notes,
														 $status,
														 $branch);
				if($stmt->execute() ){
					$msg = array("status" => 1, "msg" => "Appointment was booked successfully");
				}
				else {
					$msg = array("status" => 0, "msg" => "Error: " . $sql ."". mysqli_error($conn)); 
				}
				
				mysqli_close($conn);
			}
			catch(Exception $e){
				$msg = array("status" => 0, "msg" => "Error: " . $e->message() . "");
			}		
			
			return array_filter($msg);
		}
		
		function get_appointment($id = 0) {
			$customers = array("status" => 0, "msg" => "No Customers found");
			
			$sql = "SELECT a.appointment_id, a.customer_cardno, a.appointment_date, a.appointment_notes, a.appointment_status, a.appointment_branch, a.date_created, concat(c.title, ' ', c.surname, ' ', c.firstname) as 'fullname', c.cid 
						FROM sc_autosys_2.sc_appointments a
							LEFT JOIN sc_autosys_2.sc_customers c ON a.customer_cardno = c.cardno
						WHERE appointment_branch = ? AND a.appointment_date = curdate()
						ORDER BY case when a.appointment_status = 'New' then 1
												  when a.appointment_status = 'Open' then 2
												  when a.appointment_status = 'Closed' then 3
												  else 4
										   end asc, a.appointment_date desc";
						
			if($id != 0)
				$sql = "SELECT a.appointment_id, a.customer_cardno, a.appointment_date, a.appointment_notes, a.appointment_status, a.appointment_branch, a.date_created, concat(c.title, ' ', c.surname, ' ', c.firstname) as 'fullname', c.cid
							FROM sc_autosys_2.sc_appointments a
								LEFT JOIN sc_autosys_2.sc_customers c ON a.customer_cardno = c.cardno
							WHERE appointment_branch = ? AND a.appointment_id = ? AND a.appointment_date = curdate()
							ORDER BY case when a.appointment_status = 'New' then 1
													  when a.appointment_status = 'Open' then 2
													  when a.appointment_status = 'Closed' then 3
													  else 4
											   end asc, a.appointment_date desc";
			
			try {
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				
				if($id != 0){
					$stmt->bind_param('sd',  $_SESSION['sc_branch'], $id);
				}
				else
					$stmt->bind_param('s',  $_SESSION['sc_branch']);
				
				$stmt->execute();
				$stmt->bind_result($id, $cardno, $date, $notes, $status, $branch, $date_created, $name, $cid);
				
				while($stmt->fetch()){
					$result[] = array("id" => $id,
												"cardno" => $cardno,
												"date" => $date,
												"notes" => $notes,
												"status" => $status,
												"branch" => $branch,
												"fname" => $name,
												"cid" => $cid
												);
				}
				
				if(isset($result))
					$customers = $result;
				
				mysqli_close($conn);
			}
			catch (Exception $e){
				
			}
			
			return $customers;
		}		
		
		function update_appointment_status($id, $status){			
			$sql = "UPDATE sc_appointments 
						SET appointment_status = ?
						WHERE appointment_id = ?";
			
			try{
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('sd', $status, $id);
				if($stmt->execute() ){
					$msg = array("status" => 1, "msg" => "Your record udpated successfully");
				}
				else {
					$msg = array("status" => 0, "msg" => "Error: " . $sql ."". mysqli_error($conn)); 
				}
				
				mysqli_close($conn);
			}
			catch(Exception $e){
				$msg = array("status" => 0, "msg" => "Error: " . $e->message() . "");
			}		
			
			return array_filter($msg);
		}
		
		function doctors_examination(){
			//$_POST = array_map( 'stripslashes', $_POST );
			
			$sql = "INSERT INTO `sc_diagnosis`
						(`complain`, `pxohx`, `pxmhx`, `pxfohx`, `pxfmhx`, `lee`, 
						 `va_unaided_r_far`, `va_unaided_r_near`, `va_unaided_l_far`, `va_unaided_l_near`, 
						 `va_aided_r_far`, `va_aided_r_near`, `va_aided_l_far`, `va_aided_l_near`, 
						 `va_pinhole_r_far`, `va_pinhole_r_near`, `va_pinhole_l_far`, `va_pinhole_l_near`,
						`old_spec_r`, `old_spec_l`, `iop_r`, `iop_l`, `near`,
						`ar_sph_cyl_x_axis_r`, `ar_sph_cyl_x_axis_l`, `sub_sph_cyl_x_axis_r`, `sub_sph_cyl_x_axis_l`, `sub_add_r`, `sub_add_l`, `sub_va_r`, `sub_va_l`,
						`fb_sph_cyl_x_axis_r`, `fb_sph_cyl_x_axix_l`, `fb_add_r`, `fb_add_l`, `fb_va_r`, `fb_va_l`,
						`lids`, `conjuctiva`, `cornea`, `anterior_chamber`, `iris`, `pupil`, `lens`, `colour_vision`, `ee_others`,
						`vitreous`, `choroid`, `retina`, `macular`, `disc`, `osle_others`,
						`diagonis`, `plan`, `prescription`, `comments`, `customer_id`, `customer_cardno`, `date_created`)
						VALUES
						(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now());
				
			";
			
			try{
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ssssssssssssssssssssssssssssssssssssssssssssssssssssssssds', 
												$_POST['chiefcomplain'], $_POST['pxohx'], $_POST['pxmhx'], $_POST['pxfohx'], $_POST['pxfmhx'], $_POST['lee'],
												$_POST['va_far_unaided_r'], $_POST['va_near_unaided_r'], $_POST['va_far_unaided_l'], $_POST['va_near_unaided_l'],
												$_POST['va_far_aided_r'], $_POST['va_near_aided_r'], $_POST['va_far_aided_l'], $_POST['va_near_aided_l'],
												$_POST['va_far_pinhole_r'], $_POST['va_near_pinhole_r'], $_POST['va_far_pinhole_l'], $_POST['va_near_pinhole_l'],
												$_POST['ospr'], $_POST['ospl'], $_POST['iopr'], $_POST['iopl'], $_POST['ospn'],
												$_POST['sph_cyl_x_axis_r'], $_POST['sph_cyl_x_axis_l'], $_POST['sub_sph_cyl_x_axis_r'], $_POST['sub_sph_cyl_x_axis_l'], 
												$_POST['sub_add_r'], $_POST['sub_add_l'], 
												$_POST['sub_va_r'], $_POST['sub_va_l'],
												$_POST['fb_sph_cyl_x_axis_r'], $_POST['fb_sph_cyl_x_axis_l'], $_POST['fb_add_r'], $_POST['fb_add_l'], $_POST['fb_va_r'], $_POST['fb_va_l'],
												$_POST['libs'], $_POST['con'], $_POST['cornea'], $_POST['antc'], $_POST['iris'], $_POST['pupl'], $_POST['lens'], $_POST['colv'], $_POST['oth'],
												$_POST['vitr'], $_POST['chor'], $_POST['ret'], $_POST['mac'], $_POST['disc'], $_POST['oth1'], 
												$_POST['diag'], $_POST['plan'], $_POST['presc'], $_POST['comments'], 
												$_POST['cid'], $_POST['c_cardno']);
				if($stmt->execute() ){
					$msg = array("status" => 1, "msg" => "Your record inserted successfully");
				}
				else {
					$msg = array("status" => 0, "msg" => "Error: " . $sql ."". mysqli_error($conn)); 
				}
				
				mysqli_close($conn);
			}
			catch(Exception $e){
				$msg = array("status" => 0, "msg" => "Error: " . $e->message() . "");
			}		
			
			return array_filter($msg);
		}
	
		function add_dependant(){
			$_POST = array_map( 'stripslashes', $_POST );
			
			$lname = $_POST["lname"];
			$fname = $_POST["fname"];
			$gender = $_POST["gender"];
			$phone1 = $_POST["phone"];
			$email1 = $_POST["email"];
			$rship = $_POST["rship"];
			$primary_acct = $_POST["primary"];
			$primary_id = $_POST["pri_id"];
						
			$sql = "INSERT INTO sc_customers (fname, lname, gender, relationship, phone, email, primary_acct, primary_cid, date_created)
						VALUES (?, ?, ?, ? ?, ?, ?, ?, now());";
			
			try{
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('sssssssd', $fname,
											  $lname,
											  $gender,
											  $rship,
											  $phone1,
											  $email1,
											  $primary_acct,
											  $primary_id);
				
				if($stmt->execute() ){
					$msg = array("status" => 1, "msg" => "Your record inserted successfully");
				}
				else {
					$msg = array("status" => 0, "msg" => "Error: " . $sql ."". mysqli_error($conn)); 
				}
				
				mysqli_close($conn);
			}
			catch(Exception $e){
				$msg = array("status" => 0, "msg" => "Error: " . $e->message() . "");
			}		
			
			return array_filter($msg);
		}
		
	}
?>