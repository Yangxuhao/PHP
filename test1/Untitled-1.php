<?php

$arr=array("method"=>$method,"timestamp"=>time(),"status"=>200,"error"=>"","time_useed"=>1);
$response=array("exe_success"=>1,"code"=>0,"uid"=>"");
//接收数据
$phone=htmlspecialchars($request['phone']);
$password=htmlspecialchars($request['password']);
$verify=htmlspecialchars($request['verify']);
$user_name=htmlspecialchars($request['Uuser_name']);

if($phone=="" or $password=="" or $verify==""){
	$response["exe_success"]=0;
	$arr["status"]=401;
	$arr["error"]="incomplete data";
	goto error;
	}
$con=mysql_con();
if(!$con){
	$response["exe_success"]=0;
	$arr["status"]=401;
	$arr["error"]="mysql connect error";
	goto error;
	}
	//判断手机号是否存在
$pho_res=mysql_query("select * from user where user_count='$phone'");
$pho_value=mysql_fetch_assoc($pho_res);
if($pho_value){
	$response["exe_success"]=0;
	$response["code"]=2;
	$arr["status"]=501;
	$arr["error"]="phone num has been registered";
	}
	//查询验证码
	$verfy_result=mysql_query("select * from user_register where register_phone='$phone'");
	$verify_value=mysql_fetch_assoc($verfy_result);
	if(!$verify_value){
		$response['exr_success']=0;
		$arr["status"]=502;
		$arr["error"]="verify code has notsend";
		goto error;
		}
		//判断验证码是否过期
	$time=time();
	if($time-$verify_value["send_time"]>600){
		$response["exe_success"]=0;
		$arr["status"]=402;
		$arr["error"]="verification code expires";
		goto error;
		}
		//判断用户名是否为空
		if($user_name==""or is_null($user_name)){
			//??????????????????????
			$user_name="u".$phone;
			
			}
			//判断验证码
			if($verify_value['verifycode']!=$verify){
		$response["exe_success"]=0;
		$arr["status"]=403;
		$arr["error"]="verify error";
		goto error;
				}
		//将用户信息插入数据库
		$user_status=1;
		$lastest_time=time();
		$default_logo="default_logo.jpg";
		$level_id=1;
		$insert_result=mysql_query("insert into user(user_account,user_pwd,user_nickname,user_status,latest_login_device,latest_login_time,user_image,level_id) values ('$phone','$password'.'$user_name','$user_status','$sn','$lastest_time','$default_logo','$level_id')");
		$result=mysql_query("select * from user where user_account='$phone'");
		$new_res=mysql_fetch_assoc($result);
		if(!$new_res){
			$response["exe_success"]=0;
		$arr["status"]=503;
		$arr["error"]="insert error";
		goto error;
			}
			//返回
			$response['exe_success']=1;
			$response['code']=1;
			$response['uid']=$new_res["user_id"];
			
			mysql_close($con);
			
			error;
			$arr["response"]=$response;
		
	
	
	
	
	
	
	
	
	

