<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Create extends CI_Controller
{
    private $view_data = array();   
    private $recepie_list = array();
    private $search_data = array();
   
    function __construct()
    {
        parent::__construct();   
        $this->load->model('Create_model');
		$this->load->library('tank_auth');
    }
   
    function index()
    {
       $this->enter_recipe();
    }
   
    function get_recipe_match()
    {
        $userName = $this->input->post('recipe');
       
        $words_with_match = array();
        // input misspelled word
        
		
		//Peanut ice cream square
        $input = strtolower($userName);
       
        $result = $this->Create_model->get_all_recipe_names();
		
        $words = array();
        while($row = mysql_fetch_assoc($result))
        {
            $words[] = $row['recipeName'];
        }

        // no shortest distance found, yet
        $shortest = -1;

        // loop through words to find the closest
        foreach ($words as $word)
        {

            // calculate the distance between the input word,
            // and the current word
            $word_lower = strtolower($word);
            $explode = explode(" ", $word_lower);
            foreach($explode as $word_lower_explode)
            {
				$explode_input = explode(" ", $input);
				
				foreach($explode_input as $input_word)
				{
                	$lev = levenshtein($input_word, $word_lower_explode);
                	$strlen_explode = strlen($word_lower_explode);
               
                	if(($lev <= ($strlen_explode/3) ) && $strlen_explode >= 4)
                	{
                   		$words_with_match[] = $word;
                    	break;
                	}
       
                	// check for an exact match
                	if ($lev == 0)
                	{
                    	// closest word is this one (exact match)
						$words_with_match[] = $word;
                    	$closest = $word;
                    	$shortest = 0;
                    	// break out of the loop; we've found an exact match
                    	break;
                	}
				}
				//break;
            }

            // if this distance is less than the next found shortest
            // distance, OR if a next shortest word has not yet been found
            if ($lev <= $shortest || $shortest < 0)
            {
                $closest  = $word;
                $shortest = $lev;
            }
        }
		
		$words_with_match = array_unique($words_with_match);
       
        //Restricting the number of outputs to 5
        $words_with_match = array_slice($words_with_match, 0, 5);
        echo json_encode($words_with_match);
    }
   
//Function to get the ingredients as autocomplete drop down suggestion   
    function get_autocomplete_ingredients()
    {
          if(isset($_GET['term']))
          {
			$ingredient = strtolower($_GET['term']);
  			
            $result = $this->Create_model->get_similar_ingredient_name($ingredient);
            $result_array = array();
            while($row = mysql_fetch_assoc($result))
            {
                $result_array[] = $row['ingredientName'];
            }
            echo json_encode($result_array);
          }            
    }
   
    function enter_recipe()
    {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login');
		} else {
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['is_logged_in'] = $this->tank_auth->is_logged_in();
			$data['active_bar'] = 'Create';
			
			$this->view_data['cuisine_types'] = $this->Create_model->get_cuisine();
			$this->view_data['unit_types'] = $this->Create_model->get_unit();
			
			$this->load->view('header');
			$this->load->view('navbar', $data);
			$this->load->view('create_js', $this->view_data);
			$this->load->view('container_start');
	        $this->load->view('create', $this->view_data);
			$this->load->view('container_end');
			$this->load->view('footer');
		}
        
    }
	function success()
	{
		// Load the file
		$fullpath = $_FILES['userfile']['tmp_name'];		
		if($fullpath != "")
		{
			$fullpath = $_FILES['userfile']['tmp_name'];		
			$handle = fopen($fullpath, "rb");
			$img = fread($handle, filesize($fullpath));
			fclose($handle);
		
			// Convert to base64 and load into db
			$img = base64_encode($img);
			//echo $img;
		}
		else
		{
			$img = "";
		}

		$recipe_name = $this->input->post('recipe_name');
    	$instructions = $this->input->post('instructions');
    	$prep_time = $this->input->post('prep_time');
        $cuisine_id = $this->input->post('cuisine_type');
		$short_desc = $this->input->post('description');
		$servings = $this->input->post('servings');
		$tags = $this->input->post('tags');
		
		$tag_list = array();
		$tag_list = explode( ",",$tags);
		
		$tag_list_trimmed = array();
		
		foreach($tag_list as $tag)
		{
			$tag = strtolower($tag);
			$tag_list_trimmed[] = trim($tag);
		}
	
		$ingredients = array();
		$quantity = array();
		$unit = array();
		
		foreach($_POST as $key=>$value)
		{
			if(substr($key,0,7) == "textbox")
			{
				$ingredients[] = $value;
			}
			if(substr($key,0,6) == "qtybox")
			{
				$quantity[] = $value;
			}
			if(substr($key,0,7) == "unitbox")
			{
				$unit[] = $value;
			}
		}
		
	
		$user_id = $this->tank_auth->get_username();
		date_default_timezone_set('US/Eastern');
		$currDate = date("Y-m-d");
		$time = time();
		$currTime = date('H:i:s', $time);
		$tag_id = array();
		
		$recipe_id = $this->Create_model->enter_recipe_db($recipe_name, $prep_time, $instructions, $cuisine_id, $img, $servings, $short_desc);
		$tag_id = $this->Create_model->enter_tags($recipe_id, $tag_list_trimmed);
		$this->Create_model->enter_tag_map($recipe_id, $tag_id);
		$this->Create_model->enter_recipe_ingredients($ingredients, $recipe_id, $quantity, $unit);
		$this->Create_model->enter_user_own_details($recipe_id, $currDate, $currTime, $user_id);
		
		$data['recipe_id'] = $recipe_id;
		$data['active_bar'] = 'Create';
		
		if ($this->tank_auth->is_logged_in()) {
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$data['is_logged_in'] = $this->tank_auth->is_logged_in();
		}
		
		$this->load->view('header');
		$this->load->view('navbar', $data);
		$this->load->view('container_start');
		$this->load->view('success', $data);
		$this->load->view('container_end');
		$this->load->view('footer');
}

		function view_photo($recipeid) 
		{	
				// $recipeid is the id from database
				// Read $img from database
				$db_img = base64_decode($img);		
				$db_img = imagecreatefromstring($db_img);
				
				if ($db_img !== false) {
					header("Content-Type: image/jpeg");
					imagejpeg($db_img);
					imagedestroy($db_img);
				}	
		}
   
    function find_ingredient_matches()
    {
        //Sample User Input
        $input = $_POST['userIngredientInput'];
        $input = json_decode($input, true);
       
        $temp = $this->Create_model->get_ingredient_ids($input);
        foreach($temp as $t) {
            $user_input[] = $t->ingredientId;
        }
               
        //Query that returns distinct recipeId
        $result_recipeId = $this->Create_model->get_all_recipeId();
       
        $outside_recipeid = array();
       
        //Looping on the distict recipeIds
        while($row = mysql_fetch_assoc($result_recipeId))
        {   
            $recipeID = $row['recipeId'];
            //Query that returns list of recipeIds and IngredientIds from the database
            $result = $this->Create_model->get_all_recipe_and_ingredient();
           
            //Array to store the ingredient list
            $ingredient_list = array();
           
            //Looping on the list of recipeIds from the database
            while($row_list = mysql_fetch_assoc($result))
            {
                if($row['recipeId'] == $row_list['recipeId'])
                {
                    array_push($ingredient_list, $row_list['ingredientId']);
                }
            }
           
            //Slicing to get the top 5 elements from both the user_input and the ingredient list from db
            $ingredient_list = array_slice($ingredient_list, 0, 5);
            $user_input = array_slice($user_input, 0, 5);
           
            //The common ingredients in user_input and ingredient list from db
            $common = array_intersect($ingredient_list, $user_input);
           
            //The count of the common elements
            $common_count = count($common);
           
            //No of ingredients entered by the user
            $user_count = count($user_input);
           
            //If the main ingredient matches
            if($ingredient_list[0] == $user_input[0])
            {
                $common_count = $common_count - 1;
                $user_count = $user_count - 1;
               
                //Then if 50% of the ingredient matches then the recipe has similar ingrdients
                if(($common_count/$user_count)>= 0.5)
                {
                    //'<br/>Recipe Id '.$row['recipeId'].' has similar ingredients<br/>'
                    $outside_recipeid[] = $recipeID;
                    //echo json_encode($recipeID);
                   
                }
            }
            //Main ingredient is different
            else
            {
                //If 70% of the ingredient matches then the recipe has similar ingrdients
                if(($common_count/$user_count)>= 0.7)
                {
                    //'<br/>Recipe Id '.$row['recipeId'].' has similar ingredients<br/>'
                    $outside_recipeid[] = $recipeID;
                    //echo json_encode($recipeID);
                }
            }
        }
       
        //
        $recipe_name = $this->Create_model->get_recipe_names($outside_recipeid);
        echo json_encode($recipe_name);
    }
   
    function ingredient_name_check()
    {
        $ingredient_name=$_POST['ingredient_name'];
        $result = $this->Create_model->get_ingredient_names();
        if (in_array($ingredient_name, $result))
        {
        //ingredient name is not available
            echo "yes";
        }
        else
        {
         //ingredient unavailable i.e. ingredient doesn't exists in array
         echo "no";
    	}
	}
   
}
?>