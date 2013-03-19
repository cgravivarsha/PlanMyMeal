<style type="text/css">

blockquote, q {  
	quotes:"" "";  
} 
 
a{  
	cursor: pointer;  
	text-decoration:none;  
} 
 
br.both{  
	clear:both;  
}  

#backgroundPopup{  
	display:none;  
	position:fixed;  
	_position:absolute; /* hack for internet explorer 6*/  
	height:100%;  
	width:100%;  
	top:0;  
	left:0;  
	background:#000000;  
	border:1px solid #cecece;  
	z-index:1;  
}  

#popupContact{  
	display:none;  
	position:fixed;  
	_position:absolute; /* hack for internet explorer 6*/  
	height:384px;  
	width:408px;  
	background:#FFFFFF;  
	border:2px solid #cecece;  
	z-index:2;  
	padding:12px;  
	font-size:13px;  
}  

#popupContact h1{  
	text-align:left;  
	color:#6FA5FD;  
	font-size:22px;  
	font-weight:700;  
	border-bottom:1px dotted #D3D3D3;  
	padding-bottom:2px;  
	margin-bottom:20px;  
}  

#popupContactClose{  
	font-size:14px;  
	line-height:14px;  
	right:6px;  
	top:4px;  
	position:absolute;  
	color:#6fa5fd;  
	font-weight:700;  
	display:block;  
}  

#button{  
	text-align:center;  
	margin:100px;  
}  
</style>

<script>
   
	var flag_ing_name = Array();
	var flag_qty = Array();
	var flag_unit = Array();
	
	var flag_upload = "true";
	
	flag_ing_name[1] = "false";
	flag_qty[1] = "false";
	flag_unit[1] = "false";
	
	//SETTING UP OUR POPUP  
	//0 means disabled; 1 means enabled;  
	var popupStatus = 0;  
	//Flag to check if all the ingredients entered by the user are correct
    var flag_ingredient_check = Array();
	//Setting it to "yes" when no ingredients have been enetered
    flag_ingredient_check[0] = "yes";
	var cnt = 0;
    // Autocomplete option for ingredients
    var autocomp_opt =
	{
         source: "<?php echo base_url();?>index.php/create/get_autocomplete_ingredients/",
         minLength: 2
    };
    
	//Function for ingredient autocomplete
  	$(function() 
	{
   		 $("#textbox1").autocomplete(autocomp_opt);
  	});
	//Removing the ingredients button on the fly
  	function removeButton(c) 
	{
		flag_ing_name[c] = "true";
		flag_qty[c] = "true";
		flag_unit[c] = "true";
		
		var len = flag_unit.length;
      	$("#TextBoxDiv" + c).remove();
		isDone();
  	}
 
   function checkIngredientName(c) 
   {
   		$.ajax(
			{
                type: "post",
                url: "<?php echo base_url();?>index.php/create/ingredient_name_check",
                cache: false,                
                data: {ingredient_name: $("#textbox"+c).val()},
                success: function (data) 	
				{	
          			if(data=='no') //if username not avaiable
          			{
            			flag_ingredient_check[c] = "no";
			            $("#msgbox"+c).fadeTo(200,0.1,function() //start fading the messagebox
            			{
			            	//add message and change the class of the box and start fading
            				$("#getButtonValue").attr("disabled", "disabled");
							flag_ing_name[c] = "false";
				            $("#msgbox"+c).html('&nbsp;&nbsp;Please select ingredients from one of the drop-down suggestions.').fadeTo(900,1).css("color", "#C00");
			            });
          			}
		            else
          			{
						flag_ing_name[c] = "true";
		                $("#msgbox"+c).fadeTo(200,0.1,function() //start fading the messagebox
          	            {
			                $("#msgbox"+c).html('&nbsp;✓').fadeTo(900,1).css("color", "#3C3");
							isDone();
           		     	});
          			}
            	}
        });
 
   }
 	function checkQty(c) 
	{
		match = /^(?:\d+(?:\.\d*)?|\.\d+)$/.test($("#qtybox"+c).val());
		if($("#qtybox"+c).val() == '') 
		{
			$("#getButtonValue").attr("disabled", "disabled");
			flag_qty[c] = "false";
			$("#msgbox"+c).html('Quantity must be filled.').fadeTo(900,1).css("color", "#C00");
		}
		else if(match == false) 
		{
			$("#getButtonValue").attr("disabled", "disabled");
			flag_qty[c] = "false";
			$("#msgbox"+c).html('Quantity should be a positive number.').fadeTo(900,1).css("color", "#C00");
		}
		else 
		{
			flag_qty[c] = "true";
			$("#msgbox"+c).empty();
			if($("#unitbox"+c).val() == '') 
			{
				$("#getButtonValue").attr("disabled", "disabled");
				$("#msgbox"+c).html('Unit must be selected.').fadeTo(900,1).css("color", "#C00");
			}
			else 
			{
				flag_unit[c] = "true";
				isDone();
			}
		}
	}
	
	function checkUnit(c) {
		var select_id = document.getElementById("unitbox"+c);
		var x = select_id.options[select_id.selectedIndex].value;
		if (x== '')
		{
			$("#getButtonValue").attr("disabled", "disabled");
			flag_unit[c] = "false";
			$("#msgbox"+c).html('Unit must be selected.').fadeTo(900,1).css("color", "#C00");
		}
		else
		{
			flag_unit[c] = "true";
			$("#msgbox"+c).empty();
			isDone();
		}
	}
	
	function isDone()
	{	
		var len = flag_unit.length;
		var flag_all_true = "true";
		
		while(len--)
		{
			if(len == 0)
			break;
			if(flag_ing_name[len] == "false" || flag_qty[len] == "false" || flag_unit[len] == "false")
			{
				flag_all_true = "false";
			}
			
			if(flag_all_true == "true")
			{
				$("#getButtonValue").removeAttr('disabled');
			}
			else
			{
				$("#getButtonValue").attr("disabled", "disabled");
			}
		}
	}
	
			//loading popup with jQuery magic!  
		function loadPopup(){  
		//loads popup only if it is disabled  
		if(popupStatus==0){  
		$("#backgroundPopup").css({  
		"opacity": "0.7"  
		});  
		$("#backgroundPopup").fadeIn("slow");  
		$("#popupContact").fadeIn("slow");  
		popupStatus = 1;  
		}  
		}  
	
		 //disabling popup with jQuery magic!  
		function disablePopup(){  
		//disables popup only if it is enabled  
		if(popupStatus==1){  
		$("#backgroundPopup").fadeOut("slow");  
		$("#popupContact").fadeOut("slow");  
		popupStatus = 0;  
		}  
		}  
		
				//centering popup  
		function centerPopup(){  
		//request data for centering  
		var windowWidth = document.documentElement.clientWidth;  
		var windowHeight = document.documentElement.clientHeight;  
		var popupHeight = $("#popupContact").height();  
		var popupWidth = $("#popupContact").width();  
		//centering  
		$("#popupContact").css({  
		"position": "absolute",  
		"top": windowHeight/2-popupHeight/2,  
		"left": windowWidth/2-popupWidth/2  ,
		"background": "#FFFFFF",
		"border": "2px solid #cecece"
		});  
		//only need force for IE6  
		  
		$("#backgroundPopup").css({  
		"height": windowHeight  
		});  
		  
		}  
	
  $(document).ready(function(){

    $('#recipe').blur(checkAvailability);
    var counter = 2;

	$("#popupContactClose").click(function(){  
		disablePopup();  
		});  
		
	$("#backgroundPopup").click(function(){  
		disablePopup();  
		}); 
		
	$(document).keypress(function(e){  
		if(e.keyCode==27 && popupStatus==1){  
		disablePopup();  
		}  
		});  
	
	
    $("#addButton").click(
        function () 
		{
            var inputTextBox = $(document.createElement('input')).attr("id", 'textbox' + counter).attr("type", 'text').attr("name", 'textbox' + counter).attr("onblur", 'checkIngredientName('+counter+')');
			var qtyTextBox = $(document.createElement('input')).attr("id", 'qtybox' + counter).attr("type", 'text').attr("name", 'qtybox' + counter).attr("onblur", 'checkQty('+counter+')').attr("placeholder", '(E.g.: 1, 2.5)');
						
			var unit_types = ["bag(s)","bar(s)","block(s)","botttle(s)","bunch(es)","can(s)","carton(s)","clove(s)","container(s)","cube(s)","cup(s)","drop(s)","gallon(s)","jar(s)","loaf(s)","ounce(s)","package(s)","piece(s)","pinch(s)","pint(s)","pound(s)","slice(s)","stick(s)","stripe(s)","tablespoon(s)","teaspoon(s)","whole(s)"];
						
			var unitDD = $(document.createElement('select')).attr("id", 'unitbox' + counter).attr("name", 'unitbox' + counter).attr("onchange", 'checkUnit('+counter+')');
			
			flag_ing_name[counter] = "false";
			flag_qty[counter] = "false";
			flag_unit[counter] = "false";
	
						
			unitDD.append('<option value="" selected="selected">Select Unit</option>');
			for (var i=0;i<unit_types.length;i++)
			{
				unitDD.append('<option value="'+unit_types[i]+'" >'+unit_types[i]+'</option>');
			}						
			
            var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
            newTextBoxDiv.html('<label for="textbox'+counter+'">Additional Ingredient </label>');
            newTextBoxDiv.append(inputTextBox);
			newTextBoxDiv.append('&nbsp;&nbsp;&nbsp;' +
                '<span id="msgbox' + counter + '" style="display:none" style="position:absolute">');
				
			newTextBoxDiv.append('<label for="qtybox'+counter+'">&nbsp;&nbsp;Quantity </label>');
			newTextBoxDiv.append(qtyTextBox);
			newTextBoxDiv.append(unitDD);
			
            newTextBoxDiv.append('&nbsp;&nbsp;&nbsp;' +
                '<input type="button" value="Remove" id="removeButton' +
                counter + '" onclick = "removeButton('+counter+')">');
            
            inputTextBox.autocomplete(autocomp_opt);
            newTextBoxDiv.appendTo("#TextBoxesGroup");
            counter++;
            
            $(function() 
			{
            	$( "#textbox" + counter ).autocomplete({
                     source: "<?php echo base_url();?>index.php/create/json/",
                     minLength: 2
                	});
              	});
         	});
 
     	$("#getButtonValue").click(function () 
		{
        	var userIngredientInput = [];
        	for(i=1; i<counter; i++){
            userIngredientInput.push($('#textbox' + i).val());
        }

        $.ajax({
                type: "post",
                url: "<?php echo base_url();?>index.php/create/find_ingredient_matches",
                cache: false,                
                data: {userIngredientInput: JSON.stringify(userIngredientInput)},
                success: function(response){
                        var obj  = jQuery.parseJSON( response );
							msg = '';
							cnt = cnt+1;
                            $('#contactArea').empty();
							var msg1 = '   The ingredients you entered are also contained in the following recipes: <br/> <br/>';
							var msg2 = '   You could check recipes already entered by other users to see if you wanted to enter the same recipe.  Or better, you could anyway give your own recipe.';
							$('#contactArea').append(" "+msg1);
                            $.each(obj, function(key, value) {    
                            //$('#contactArea').append(" "+value.recipeName);
							
							msg = msg + value;
                			$('#contactArea').append('<li> <ul> <a href="<?php echo base_url();?>index.php/search/enter_name_search1/'+value.recipeName+'"  target="_blank">'+value.recipeName+'</a> <br/> </ul> </li>');
							}
                        );
						
						$('#contactArea').append(" "+msg2);
						if(msg!='') {
						//centering with css  
						centerPopup();  
						//load popup  
						loadPopup(); 
						}
                },
                error: function(){                        
                    alert('Error while request..');
                }
    
             });
     	});
 
  
	 	$("#instructions").keypress(function()
		{
			$("#instructions_msgbox").empty();
		});
		$("#recipe").keypress(function()
		{
			$("#recipe_msgbox").empty();
		});
		$("#textbox1").keypress(function()
		{
			$("#ingredient_msgbox").empty();
			$("#done_msgbox").empty();
		});
		$("#TextBoxesGroup").keypress(function()
		{
			$("#done_msgbox").empty();
		});
		$("#prep_time").keypress(function()
		{
			$("#prep_time_msgbox").empty();
		});
		$("#cuisine_type").change(function()
		{
			$("#cuisine_msgbox").empty();
		});

});



function checkAvailability()
{
    var userName = $('#recipe').val();
    $.ajax({
        type: "post",
        url: "<?php echo base_url();?>index.php/create/get_recipe_match",
        cache: false,                
        data:'recipe=' + $("#recipe").val(),
        success: function(response){
                var obj  = jQuery.parseJSON( response );
                var msg1 = '   The recipe name you entered closely matched one or more recipes in PlanMyMeal: <br/> <br/>';
				var msg2 = '   You could check these recipes or you could anyway give your own recipe.';
				var msg='';
                $('#recipeMatch').empty();
                
				$('#contactArea').empty();
				$('#contactArea').append(" "+msg1);
				$.each(obj, function(key, value) 
				{    
					msg = msg + value;
                	$('#contactArea').append('<li> <ul> <a href="<?php echo base_url();?>index.php/search/enter_name_search1/'+value+'"  target="_blank">'+value+'</a> <br/> </ul> </li>');
                });
				
				$('#contactArea').append(" "+msg2);
				
				if(msg!='') {
					cnt = cnt+1;
				//centering with css  
				centerPopup();  
				//load popup  
				loadPopup();  
				}
		
   },
   error: function()
   {                        
   		alert('Error while request..');
   }
 
 });
}    
	

//Validation function for the form
function validate_create()
{
	var flag_is_null = "false";
	var flag_all_true = "true";
	var flag_entered_ingred = "false";
	var flag_entered_qty = "false";
	$("#recipe_msgbox").empty();
	$("#ingredient_msgbox").empty();
	$("#instructions_msgbox").empty();
	$("#prep_time_msgbox").empty();
	$("#prep_time_msgbox").empty();
	$("#servings_msgbox").empty();
	$("#cuisine_msgbox").empty();
	
	
	//Validation for reciep name
	var x=document.forms["create_form"]["recipe_name"].value;
	if (x==null || x=="")
  	{
		flag_all_true = "false";
		$("#recipe_msgbox").text("   Recipe name must be filled out").css("color", "#C00");
	}
	//Validation for ingredients
	x=document.forms["create_form"]["textbox1"].value;
	if(x==null || x=="")
	{
		flag_entered_ingred = "true";
		flag_all_true = "false";
		$("#ingredient_msgbox").text("   Ingredient must be filled").css("color", "#C00");
	}
	
	//Validation for unit of ingredients
	x=document.forms["create_form"]["unitbox1"].value;
	if(x == '' && flag_entered_qty == "false" && flag_entered_ingred == "false")
	{
		flag_all_true = "false";
		$("#ingredient_msgbox").text("   Unit of measure must be selected").css("color", "#C00");
	}
	
	//Validation for instructions
	x=document.forms["create_form"]["instructions"].value;
	if (x==null || x=="")
	{
  		flag_all_true = "false";
		$("#instructions_msgbox").text("   Instructions must be filled").css("color", "#C00");
    }
	//Validation for preparation time to check if it is null
	x=document.forms["create_form"]["prep_time"].value;
	if (x==null || x=="")
    {
  		flag_all_true = "false";
		flag_is_null = "true";
		$("#prep_time_msgbox").text("   Preparation time must be filled").css("color", "#C00");
	}
	//Validation for preparation time to check if it is not an integer
	if (x.toString().search(/^[0-9]+$/) != 0 && flag_is_null == "false")
    {
  		flag_all_true = "false";
		$("#prep_time_msgbox").text("   Preparation must be an integer").css("color", "#C00");
	}
	
	x=document.forms["create_form"]["servings"].value;
	if (x.toString().search(/^[0-9]+$/) != 0 && x!= "")
	{
  		flag_all_true = "false";
		$("#servings_msgbox").text("   Please enter servings in numbers").css("color", "#C00");
    }
	
	x=document.forms["create_form"]["cuisine_type"].value;
	if(x == '')
	{
		flag_all_true = "false";
		$("#cuisine_msgbox").text("   Cuisine type must be selected").css("color", "#C00");
	}
	if($("#getButtonValue").is(":disabled")) 
	{
		flag_all_true = "false";
		$(window).scrollTop($('#create_recipe').offset().top);
		$("#done_msgbox").text("   Invalid ingredient entry.  Please select proper ingredient from drop-down suggestions.").css("color", "#C00");
		
	}
	
	
	if(flag_upload == "false")
	{
		flag_all_true = "false";
	}
	
	
	if(flag_all_true == "true")
	{
		return true;
	}
	else
	{
		return false;
	}
}
</script>