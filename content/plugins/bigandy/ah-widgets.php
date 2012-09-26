<?php
/* Random Post Widget
 * http://www.makeuseof.com/tag/how-to-create-wordpress-widgets/
 * http://www.wproots.com/advanced-wordpress-widgets/
 */

 
 
class RandomPostWidget extends WP_Widget
{
  function RandomPostWidget()
  {
    $widget_ops = array('classname' => 'RandomPostWidget', 'description' => 'Displays a random post with thumbnail' );
    $this->WP_Widget('RandomPostWidget', 'AH Recent Posts', $widget_ops);
  }
 
  function form($instance)
  {
    $title = esc_attr($instance['title']);
    $select = esc_attr($instance['postNumber']);
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: </label>
  
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></p>
  
  <p>
    <label for="<?php echo $this->get_field_id('postNumber'); ?>">Select Number of Posts:</label>
    <select name="<?php echo $this->get_field_name('postNumber'); ?>" id="<?php echo $this->get_field_id('postNumber'); ?>" class="widefat">
            <?php
            $options = array(1,2,3,4,5,6,7,8,9);
            foreach ($options as $option) {
                echo '<option value="' . $option . '" id="' . $option . '"', $select == $option ? ' selected="selected"' : '', '>', $option, '</option>';
            }
            ?>
    </select>
    </p>
<?php
  }
 
 
 
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['postNumber'] = strip_tags($new_instance['postNumber']);
    
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? 'Recent Posts' : apply_filters('widget_title', $instance['title']);
    
    if (!empty($title))
      echo $before_title . $title . $after_title;
 
    // WIDGET CODE GOES HERE
    
     // going off on my own here  
       
       $outputPostNumber = $instance['postNumber'];
        $ah_Post_Number = $outputPostNumber;
      
        $my_query = new WP_Query("posts_per_page=".$ah_Post_Number);
      
        if ( have_posts() ) :
            echo "<ul>";
                while ($my_query->have_posts()) : $my_query->the_post();
                    echo "<li><a href='".get_permalink()."'>".get_the_title()."</a></li>";
         
                endwhile;
            echo "</ul>";
        endif;
        wp_reset_query();
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("RandomPostWidget");') );