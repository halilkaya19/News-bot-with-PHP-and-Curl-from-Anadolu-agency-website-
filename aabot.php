<?php include ("../wp-config.php"); ?> 
<?php  
$cek = file_get_contents("http://www.aa.com.tr/tr/dunya");
preg_match_all('@<h3><a href="(.*?)">(.*?)</a></h3>@si',$cek,$link);

for($i=1;$i<=3;$i++){

$icerik_cek="http://www.aa.com.tr/".$link[1][$i];
$icerik_baglan=file_get_contents($icerik_cek);
preg_match_all('@<span class="category hide" style="display:none;">(.*?)<h3>(.*?)</h3>(.*?)<ul class="social-icons">@si',$icerik_baglan,$baslik);
preg_match_all('@<div class="news-spot">(.*?)</div>@si',$icerik_baglan,$spot);
preg_match_all('@<div class="news-spot">(.*?)</p>(.*?)</p>@si',$icerik_baglan,$icerik);
preg_match_all('@<img alt="" src="(.*?)" />@si',$icerik_baglan,$resim);
$bebek=$resim[1][0];

$url = $resim[1][0]; 
$ch =curl_init();  
curl_setopt($ch, CURLOPT_URL,$url);  
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);  
$data= curl_exec($ch);  
curl_close($ch);  

$img_name=md5($data); 

if(!file_exists("images/$img_name".".jpg")){ 

file_put_contents("images/$img_name".".jpeg",$data);   

$resim_son = 'http://'.$_SERVER['HTTP_HOST'].'/aabot/images/'.$img_name.'.jpeg'; 
}  

 $my_post = array(); 
  $my_post['post_title'] = $baslik[2][0]; 
  $my_post['post_name'] = 1;
  $my_post['post_excerpt'] = $spot[1][0];
  $my_post['post_content'] = $icerik[0][0];
  $my_post['post_status'] = 'publish'; 
  $my_post['post_author'] = 1; 

 
 
 
 
  $kontrol    =   @mysql_num_rows(mysql_query("Select * from ".$wpdb->prefix."postmeta WHERE meta_value='$bebek'")); 

 if($kontrol == '0'){ 
     
        $post_id = wp_insert_post($my_post); 
		add_post_meta($post_id, 'resim', $resim_son, true);  
		
		add_post_meta($post_id, 'url', $resim[1][0], true); 
         
            echo '<strong>Eklendi:</strong> '.$bebek; echo '<br />';

                           }else{
						   
			echo '<strong>Zaten Eklenmis:</strong> '.$bebek; echo '<br />';	   
						   }
 }

?>