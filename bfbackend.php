<?php
//header('Access-Control-Allow-Origin:http://client.runoob.com');
function myQuery1($query){
	$con = mysqli_connect("127.0.0.1","root","whbY52015");
	if(!$con){
		die('Could not connect: ' . mysql_error());
	}
	mysqli_query($con,"set names 'utf8'");
	mysqli_select_db($con,"sellsystem");
	$temp = mysqli_query($con,$query);
	mysqli_close($con);
	return $temp;
}

function myQuery2($query){
	$con = mysqli_connect("127.0.0.1","root","whbY52015");
	if(!$con){
		die('Could not connect: ' . mysql_error());
	}
	mysqli_query($con,"set names 'utf8'");
	mysqli_select_db($con,"sellsystem");
	$temp = mysqli_query($con, $query);
	$count = mysqli_num_rows($temp);
	$result = array();
	for($i = 0; $i<$count; $i++){
		$row = mysqli_fetch_array($temp, MYSQLI_ASSOC);
		array_push($result, $row);
	}
	mysqli_close($con);
	return $result;
}

function register($username, $userpwd, $useraccount, $payPwd, $useraddress, $phone){
	if($useraccount == ""){
		$query  = "INSERT INTO user VALUES(0, '$username', '$userpwd', '$useraccount', '$payPwd', '$useraddress', '$phone');";
		$res = myQuery1($query);
		if($res){
			echo "200";
		}
		else{
			echo "该用户已存在！";
		}
	} 
	else{
		$query = "SELECT payPwd FROM bank WHERE account = '$useraccount';";
		$res = myQuery2($query);
		if(count($res)==1){
			if($res[0]['payPwd'] == $payPwd){
				$query  = "INSERT INTO user VALUES(0, '$username', '$userpwd', '$useraccount', '$payPwd', '$useraddress', '$phone');";
				$res = myQuery1($query);
				if($res){
					echo "200";
				}
				else{
					echo "该用户已存在！";
				}
			}
			else{
				echo "支付密码错误！";
			}
		}
		else{
			echo "银行账户不存在！";
		}
	}
}

// function changeUserInfo($username, $infoname, $infovalue){
// 	$query = "UPDATE user SET $infoname = '$infovalue' WHERE username = '$username';";
// 	$res = myQuery1($query);
// 	if($res){
// 		echo "200";
// 	}
// 	else{
// 		echo "用户信息修改失败！";
// 	}
// }

function changeUserInfo($userID, $oldpwd, $username, $userpwd, $useraccount, $payPwd, $useraddress, $phone){
	echo "userID:".$userID."!";
	$userID = intval($userID);
	if($useraccount == ""){
		if($oldpwd == "" || $userpwd == ""){
			$query  = "UPDATE user SET username = '$username', useraccount = '$useraccount', payPwd = '$payPwd', useraddress = '$useraddress', phone = '$phone' WHERE userID = $userID;";
			$res = myQuery1($query);
			if($res){
				echo "1&";
				echo "200&";
				echo $userID."&".$oldpwd.$username.$userpwd.$useraccount.$payPwd.$useraddress.$phone;
			}
			else{
				echo "该用户已存在！";
			}
		}
		else{
			$query = "SELECT userpwd FROM user WHERE userID = $userID;";
			$res = myQuery2($query);
			if(count($res)==1){
				if($res[0]['userpwd'] == $oldpwd){
					$query  = "UPDATE user SET username = '$username', userpwd = '$userpwd', useraccount = '$useraccount', payPwd = '$payPwd', useraddress = '$useraddress', phone = '$phone' WHERE userID = $userID;";
					$res = myQuery1($query);
					if($res){
						echo "2&";
						echo "200&";
						echo $userID."&".$oldpwd.$username.$userpwd.$useraccount.$payPwd.$useraddress.$phone;
					}
					else{
						echo "个人信息修改失败！";
					}
				}
				else{
					echo "旧密码输入错误！";
				}
			}
		}
	} 
	else{
		$query = "SELECT payPwd FROM bank WHERE account = '$useraccount';";
		$res = myQuery2($query);
		if(count($res)==1){
			if($res[0]['payPwd'] == $payPwd){
				if($oldpwd == "" || $userpwd == ""){
					$query  = "UPDATE user SET username = '$username', useraccount = '$useraccount', payPwd = '$payPwd', useraddress = '$useraddress', phone = '$phone' WHERE userID = $userID;";
					$res = myQuery1($query);
					if($res){
						echo "3&";
						echo "200&";
						echo $userID."&".$oldpwd.$username.$userpwd.$useraccount.$payPwd.$useraddress.$phone;
					}
					else{
						echo "该用户已存在！";
					}
				}
				else{
					$query = "SELECT userpwd FROM user WHERE userID = $userID;";
					$res = myQuery2($query);
					if(count($res)==1){
						if($res[0]['userpwd'] == $oldpwd){
							$query  = "UPDATE user SET username = '$username', userpwd = '$userpwd', useraccount = '$useraccount', payPwd = '$payPwd', useraddress = '$useraddress', phone = '$phone' WHERE userID = $userID;";
							$res = myQuery1($query);
							if($res){
								echo "4&";
								echo "200&";
								echo $userID."&".$oldpwd.$username.$userpwd.$useraccount.$payPwd.$useraddress.$phone;
							}
							else{
								echo "个人信息修改失败！";
							}
						}
						else{
							echo "旧密码输入错误！";
						}
					}
				}
			}
			else{
				echo "支付密码错误！";
			}
		}
		else{
			echo "银行账户不存在！";
		}
	}
}

function getUserInfo($username){
	$query = "SELECT * FROM user WHERE username = '$username';";
	echo json_encode(myQuery2($query)[0]);
}

function signin($username, $userpwd){
	$query = "SELECT userpwd FROM user WHERE username = '$username';";
	$res = myQuery2($query);
	if(count($res)==1){
		if($res[0]['userpwd'] == $userpwd){
			echo "200";
		}
		else{
			echo "密码错误！";
		}
	}
	else{
		echo "该用户不存在！";
	}
}

function trade($username, $useraccount, $sellname, $sellaccount, $money, $ordercontent, $arrivingTime){
	$money = (double)$money;
	$query = "SELECT * FROM bank WHERE account = '$useraccount' and sumMoney >= $money;";
	$result_u = myQuery2($query);
	$query = "SELECT * FROM bank WHERE account = '$sellaccount';";
	$result_s = myQuery2($query);

	if(count($result_u) == 1 && count($result_s) == 1){
		$query = "UPDATE bank SET sumMoney = sumMoney - $money WHERE account = '$useraccount';";
		myQuery1($query);
		$query = "UPDATE bank SET sumMoney = sumMoney + $money WHERE account = '$sellaccount';";
		myQuery1($query);
	}
	else if(count($result_s) == 0){
		echo "卖家不存在！";
		return;
	}
	else{
		echo "您的余额不足！";
		return;
	}

	$query = "INSERT INTO orders VALUES(0, '$username', '$sellname', NOW(), '$arrivingTime', $money, '$ordercontent', 0);";
	$res = myQuery1($query);
	if($res){
		echo "200&";
		echo $money."&";
		echo(doubleval($result_u[0]['sumMoney'])-$money);
	}
	else{
		echo "交易失败！";
	}

	$str = str_replace(array(" ","{","}"), "", $ordercontent);
	$arr = explode(',', $str);
	for($i = 0; $i < count($arr); $i = $i + 2){
		$foodname = $arr[$i];
		$quantity = $arr[$i+1];
		$query = "UPDATE breakfast SET  total = total - $quantity WHERE foodname = '$foodname';";
		myQuery1($query);
		$query = "SELECT * FROM breakfast WHERE foodname = '$foodname';";
		$curquantity = myQuery2($query)[0]['total'];
		if($curquantity == 0){
			$query = "DELETE FROM breakfast WHERE foodname = '$foodname';";
			myQuery1($query);
		}
	}

}

function getMoney($account){
	$query = "SELECT * FROM bank WHERE account = '$account';";
	echo myQuery2($query)[0]['sumMoney'];
}

function getUserOrderList($username){
	$query = "SELECT * FROM orders WHERE username = '$username';";
	echo json_encode(myQuery2($query));
}

function getSellOrderList($sellname){
	$query = "SELECT * FROM orders WHERE sellname = '$sellname';";
	echo json_encode(myQuery2($query));
}

function getCurrentOrder($username){
	$query = "SELECT * FROM orders WHERE username = '$username' ORDER BY orderID DESC;";
	echo json_encode(myQuery2($query)[0]);
}

function setPreOrder($username, $sellname, $money, $preordercontent, $arrivingTime){
	$money = (double)$money;
	$query = "SELECT * FROM preorders WHERE username = '$username';";
	$res = myQuery2($query);
	if(count($res)>0){
		$query  = "UPDATE preorders SET sellname = '$sellname', sumMoney = '$money', content = '$preordercontent', arrivingTime = '$arrivingTime' WHERE username = '$username';";
		$res = myQuery1($query);
		if($res){
			echo "200";
		}
		else{
			echo "订单设置失败！";
		}
	}
	else{
		$query  = "INSERT INTO preorders VALUES('$username', '$sellname', $money, '$preordercontent', '$arrivingTime');";
		$res = myQuery1($query);
		if($res){
			echo "200";
		}
		else{
			echo "订单设置失败！";
		}
	}
}

function addbreakfast($foodname, $price, $total, $type, $isrcd){
	$price = (double)$price;
	$total = intval($total);
	if($isrcd == "1"){
		$isrcd = 1;
	}
	else if($isrcd == "0"){
		$isrcd = 0;
	}
	else{
		echo "类型错误！";
	}
	$query  = "INSERT INTO breakfast VALUES(0, '$foodname', $price, $total, '$type', $isrcd);";
	$res = myQuery1($query);
	if($res){
		echo "200";
	}
	else{
		echo "商品添加失败！";
	}
}

function deletebreakfast($foodname){
	$query = "DELETE FROM breakfast WHERE foodname = '$foodname';";
	$res = myQuery1($query);
	if($res){
		echo "200";
	}
	else{
		echo "商品删除失败！";
	}
}

// function changebreakfast($foodname, $infoname, $infovalue){
// 	$query = "UPDATE breakfast SET $infoname = '$infovalue' WHERE foodname = '$foodname';";
// 	$res = myQuery1($query);
// 	if($res){
// 		echo "200";
// 	}
// 	else{
// 		echo "商品信息修改失败！";
// 	}
// }

function changebreakfast($foodID, $foodname, $price, $total, $type, $isrcd){
	$foodID = intval($foodID);
	$price = (double)$price;
	$total = intval($total);
	if($isrcd == "1"){
		$isrcd = 1;
	}
	else if($isrcd == "0"){
		$isrcd = 0;
	}
	else{
		echo "类型错误！";
	}
	$query  = "UPDATE breakfast SET foodname = '$foodname', price = $price, total = $total, type = '$type', isrcd = $isrcd WHERE foodID = $foodID;";
	$res = myQuery1($query);
	if($res){
		echo "200";
	}
	else{
		echo "修改商品信息失败！";
	}
}

function getbreakfast($type){
	$query = "SELECT * FROM breakfast WHERE type = '$type';";
	echo json_encode(myQuery2($query));	
	// $query = "SELECT * FROM breakfast WHERE type = '面食';";
	// echo json_encode(myQuery2($query));
	// $query = "SELECT * FROM breakfast WHERE type = '粥类';";
	// echo json_encode(myQuery2($query));
	// $query = "SELECT * FROM breakfast WHERE type = '糕点';";
	// echo json_encode(myQuery2($query));
	// $query = "SELECT * FROM breakfast WHERE type = '饮料';";
	// echo json_encode(myQuery2($query));
	// $query = "SELECT * FROM breakfast WHERE type = '其他';";
	// echo json_encode(myQuery2($query));
}

function getPreOrder($username){
	$query = "SELECT * FROM preorders WHERE username = '$username';";
	echo json_encode(myQuery2($query)[0]);
}

function addrecommend($title, $content){
	$query  = "INSERT INTO recommend VALUES(0, '$title', '$content', NOW());";
	$res = myQuery1($query);
	if($res){
		echo "200";
	}
	else{
		echo "推荐内容添加失败！";
	}
}

function getrecommend(){
	$query = "SELECT * FROM recommend ORDER BY articleID DESC;";
	echo json_encode(myQuery2($query)[0]);
}

function acceptorder($orderID){
	$query = "UPDATE orders SET state = 1 WHERE orderID = $orderID;";
	$res = myQuery1($query);
	if($res){
		echo "200";
	}
	else{
		echo "商家确认订单失败！";
	}
}

header('Access-Control-Allow-Origin:http://client.runoob.com');

switch ($_POST['method']){
	case 'register':
		register($_POST['username'], $_POST['userpwd'], $_POST['useraccount'], $_POST['payPwd'], $_POST['useraddress'], $_POST['phone']);
		break;
	case 'getUserInfo':
		getUserInfo($_POST['username']);
		break;
	case 'signin':
		signin($_POST['username'], $_POST['userpwd']);
		break;
	case 'trade':
		trade($_POST['username'], $_POST['useraccount'], $_POST['sellname'], $_POST['sellaccount'], $_POST['money'], $_POST['ordercontent'], $_POST['arrivingTime']);
		break;
	case 'getMoney':
		getMoney($_POST['account']);
		break;
	case 'getUserOrderList':
		getUserOrderList($_POST['username']);
		break;
	case 'getSellOrderList':
		getSellOrderList($_POST['sellname']);
		break;
	case 'getCurrentOrder':
		getCurrentOrder($_POST['username']);
		break;
	case 'setPreOrder':
		setPreOrder($_POST['username'], $_POST['sellname'], $_POST['money'], $_POST['preordercontent'], $_POST['arrivingTime']);
		break;
	case 'getPreOrder':
		getPreOrder($_POST['username']);
		break;
	case 'addbreakfast':
		addbreakfast($_POST['foodname'], $_POST['price'], $_POST['total'], $_POST['type'], $_POST['isrcd']);
		break;
	case 'getbreakfast':
		getbreakfast($_POST['type']);
		break;
	case 'addrecommend':
		addrecommend($_POST['title'], $_POST['content']);
		break;
	case 'getrecommend':
		getrecommend();
		break;
	case 'changebreakfast':
		changebreakfast($_POST['foodID'], $_POST['foodname'], $_POST['price'], $_POST['total'], $_POST['type'], $_POST['isrcd']);
		break;
	case 'changeUserInfo':
		changeUserInfo($_POST['userID'], $_POST['oldpwd'], $_POST['username'], $_POST['userpwd'], $_POST['useraccount'], $_POST['payPwd'], $_POST['useraddress'], $_POST['phone']);
		break;
	case 'deletebreakfast':
		deletebreakfast($_POST['foodname']);
		break;
	case 'acceptorder':
		acceptorder($_POST['orderID']);
		break;
	default:
		echo "404";
		break;
}
?>


