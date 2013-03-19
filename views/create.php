<div class="page-header">
    <h1>Create Recipe</h1>
</div> 

 <?php
       
	    $form_attributes = array('name'=>'create_form','class'=>'well','onsubmit'=>'return validate_create()');
        //echo form_open('/create/success', $form_attributes);		
		echo form_open_multipart('/create/success', $form_attributes);
        $recipe_name = array('name'=>'recipe_name','id'=>'recipe','class'=>'span3','placeholder'=>'Name your recipe');
        $description = array('name'=>'description','id'=>'desc','class'=>'span3','placeholder' => 'Describe briefly about recipe');
        $ingredient_name = array('name'=>'ingredient_name','id'=>'ingred','class'=>'ingredients_group','placeholder' => 'Give an ingredient name ...');
        $button_attr = array('name'=>'add_button','id'=>'add','class'=>'button_group');
        $insrtuctions = array('name'=>'instructions','id'=>'instructions','class'=>'span3','placeholder' => 'Give the preparation instructions', 'cols' => '70', 'rows' => '15');
        $prep_time = array('name'=>'prep_time','id'=>'prep_time','class'=>'span3','placeholder'=>'Give the preparation time');
        $tags = array('name'=>'tags','id'=>'tags','class'=>'span3','placeholder' => 'Give relevant tags');
        $servings = array('name'=>'servings','id'=>'servings','class'=> 'span3','placeholder' => 'How many servings?');
        $button = array('add', 'remove');
		echo '<div id="create_recipe">';           
        echo form_label("Recipe Name* ", "recipe");    echo form_input($recipe_name);
        echo '<span id="msgbox" style="display:none"></span>';
	    echo '<span id="recipe_msgbox"></span>'; echo '<br/>';
        echo form_label("Short Description ", "desc"); echo form_input($description); 
   		//echo '<span id="desc_msgbox"></span>';
		echo '<hr/>';
		echo '<div id="addIngredients" style=" width: inherit; background-color: #FFFFF5">';
		echo '<span id="done_msgbox"></span>';
        echo '<h4 class="x4-headline" style="font-family: serif">Specify Ingredients in your recipe</h4> ';
        echo '<div id="TextBoxesGroup"><div id="TextBoxDiv1">';
        echo '<label for="textbox1">Main Ingredient* </label>';
		echo '<input type="text" id="textbox1" name="textbox1" value="" onblur="checkIngredientName(1)" width="40"/>';
		echo '<span id="msgbox1" style="display:none" style="position:absolute"></span>'; 
		echo '<label for="qtybox1">&nbsp;&nbsp;Quantity* </label>';
		echo '<input type="text" id="qtybox1" name="qtybox1" value="" onblur="checkQty(1)" placeholder="(E.g.: 1, 2.5)"/>';
		
		$unitType = array();
		$options = array('' => 'Select unit');
        foreach ($unit_types as $units):
        $options[$units->unit_name] = $units->unit_name;
				$unitTypes[] = $units->unit_name;
        endforeach;
		$ut = "id='unitbox1' onChange=checkUnit(1)";
        echo form_dropdown('unitbox1',$options, set_value('unitbox1'), $ut); 
		
		echo '<span id="ingredient_msgbox"></span>';
		echo '<p><i><b>Feel free to add as many supplementary ingredients as necessary.</b> </i></p>';
        echo '</div></div>'; echo '<br/>';
        echo '<label style="font-style:italic;"> Do you want to add more ingredients? </label>'; echo '<br/>';
        echo '<input type="button" value="Yes" id="addButton">';
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
        echo '<input type="button" value="Check" id="getButtonValue" disabled="disabled" onmouseover="tooltip.pop(this, "Hello")">'; echo '  <i>Check for recipes with similar ingredients</i><br/>';
		echo '<div id="ingredientMatch"></div>'; echo '<br/>';
        echo '</div>';
		echo '<hr/>';
        echo form_label("Instructions* ", "instructions"); echo form_textarea($insrtuctions); 
		echo '<span id="instructions_msgbox"></span>'; echo '<br/>';
		echo '<hr/>';
        echo form_label("Preparation Time* ", "prep_time"); echo form_input($prep_time);
		echo '<span><i>&nbsp;mins</i></span>';
		echo '<span id="prep_time_msgbox"></span>'; echo '<br/>';
        echo form_label("Tags: ", "tags"); echo form_input($tags);  
		echo '<span><i>&nbsp;&nbsp;Please seperate your tags by comma (,).</i></span>'; echo '<br/>';
        echo form_label("Servings ", "servings"); echo form_input($servings); 
		echo '<span id="servings_msgbox"></span>'; echo '<br/>';
		echo '<hr/>';
        echo form_label("Cuisine Type* "); 
            
        $options = array('' => 'Select one');
        foreach ($cuisine_types as $cuisine) :
        $options[$cuisine->cuisineId] = $cuisine->cuisineName;
        endforeach;
		
        echo form_dropdown('cuisine_type',$options, set_value('cuisine_type')); 
	    echo '<span id="cuisine_msgbox"></span>';
		echo '<br/>'; 
		echo '<hr/>';
		// Photo upload
		echo '<label"> Add a picture?</label>'; echo '<br/>';
		echo '<input type="file" name="userfile" size="20" accept="image/jpeg"/>    <i>.jpeg only</i> <span id="upload_msgbox"></span><br />';
		echo '<hr/>';
		echo '<p align="center"> I agree to the term & conditions of the website.  I further certify that I have all rights to upload the picture for this recipe. </p>';
        echo '<p align="center"><input class="btn btn-info" name="create_button" id="create_button" type="submit" value="Create Recipe" align="absmiddle"> </p>';
		echo '</div>';
		
		echo '
    <div id="popupContact" style="display: none;">  
        <a id="popupContactClose">x</a>  
        <h1>Did you mean any of these recipes?</h1>  
        <p id="contactArea">  
              
        </p>  
    </div>  
    <div id="backgroundPopup"></div>';
		echo form_close();

    ?>
	