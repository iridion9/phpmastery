<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('output'))
{
	function output($layout = null , $data = array() , $isleft = false , $isright = false)
	{
		$CI = & get_instance();
		$output = $CI->load->view($CI->config->item('template').'header' , $data , true);
		if($isleft)$output .= $CI->load->view($CI->config->item('template').'left' , $data , true);
		$output .= $layout;
		if($isright)$output .= $CI->load->view($CI->config->item('template').'right' , $data , true);
		$output .= $CI->load->view($CI->config->item('template').'footer', $data , true);
		$CI->output->set_output($output);
	    return $output;
   }	   
}

if (! function_exists('permission_denied'))
{
	function permission_denied($err_msg=NULL)
	{
	    $CI = & get_instance();
	    $data = getCommonData(); //$CI->common_model->getPageCommonData();
	    $data['title']='Permission Denied';
	    $data['content']=($err_msg) ? $err_msg: 'Permission Denied';
	    $output = $CI->load->view('error/accessibility_error',$data, true);
	    output($output , $data);	
	    return;
	}
}

if (! function_exists('please_login'))
{
	function please_login($err_msg=NULL)
	{
		$CI = & get_instance();
		$data = getCommonData(); //$CI->commonmodel->getPageCommonData();
		$data['title']='Please Login';
		$data['content']=($err_msg) ? $err_msg: 'Please Login';
		$data['refno']=0;
		$url_to_redirect=$CI->uri->uri_string();
		if(trim($url_to_redirect)!='account/login')
			$CI->session->set_userdata('url_to_redirect',$url_to_redirect);
	   		$data["extraheader"]='<style type="text/css">.leftmenu{display:none;}    #sdw_box,#top_box_bt,#main_content_pageincomenbot,#main_content_pageincomentop,#main_content_left_box{display:none;}#iner_content_box2b(float:none;margin:auto;)</style>';
	   		$output = $CI->load->view('error/authentication_error',$data, true);
	   		output($output , $data);	
			return;
		}	
}

if (! function_exists('page_not_found'))
{
 	function page_not_found($err_msg=NULL)
	{
		$CI = & get_instance();
		$data = getCommonData(); //$CI->commonmodel->getPageCommonData();
		$data['title']='Page Not Found';
		$data['content']=($err_msg) ? $err_msg: 'Page Not Found';
		$data['refno']=0;
	   if(!$CI->session->isLoggedin($err_msg))
	   {
	   		$data["extraheader"]='<style type="text/css">.leftmenu,#main_content_left_box{display:none;}    #sdw_box,#top_box_bt,#main_content_pageincomenbot,#main_content_pageincomentop,#main_content_left_box{display:none;}#iner_content_box2b(float:none;margin:auto;)</style>';
	   }
   		$output = $CI->load->view('error/page_not_found_error',$data, true);
   		output($output , $data);	
   		return;
	}	
}

if (! function_exists('check_login'))
{
 	function check_login($err_msg=NULL)
 	{
    	 if(!isLoggedin())
    	 {
		     please_login($err_msg);
			 return false;
  		 }
		     else
		     return true;
		 }	
}

if (! function_exists('check_admin'))
{
	function check_admin($err_msg=NULL)
	{
		if(!isAdmin())
		{
			permission_denied($err_msg);
			return false;
		}
		else
			return true;
	}	
}

if (! function_exists('check_user_admin'))
{
	function check_user_admin($err_msg=NULL)
	{
	   if(!isUserAdmin())
	   {
	       permission_denied($err_msg);
	   	   return false;
	   }
	   else
	       return true;
	}	
}

if (! function_exists('getCommonData'))
{
    function getCommonData()
	{ 
	    $CI = & get_instance();
		$data = array();
		$data = $CI->common_model->getPageCommonData();
		return $data;
	}
}

if (! function_exists('getUserID'))
{
    function getUserID()
	{ 
	    $CI = & get_instance();
		$user=$CI->session->userdata(config_item('payer'));
		if($user)
		{
		    $userid=$user->id;
		    return $userid;
		}
		else
		   return false;  
	}
}

if (! function_exists('isAdmin'))
{
    function isAdmin()
	{ 
	    $CI = & get_instance();
		if(!$CI->session->isAdmin())
		   return false;
	    else
		   return true;
	}
}

if (! function_exists('isUserAdmin'))
{
    function isUserAdmin()
	{ 
	    $CI = & get_instance();
		if($CI->session->userType()=='user')
		   return true;
	   else
		   return false;
	}
}

if (! function_exists('isLoggedin'))
{
    function isLoggedin()
	{ 
	   $CI = & get_instance();
	   if(!$CI->session->isLoggedin())
	   {
		   return false;
	   }
	   else
		   return true;
	}
}

if (! function_exists('authorizeApp'))
{
    function authorizeApp($app_id='')
	{ 
	   $return=true;
	   if(!isAdmin())
	   {
	   
		   $CI = & get_instance();
		   $CI->load->model("application");
		   $app= $CI->application->find(array('userid'=>getUserID(),'id'=>$app_id));
		 
		   if(!$app)
		   {
		     $CI->load->model("user");
		     $app= $CI->user->find(array('id'=>getUserID(),'authenticated_app'=>$app_id));
			 if(!$app)
		       $return =false;
		   }	 
	    }
		return $return;		 
	}
}

