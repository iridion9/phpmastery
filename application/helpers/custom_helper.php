<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('getUserLang'))
{
  function getUserLang()
  {
     $defaultLang='eng'; //fre
	 $CI = & get_instance();
     $defaultLang=($CI->session->userdata('userLang')) ? $CI->session->userdata('userLang') : $defaultLang;
     return $defaultLang;
  }
}  

  function formatTelePhone($telePhoneNumber=NULL,$lastPartLength=4)
  {
      $return=$telePhoneNumber;
	  $telePhoneNumber=trim($telePhoneNumber);
      if($telePhoneNumber)
	  {
	      $pos=strlen(strpos($telePhoneNumber,'-'));
	      if($pos>4)
		  { return $telePhoneNumber;}
		  $length=strlen($telePhoneNumber);
		  $start = $length - $lastPartLength;
		  $firstPart=substr($telePhoneNumber , 0, $start);
		  $lastPart = substr($telePhoneNumber , $start ,$lastPartLength);
		  if(strlen(trim($firstPart))>3)
		  {
		    $pos=strlen(strpos($firstPart,'-'));
			if(!$pos)
			{ 
			  $firstLength=strlen(trim($firstPart));
		      $firstLastLength = $firstLength - 3;
			  
			  $first_firstPart=substr($firstPart , 0, 3);
			  $first_lastPart = substr($firstPart ,3,$firstLength);
			  $firstPart=$first_firstPart.'-'.$first_lastPart;
			}
		  }
		  $return = $firstPart.'-'.$lastPart;
	  }
	 return $return;	  
  }
  
  function formatPostalCode($postalCode=NULL)
  {
    $return=$postalCode;
    if(strlen(trim($postalCode))==6)
	{
	   $postalCodeLength=strlen(trim($postalCode));
	   $postalCodeLastLength = $postalCodeLength - 3;
	   $firstPart=substr($postalCode , 0, 3);
	   $lastPart = substr($postalCode ,3,$postalCodeLength);
	   $return=$firstPart.' '.$lastPart;
	}
	return $return;
  }
  
if (! function_exists('export_array_to_csv'))
{
	function export_array_to_csv($results=array())
	{
		$csv_terminated = "\r\n";
		$csv_separator = ",";
		$csv_separator_replace_by=';';
		$csv_enclosed = '"';
		$csv_escaped = "\\";
		
		// Gets the data from the database
		//$results = $results //$this->db->query($sql_query);
		//$fields_cnt = $result->num_fields($result);
		$fields_cnt=count($results[0]);
		$schema_insert = '';
		if(is_array($results) && count($results))
		{
			$fields = $results[0];//$result->field_data(); 
			foreach($fields as $field)
			{
			  $field=str_replace($csv_separator,$csv_separator_replace_by,$field);
			  $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, stripslashes($field)) . $csv_enclosed;
			  $schema_insert .= $l;
			  $schema_insert .= $csv_separator;
			} // end foreach
			$out = trim(substr($schema_insert, 0, -1));
			$out .= $csv_terminated;
		}
		array_shift($results);
		if(is_array($results) && count($results))
		{
			// Format the data
			foreach($results as $row)
			{
			  $schema_insert = '';
				$j=0;
				foreach($row as $val)
				{
					$val= trim($val);
					$val=str_replace($csv_separator,$csv_separator_replace_by,$val);
					if ($val == '0' || $val != '')
					{
					  if ($csv_enclosed == '')
					  {
						$schema_insert .= $val;
					  } 
					  else
					  {
						$schema_insert .= $csv_enclosed . 
						str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $val) . $csv_enclosed;
					  }
					}
					else
					{
						$schema_insert .= '';
					}
					if ($j < $fields_cnt - 1)
					{
						$schema_insert .= $csv_separator;
					}
				   $j++;	
				} // end foreach
				$out .= $schema_insert;
				$out .= $csv_terminated;
			} // end foreach
		 }	
	   return $out;
	}
}

if (! function_exists('export_data'))
{
	 function export_data($out=NULL,$file_name="export")
	 {
		 header('Content-Encoding: UTF-8');
		 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		 header("Content-Length: " . strlen($out));
		 header("content-type:application/csv;charset=UTF-8;encoding=UTF-8");
		// header("Content-type: application/vnd.ms-excel; name='excel'");
		// header("Content-type: text/x-csv");
		 header("Content-Disposition: filename=".$file_name.".csv");
		 header("Pragma: no-cache");
		 header("Expires: 0");
		 print $out;
		 exit;
	  }
}

if (! function_exists('save_file'))
{
   function save_file($content="",$file_name="")
   {
     $file_name=($file_name) ? $file_name : "file_".time().".txt";
     file_put_contents($file_name,$content);
   }
}	

if (! function_exists('convert_line_breaks'))
{
	 function convert_line_breaks($string, $line_break=PHP_EOL) {
				$patterns = array("/(<br>|<br \/>|<br\/>)\s*/i","/(\r\n|\r|\n)/");
				$replacements = array(PHP_EOL,$line_break);
				$string = preg_replace($patterns, $replacements, $string);
				return $string;
			}
}

if (! function_exists('makeoptionsdb'))
{
   function makeoptionsdb($objects=array() , $variable='' , $value='', $selected='')
    {
  		$options = '';
		if(is_array($objects))
		{
			foreach ($objects as $object)
			{
			  $val= (trim($object[$variable])); 
			  $text=addslashes((trim($object[$value]))); 
			  if($selected && is_array($selected) && count($selected))
			  {
			    if(in_array(trim($val),$selected))
					$options .= '<option value="' . $val. '" selected>' . $text.'</option>';
				  else
					$options .= '<option value="' . $val . '">'.$text.'</option>';
			  }
			  else
			  {
				  if(strcmp(trim($selected),trim($val))===0)
					$options .= '<option value="' . $val. '" selected>' . $text.'</option>';
				  else
					$options .= '<option value="' . $val . '">'.$text.'</option>';
			  }		
			}
  		}
	    return $options;
    }
}

if (! function_exists('makeoptions'))
{	
    function makeoptions($objects , $variable=NULL, $value=NULL,$selected='',$prestring=NULL,$poststring=NULL,$class='')
  	{
  		$options = '';
  		if(is_array($objects)){
	    	foreach ($objects as $objectkey => $objectvalue)
			{
			   if($prestring)
			     $objectvalue =  $prestring.''.$objectvalue;
			   if($poststring)
			     $objectvalue =  $objectvalue.''.$poststring;
			   
			   $objectkey = ($variable=='value') ? $objectvalue : $objectkey;
			   $_class=($class=='value') ? $objectkey : '';
			   
			   if($selected && is_array($selected) && count($selected))
			  {
			    if(in_array(trim($objectkey),$selected))
					$options .= '<option '.(($_class) ? 'class="'.$_class.'"' : '').' value="' . $objectkey . '" selected="selected">' . $objectvalue .'</option>';
			      else
			         $options .= '<option '.(($_class) ? 'class="'.$_class.'"' : '').' value="' . $objectkey . '">' . $objectvalue .'</option>';
			  }
			  else
			  {
				  if(strcmp(trim($selected),trim($objectkey))===0)
  				    $options .= '<option '.(($_class) ? 'class="'.$_class.'"' : '').' value="' . $objectkey . '" selected="selected">' . $objectvalue .'</option>';
			      else
			         $options .= '<option '.(($_class) ? 'class="'.$_class.'"' : '').' value="' . $objectkey . '">' . $objectvalue .'</option>';
			  }
			}
  		}
  		return $options;
	}
}

if(!function_exists('number_array'))
{
  function number_array($min=0,$max=10,$step=1,$order='asc')
  {
    $number_array='';
    if($order=='asc')
	{
		for($i=$min;$i<=$max;$i+=$step)
		{
		  $number_array[$i]=$i;
		}
	}
	else if($order=='desc')
	{
	  for($i=$max;$i>=$min;$i-=$step)
		{
		  $number_array[$i]=$i;
		}
	}
	return $number_array;
  }
}

if (! function_exists('current_url'))
{
	function current_url()
	{
		if (!isset($_SERVER['REQUEST_URI'])) {
			$serverrequri = $_SERVER['PHP_SELF'];
		} else {
			$serverrequri = $_SERVER['REQUEST_URI'];
		}
		/**
		 * server request uri depends upon server OS version
		 */
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		/**
		 * get protocal if SSL enabled.
		 */
		$serverprotocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
		$serverport = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
		/**
		 * Page might be run with IP and Port also like 127.0.0.1:8080
		 */
		return $serverprotocol . "://" . $_SERVER['SERVER_NAME'] . $serverport . $serverrequri;
	}
}

if (! function_exists('isValidEmail'))
{
	function isValidEmail($email)
	{
		$filtered_email = filter_var($email, FILTER_VALIDATE_EMAIL);
		if ($filtered_email !== false) {
			return true;
		} else {
			return false;
		}
    }
}		

if (! function_exists('isValidURL'))
{
	function isValidUrl($url)
	{
	   $filtered_url = filter_var($url, FILTER_VALIDATE_URL);
		if ($filtered_url !== false) {
			return true;
		} else {
			return false;
		}
    }
}

if (! function_exists('generateRandomString'))
{
    // alphanum,alphanumAZ,alphanumaz,num,alphaAZaz,alphaAZ,alphanaz
    function generateRandomString($length=10,$type='alphanum') {
		$charsaz = "abcdefghijklmnopqrstuvwxyz";
		$charsAZ = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$num = "123456789";
		switch($type)
		{
		  case 'alphanumAZ':
		    $chars = $charsAZ.$num;
		  break;
		  case 'alphanumaz':
		    $chars = $charsaz.$num;
		  break;
		  case 'alphaAZaz':
		    $chars = $charsaz.$charsAZ;
		  break;
		  case 'alphanumAZaz':
		    $chars = $charsaz.$charsAZ.$num;
		  break;
		  case 'alphaAZ':
		    $chars =$charsAZ;
		  break;
		  case 'alphaaz':
		    $chars = $charsaz;
		  break;
		  case 'num':
		    $chars = $num;
		  break;
		  default:
		    $chars = $charsaz.$charsAZ.$num;
		  break;
		}
			
		$str='';
		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}
		
		return $str;
	}
	
	function error($message='',$text_align='left',$tag='div')
	{
	   return formatMessage('error',$message,$text_align,$tag);
	}
	
	function success($message='',$text_align='left',$tag='div')
	{
	   return formatMessage('success',$message,$text_align,$tag);
	}
	
	function formatMessage($type='error',$message='',$text_align='left',$tag='div')
	{ 
	   $return="";
	   $align="text-left";
	   if($text_align=='center')
	     $align="text-center";
	   if($text_align=='right')
	   	 $align="text-right";
	   
	   $message_type="error";
	   if($type=='success')
	     $message_type="success";
	   if($type=='warning')
	   	 $message_type="text-warning";
	   if($type=='info')
	   	 $message_type="text-info";
	    if($type=='muted')
	   	 $message_type="text-muted";	  
	   	 	 
	   switch($tag)
	   {
	     default:
		 case 'div':
		   $return='<div class="'.$message_type.' '.$align.'">'.$message.'</div>';
		 break;
		 
	   }
	   return $return;
	}
}

