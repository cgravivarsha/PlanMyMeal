</div>
<!-- Carousel
================================================== -->
<div id="myCarousel" class="carousel slide">
  <div class="carousel-inner">
    <div class="item active">
      <img src="<?php echo base_url(); ?>assets/img/slide-01.jpg" alt="">
      <div class="container">
        <div class="carousel-caption">
          <h1 style="text-shadow:black 0.1em 0.1em 0.2em;">Search by Ingredients</h1>
            <p class="lead" style="text-shadow:black 0.1em 0.1em 0.2em;">You can specify ingredients here</p>
            <br />
            
            <?php
            	$form_attributes = array('id'=>'f1','name'=>'f1','onsubmit'=>'return validate_ingredient()');
        			echo form_open(base_url().'index.php/search/enter_ingredient_search', $form_attributes); ?>
            <table>
              <!--<caption>Search by Ingredients</caption>-->
              <thead>
                <tr>
                  <th><span class="badge badge-success">Includes</span></th>
                  <th>&nbsp;&nbsp;</th>
                  <th><span class="badge badge-important">Excludes</span></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input type="text" id="ingredient1" name="ingredient1"></td>
                  <td></td>
                  <td><input type="text" id="exingredient1" name="exingredient1"></td>
                </tr>
                <tr>
                  <td><input type="text" id="ingredient2" name="ingredient2"></td>
                  <td></td>
                  <td><input type="text" id="exingredient2" name="exingredient2"></td>
                </tr>
                <tr>
                  <td><input type="text" id="ingredient3" name="ingredient3"></td>
                  <td></td>
                  <td><input type="text" id="exingredient3" name="exingredient3"></td>
                </tr>
                <tr>
                  <td><input type="text" id="ingredient4" name="ingredient4"></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td><input type="text" id="ingredient5" name="ingredient5"></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
            <button type="submit" class="btn btn-large btn-primary">Search</button>
            </form> 
        </div>
      </div>
    </div>
    <div class="item">
      <img src="<?php echo base_url(); ?>assets/img/slide-02.jpg" alt="">
      <div class="container">
        <div class="carousel-caption">
          <h1 style="text-shadow:black 0.1em 0.1em 0.2em;">Search by Recipe Name</h1>
            <p class="lead" style="text-shadow:black 0.1em 0.1em 0.2em;">You can specify recipe name here</p>
            <br />
            <?php
            	$form_attributes = array('name'=>'recipe_name_form','onsubmit'=>'return validate_recipeName()');
        			echo form_open(base_url().'index.php/search/enter_name_search', $form_attributes); ?>
              <input class="span3" id="s_name" name="s_name" type="text" /><br />
              <button type="submit" class="btn btn-large btn-primary">Search</button>
            </form>
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
        </div>
      </div>
    </div>
    <div class="item">
      <img src="<?php echo base_url(); ?>assets/img/slide-03.jpg" alt="">
      <div class="container">
        <div class="carousel-caption">
          <h1 style="text-shadow:black 0.1em 0.1em 0.2em;">Search by Cuisine Type</h1>
            <p class="lead" style="text-shadow:black 0.1em 0.1em 0.2em;">You can specify cuisine type here</p>
            <br />
            <?php
            $form_attributes = array('name'=>'cuisine_form','onsubmit'=>'return validate_cuisine()');
        		echo form_open(base_url().'index.php/search/from_search_form', $form_attributes); 
        		$options = array('' => 'Select one');
        		foreach ($cuisine_types as $cuisine) :
        		$options[$cuisine->cuisineId] = $cuisine->cuisineName;
        		endforeach;
		
        		echo form_dropdown('cuisine_type',$options, set_value('cuisine_type'));?>
      				<br />
              <button type="submit" class="btn btn-large btn-primary">Search</button>
            </form>
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
            <br />
        </div>
      </div>
    </div>
  </div>
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
</div><!-- /.carousel -->