<?php
class Create_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
	
    function get_cuisine()
    {
        return $this->db->get('Cuisine')->result();
    }
	
	function get_unit()
    {
        return $this->db->get('Units')->result();
    }
	
	function enter_user_own_details($recipe_id, $currDate, $currTime, $user_id)
	{
		$query_str = 'INSERT into UserOwnsRecipe(recipeId, uploadDate, uploadTime , email) values(?, ?, ?, ?)';   
	    $this->db->query($query_str, array($recipe_id, $currDate, $currTime, $user_id));
	}
	
	function get_all_recipe_names()
	{
		$query = "SELECT recipeName FROM Recipe order by recipeName";
        return(mysql_query($query));
	}
	
	function get_similar_ingredient_name($ingredient)
	{
		$query = "SELECT ingredientName FROM Ingredients where LOWER(ingredientName) like '%".$ingredient."%'";
        return(mysql_query($query));
	}
	
	function get_all_recipeId()
	{
		$query_recipeId = "SELECT distinct recipeId from RecipeHasIngredients";
        return(mysql_query($query_recipeId));
	}
	
	function get_all_recipe_and_ingredient()
	{
		$query = "SELECT ingredientId, recipeId FROM RecipeHasIngredients order by recipeId, ingredientId";
        return(mysql_query($query));
	}
	
	function enter_recipe_ingredients($ingredients, $recipe_id, $quantity, $unit)
	{	
		$counter = -1;
		$weights = array(200, 90, 70, 50, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        foreach($ingredients as $ingName)
        {
        	$sql = "SELECT ingredientId FROM Ingredients WHERE ingredientName = '".$ingName."'"; 
			
	        $result = mysql_query($sql);
			while ($row = mysql_fetch_assoc($result)) 
		   	{
				$id = $row['ingredientId'];
			}
			$counter++;
			$query_str = 'INSERT into RecipeHasIngredients(ingredientId, recipeId, quantity , unit, weights) values(?, ?, ?, ?, ?)';   
	        $this->db->query($query_str, array($id, $recipe_id, $quantity[$counter], $unit[$counter], $weights[$counter]));
        }
	}
	
	
	function enter_tags($recipe_id, $tag_list)
	{  
        $result_array = array();
		
		$flag_present = "false";
		
    	$tags_in_recipe = array();
		
		foreach($tag_list as $tag)
		{
			$flag_present = "false";
			$query = "Select * from Tags";
	        $result = mysql_query($query);
			while ($row = mysql_fetch_assoc($result)) 
			{
				if(strcmp($tag,$row['tagName']) == 0)
				{
					$flag_present = "true";
					$frequency = $row['frequency'] + 1;
					$query_update = "UPDATE Tags SET frequency ='".$frequency."' WHERE tagName ='". $row['tagName']."'";
		        	$result_update = mysql_query($query_update);	
					$tags_in_recipe[] = $row['tagId'];
				}
			}
			
			if($flag_present == "false")
			{
				$query_str = 'INSERT into Tags(tagName, frequency) values(?, ?)';   
		        $this->db->query($query_str, array($tag, 1));		

				$id = mysql_insert_id();
				$tags_in_recipe[] = $id;
			}
        }
        return $tags_in_recipe;
	}
	
	function enter_tag_map($recipe_id, $tag_id)
	{
		foreach($tag_id as $id)
		{
			$query_str = 'INSERT into TagMap(recipeId , tagId) values(?, ?)';   
        	$this->db->query($query_str, array($recipe_id, $id));	

		}
	}
	
    function enter_recipe_db($recipe_name, $prep_time, $instructions, $cuisine_id, $img, $servings, $short_desc)
    {
        $query_str = 'INSERT into Recipe(recipeName, preparationTime, instructions , cuisineId, image, servings, description) values(?, ?, ?, ?, ?, ?, ?)';   
        $this->db->query($query_str, array($recipe_name, $prep_time, $instructions, $cuisine_id, $img, $servings, $short_desc));
		$id = mysql_insert_id();
		return $id;
    }
    function get_recipe()
    {
        return $this->db->get('Recipe')->result();
    }
   
    function get_ingredient_ids($igdnt_name_arr)
    {
        $sql = "SELECT ingredientId FROM Ingredients WHERE ";   
        foreach($igdnt_name_arr as $ig)
        {
            $sql .= "ingredientName = '".$ig."' OR ";
        }
        $sql = substr($sql,0,-3);
        $query = $this->db->query($sql);
        return $query->result();
    }
   
    function get_recipe_names($recipe_id_arr)
    {
        if(count($recipe_id_arr) != 0)
        {
            $sql = "SELECT recipeName FROM Recipe WHERE ";
            foreach($recipe_id_arr as $rd)
            {
                $sql .= "recipeId = '".$rd."' OR ";
            }
            $sql = substr($sql,0,-3);
            $query = $this->db->query($sql);
            return $query->result();
        }
    }
   
    function get_ingredient_names()
    {
        $query = "Select ingredientName from Ingredients";
        $result = mysql_query($query);
       
        $result_array = array();
        while ($row = mysql_fetch_assoc($result)) {
            $result_array[] = $row['ingredientName'];   
        }
        return $result_array;
    }
   
}
?>