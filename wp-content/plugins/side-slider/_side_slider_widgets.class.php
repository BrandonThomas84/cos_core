<?php

class _side_slider_widgets extends WP_Widget {

	function _side_slider_widgets() {

		$widget_ops = array( 'classname' => 'std22-side-slider-form', 'description' => __('A widget that displays a slider on the side of any page') );
		
		$control_ops = array( 'id_base' => 'std22-side-slider-form' );
		
		$this->WP_Widget( 'std22-side-slider-form', 'Side Slider Form', $widget_ops, $control_ops );		
	}
	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {

		$currentPage = get_the_ID();
		$pageLinks = array();

		foreach( get_pages() as $page ){

			if( !empty( $instance[ 'pagelinks-' . $page->ID ] ) ){
				$pageLinks[] = $page->ID;	
			}
			
		}

		//$instance['content']

		if( !empty( $instance[ 'pages-' . $currentPage ] ) && $instance[ 'pages-' . $currentPage ] !== 'N' ){

			//check for vertical
			if( $instance['vertical'] == 'Y' ){
				$vertClass = ' vert-text';
			} else {
				$vertClass = null;
			}

			extract( $args );
			echo $before_widget;
			echo '
				<div id="side-follow">
					<div class="side-tab' . $vertClass . '">
					 <a href="#" title="Click Me!">' . $instance['link'] . '</a>
					</div>
					<div class="side-content' . $vertClass . '">
						<div class="side-content-inner">
							<h2>' . $instance['title'] . '</h2>
							' . $instance['content'] . '
							<ul class="slider-menu">';

			foreach( $pageLinks as $id ){

				$linkObj = get_post($id);

				if( !empty( $instance['button_override-' . $linkObj->ID] ) ){
					$buttonTitle = $instance['button_override-' . $linkObj->ID];
				} else {
					$buttonTitle = $linkObj->post_title;
				}

				echo 			'<li class="slider-menu-item">
									<a href="/?page_id=' . $linkObj->ID . '">' . $buttonTitle . '</a>
							    </li>';
			}

			echo 			'</ul>
						</div>
					</div>
				</div>';
			echo $after_widget;
		} 
	}

	/**
	 * Ouputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 
			'link' 		=> null, 
			'title' 	=> null, 
			'content' 	=> null,
			'vertical'	=> 'N'
		);

		foreach( get_pages() as $page ){
			$defaults[ 'pages-' . $page->ID ] = null;
			$defaults[ 'pagelink-' . $page->ID ] = null;
			$defaults[ 'button_override-' . $page->ID ] = null;
		}

		// outputs the options form on admin
		echo '<label for="std22-ss-title">Link Text / CTA</label><br>';
		echo '<input type="text" id="' . $this->get_field_id( 'link' ) . '" name="' . $this->get_field_name( 'link' ) . '" required value="' . $instance['link'] . '"><br>';
		echo '<label for="std22-ss-vertical">Vertical Text<br>';
		echo '<input type="checkbox" id="' . $this->get_field_id( 'vertical' ) . '" name="' . $this->get_field_name( 'vertical' ) . '" ' . checked('Y', $instance['vertical'], FALSE) . '><br>';
		echo '<label for="std22-ss-title">Title</label><br>';
		echo '<input type="text" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '"  required value="' . $instance['title'] . '"><br>';
		echo '<label for="std22-ss-content">Content</label><br>';
		echo '<textarea id="' . $this->get_field_id( 'content' ) . '" name="' . $this->get_field_name( 'content' ) . '" required style="width: 100%; height: 180px;">' . $instance['content'] . '</textarea>';
		echo '<div class="std22-ss-pages">';
		echo '<label for="std22-ss-pages">Generate Link for:</label><br>';

		//create links
		foreach( get_pages() as $page ){
			echo '<div class="std22-ss-page-checkbox">';
			echo '<input type="checkbox" id="' . $this->get_field_id(  'pagelinks-' . $page->ID ) . '" name="' . $this->get_field_name( 'pagelinks-' . $page->ID ) . '" value="' . $page->ID . '"' . checked($page->ID, $instance[ 'pagelinks-' . $page->ID ], false) . '>' . $page->post_title . '</br>';
			echo '<label>Button Text Override</label><br><input type="text" id="' . $this->get_field_id( 'button_override-' . $page->ID  ) . '" name="' . $this->get_field_name( 'button_override-' . $page->ID  ) . '"  required value="' . $instance['button_override-' . $page->ID ] . '"><br>';
			echo '</div>';
		}
		echo '</div><hr><br>';
		//echo '<label for="std22-ss-button-override">Button Text Override<br>';
		
		echo '<div class="std22-ss-pages">';
		echo '<label for="std22-ss-pages">Pages to Display on:<br>';

		//display on
		foreach( get_pages() as $page ){
			echo '<div class="std22-ss-page-checkbox">';
			echo '<input type="checkbox" id="' . $this->get_field_id(  'pages-' . $page->ID ) . '" name="' . $this->get_field_name( 'pages-' . $page->ID ) . '" value="' . $page->ID . '"' . checked($page->ID, $instance[ 'pages-' . $page->ID ], false) . '>' . $page->post_title;
			echo '</div>';
		}

		echo '</div>';
	}	

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$old_instance['link'] = $new_instance['link'];
		$old_instance['title'] = strip_tags( $new_instance['title'] );
		$old_instance['content'] = $new_instance['content'];

		if( isset( $new_instance['vertical'] ) ){
			$old_instance['vertical'] = 'Y';	
		} else {
			$old_instance['vertical'] = 'N';
		}

		foreach( get_pages() as $page ){
			$old_instance[ 'pages-' . $page->ID ] = $new_instance[ 'pages-' . $page->ID ];
			$old_instance[ 'pagelinks-' . $page->ID ] = $new_instance[ 'pagelinks-' . $page->ID ];
			$old_instance[ 'button_override-' . $page->ID ] = $new_instance[ 'button_override-' . $page->ID ];
		}

		return $old_instance;
	}
}

?>