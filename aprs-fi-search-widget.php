<?php
/**
 * Plugin Name: APRS.fi Search Widget
 * Plugin URI: http://wordpress.eagleflint.com/plugins/aprsfisearch.php
 * Description: A widget that populates a Ham Call Sign search form to the APRS.fi website
 * Version: 0.2.1
 * Date: 2011-04-06
 * Author: Flint Gatrell, N0FHG
 * Author URI: http://eagleflint.com
 * Copyright: 2011 by Flint Gatrell
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'aprsfisearch_load_widgets' );

/**
 * Register our widget.
 * 'APRSfiSearch_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function aprsfisearch_load_widgets() {
	register_widget( 'APRSfiSearch_Widget' );
}

/**
 * APRS.fi Search Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class APRSfiSearch_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function APRSfiSearch_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'aprsfisearch', 'description' => __('A simple widget that displays a Ham Call Sign search form against the APRS.fi website.', 'aprsfisearch') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'aprsfisearch-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'aprsfisearch-widget', __('APRS.fi Search Widget', 'aprsfisearch'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$aprsfisearchtitle = apply_filters('widget_title', $instance['aprsfisearchtitle'] );
		$aprsfisearchbuttontext = $instance['aprsfisearchbuttontext'];
		$aprsfisearchinitialsearchvalue = $instance['aprsfisearchinitialsearchvalue'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $aprsfisearchtitle )
			echo $before_title . $aprsfisearchtitle . $after_title;

		/* Display search control from widget settings if button text is defined. */
		echo ('<script type="text/javascript">
			<!--
			function aprsfisearchpop(myform, windowname)
			{
			if (! window.focus)return true;
			window.open(\'\', windowname, \'height=800,width=1000,scrollbars=yes\');
			myform.target=windowname;
			return true;
			}
			//-->
			</script>');
		printf( '<form method="get" action="http://aprs.fi/"  onSubmit="aprsfisearchpop(this, \'APRSfi\')"">
			<input type="text" name="call" value="'.__('%1$s', 'aprsfisearch').'" />', $aprsfisearchinitialsearchvalue );
		printf( '<input type="submit" value="'.__('%1$s', 'aprsfisearch').'" />
			<input type="hidden" name="mt" value="roadmap" />
			<input type="hidden" name="z" value="11" />
			<input type="hidden" name="timerange" value="3600" /></form>', $aprsfisearchbuttontext );
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['aprsfisearchtitle'] = strip_tags( $new_instance['aprsfisearchtitle'] );
		$instance['aprsfisearchbuttontext'] = strip_tags( $new_instance['aprsfisearchbuttontext'] );
		$instance['aprsfisearchinitialsearchvalue'] = strip_tags( $new_instance['aprsfisearchinitialsearchvalue'] );

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'aprsfisearchtitle' => __('APRS.fi Call Sign Lookup', 'aprsfisearch'), 'aprsfisearchbuttontext' => __('Search', 'aprsfisearch'), 'aprsfisearchinitialsearchvalue' => __('W0DTF-10', 'aprsfisearch') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'aprsfisearchtitle' ); ?>"><?php _e('Title:', 'aprsfisearch'); ?></label>
			<input id="<?php echo $this->get_field_id( 'aprsfisearchtitle' ); ?>" name="<?php echo $this->get_field_name( 'aprsfisearchtitle' ); ?>" value="<?php echo $instance['aprsfisearchtitle']; ?>" style="width:100%;" />
		</p>

		<!-- Initial Search Value: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'aprsfisearchinitialsearchvalue' ); ?>"><?php _e('Initial Search Value:', 'aprsfisearch'); ?></label>
			<input id="<?php echo $this->get_field_id( 'aprsfisearchinitialsearchvalue' ); ?>" name="<?php echo $this->get_field_name( 'aprsfisearchinitialsearchvalue' ); ?>" value="<?php echo $instance['aprsfisearchinitialsearchvalue']; ?>" style="width:100%;" />
		</p>

		<!-- Search Button Text: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'aprsfisearchbuttontext' ); ?>"><?php _e('Search Button Text:', 'aprsfisearch'); ?></label>
			<input id="<?php echo $this->get_field_id( 'aprsfisearchbuttontext' ); ?>" name="<?php echo $this->get_field_name( 'aprsfisearchbuttontext' ); ?>" value="<?php echo $instance['aprsfisearchbuttontext']; ?>" style="width:100%;" />
		</p>

	<?php
	}
}

?>