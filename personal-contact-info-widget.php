<?php

	/**
	 * Plugin Name: Personal Contact Info Widget
	 * Description: Custom Widget for displaying your photo and personal contact information.
	 * Version: 1.1
	 * Author: Juan Sanchez Jr.
	 * License: GPLv2 or later
	 */
	 
	/**  
	 * Copyright 2014  Juan Sanchez Jr. ( email : bringmesupport@gmail.com )
	 * This program is free software; you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License, version 2, as 
	 * published by the Free Software Foundation.
	 *
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with this program; if not, write to the Free Software
	 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	 */

	defined('ABSPATH') or die("No script kiddies please!");

	class jsjr_personal_contact_info extends WP_Widget {

		private $ver = '1.0';
		private $domain = 'pci_text_domain';
		private $social_icons = array(
				'fa-facebook-square' 	=> 'Facebook',
				'fa-youtube-square' 	=> 'YouTube',
				'fa-twitter-square' 	=> 'Twitter',
				'fa-linkedin-square' 	=> 'LinkedIn',
				'fa-google-plus-square' => 'Google Plus',
				'fa-skype'				=> 'Skype',
				'fa-dropbox'			=> 'Dropbox',
				'fa-yelp'				=> 'Yelp',
				'fa-instagram' 			=> 'Instagram',
				'fa-pinterest'			=> 'Pinterest',
				'fa-wordpress'			=> 'WordPress',
				'fa-vine'				=> 'Vine',
				'fa-vimeo-square'		=> 'Vimeo',
				'fa-tumblr-square'		=> 'Tumblr',
				'fa-foursquare'			=> 'Foursquare',
				'fa-digg'				=> 'Digg',
				'fa-skype'				=> 'Skype',
				'fa-github'				=> 'GitHub',
				'fa-bitbucket-square'	=> 'Bitbucket',
				'fa-stack-overflow'		=> 'Stack Overflow'
		);
		
		public function __construct() {
			$widget_ops = array(
				'description' => __( 'Custom Widget for displaying your photo, social media links and contact information.', $this->domain ),
				'customizer_support' => true
			);
			$control_ops = array( 'width' => 400 );
			parent::__construct( false, __( 'Personal Contact Info' , $this->domain ), $widget_ops, $control_ops );
			add_action( 'wp_enqueue_scripts', array( $this, 'jsjr_pci_wp_styles_and_scripts' ));
			add_action( 'admin_enqueue_scripts', array( $this, 'jsjr_pci_admin_styles_and_scripts' ));
		}
		
		public function jsjr_pci_admin_styles_and_scripts( $hook ){
			if ( 'widgets.php' == $hook ) {
				wp_enqueue_media();
				wp_enqueue_script( 'jquery-ui-tooltip' );
				wp_enqueue_script( 'jsjr-pci-admin-scripts' , plugin_dir_url( __FILE__ ) . 'js/admin-scripts.js', array('jquery'), $this->ver , false );
				wp_enqueue_style( 'jsjr-pci-admin-css' , plugin_dir_url( __FILE__ ) . 'css/admin-styles.css' , array() , $this->ver , false );				
			}			
		}
		
		public function jsjr_pci_wp_styles_and_scripts(){
			wp_enqueue_style( 'jsjr-pci-wp-css' , plugin_dir_url( __FILE__ ) . 'css/wp-styles.css' , array() , $this->ver , false );
			if ( get_option('fa_existing') === "unchecked" ) {
				wp_enqueue_style( 'jsjr-pci-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', array(), '4.2.0', false );
			}
		}
		
		public function widget( $args, $instance ) {

			extract( $args );
			extract( $instance );
				
			echo $before_widget;
			
			echo '<div class="jsjr-pci-contact-section">';
			
			
			if ( !empty( $title ) ) {
				echo $before_title , $title , $after_title;
			}
			
			if( !empty( $profile_image_url ) && !empty( $profile_image ) && !empty( $profile_image_below ) && !empty( $profile_image_width ) ){
				if ( $profile_image_below  === 'unchecked' ) {
					echo '<img src="' , $profile_image_url , '" class="jsjr-pci-photo ', $profile_image , '" style="width:' , $profile_image_width , '" alt="Profile Photo" />';
				} 
			} 
			
			if ( !empty( $full_name ) ) {
				echo '<h2 class="jsjr-pci-name" >' , $full_name , '</h2>';
			}
			
			if ( !empty( $slogan  ) ) {
				echo '<p class="jsjr-pci-slogan" >' , $slogan , '</p>';
			}
			
			echo '<p class="jsjr-pci-social-icons" >';
			
			foreach ( $this->social_icons as $fa_class => $icon ) {
				if ( !empty( $instance[$fa_class] ) ) {
					echo '<a href="' , $instance[$fa_class] , '" class="fa ' , $fa_class , '" target="_blank" ></a>';
				}
			}
				
			echo '</p>';
			
			if ( !empty( $email ) ) {
				echo '<p class="jsjr-pci-email" ><span class="fa fa-envelope" ></span> ' , $email , '</p>';
			}
			
			if ( !empty( $phone ) ) {
				echo '<p class="jsjr-pci-phone" ><span class="fa fa-phone"></span> ' , $phone , '</p>';
			}
			
			if( !empty( $profile_image_url ) && !empty( $profile_image_below ) ) {
				if ( $profile_image_below  === 'checked' ) {
					echo '<img src="' , $profile_image_url , '" class="jsjr-pci-photo ', $profile_image , '" alt="Profile Photo" />';
				}
			}
			
			echo '</div>';
			
			echo $after_widget;
			
		}

		public function update( $new_instance, $old_instance ) {
			foreach ( $new_instance as $key => $value ) {
				$old_instance[ $key ] = trim( strip_tags( $value ) );
			}
			$old_instance[ 'profile_image_below' ] = isset( $new_instance[ 'profile_image_below' ] ) ? $new_instance[ 'profile_image_below' ] : 'unchecked';
			$old_instance[ 'fa_existing' ] = isset( $new_instance[ 'fa_existing' ] ) ? $new_instance[ 'fa_existing' ] : 'unchecked';
			update_option( 'fa_existing', $old_instance['fa_existing'] );
			return $old_instance;
		}

		public function form( $instance ) {	

			foreach ( $instance as $key => $value ) {
				$$key = esc_attr( $value );
			}
			
			$select_options = array (
				'jsjr-pci-photo-square'		=> 'Square',
				'jsjr-pci-photo-circle'		=> 'Round',
				'jsjr-pci-photo-rcorners'	=> 'Rounded Corners',
				'jsjr-pci-photo-thumbnail'	=> 'Thumbnail'
			);
			
			$select_options_width = array (
				'auto'		=> 'Use Photo\'s Width',
				'100%'		=> '100%',
				'90%'		=> '90%',
				'80%'		=> '80%',
				'70%'		=> '70%',
				'60%'		=> '60%',
				'50%'		=> '50%',
				'40%'		=> '40%',
				'30%'		=> '30%',
				'20%'		=> '20%',
				'10%'		=> '10%'
			);
			
			?>
			
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):', $this->domain ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php _e( isset( $title ) ?  $title : '', $this->domain ); ?>" />
			</p>
			
			<div class="jsjr-pci-accordion">
			
				<h3 class="jsjr-pci-toggle" >Profile Photo</h3>
				<div id="test" style="display:none;" >
					<p>
						<label for="<?php echo $this->get_field_id( 'profile_image_url' ); ?>"><?php _e( 'Link to Profile Image (URL):', $this->domain ); ?></label> 
						<input class="widefat upload-input" id="<?php echo $this->get_field_id( 'profile_image_url' ); ?>" name="<?php echo $this->get_field_name( 'profile_image_url' ); ?>" type="text" value="<?php _e( isset( $profile_image_url ) ?  $profile_image_url : '', $this->domain ); ?>" />
						<input type="button" name="submit" id="submit" class="button-primary upload-button" value="Select image">
						
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('profile_image'); ?>"><?php _e('Image Style:'); ?></label>
						<a href="#" class="jsjr-pci-question" title="<?php _e( 'NOTICE: These styles do not work on old internet browsers.', $this->domain ) ?>">?</a>
						<select name="<?php echo $this->get_field_name('profile_image'); ?>" id="<?php echo $this->get_field_id('profile_image'); ?>" class="widefat">
							<?php
							$profile_image = isset( $profile_image ) ? $profile_image : 'jsjr-pci-photo-square';
							foreach ( $select_options as $key => $value ) {
								echo '<option value="' , $key , '" ', selected( $profile_image, $key ) , '>', __( $value, $this->domain ) , '</option>';
							}
							?>
						</select>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('profile_image_width'); ?>"><?php _e('Image Width:'); ?></label>
						<select name="<?php echo $this->get_field_name('profile_image_width'); ?>" id="<?php echo $this->get_field_id('profile_image_width'); ?>" class="widefat">
							<?php
							$profile_image = isset( $profile_image_width ) ? $profile_image_width : 'auto';
							foreach ( $select_options_width as $key => $value ) {
								echo '<option value="' , $key , '" ', selected( $profile_image_width, $key ) , '>', __( $value, $this->domain ) , '</option>';
							}
							?>
						</select>
					</p>
					<p>
						<input id="<?php echo $this->get_field_id('profile_image_below'); ?>" name="<?php echo $this->get_field_name('profile_image_below'); ?>" type="checkbox" value="checked" <?php isset( $profile_image_below ) ? checked( 'checked', $profile_image_below ) : ''; ?> />
						<label for="<?php echo $this->get_field_id('profile_image_below'); ?>"><?php _e('Move photo below Contact Details', $this->domain ); ?></label>
					</p>
				</div>
				
				<h3 class="jsjr-pci-toggle" >Contact Details</h3>
				<div style="display:none;" >
					<p>
						<label for="<?php echo $this->get_field_id('full_name'); ?>"><?php _e('Your Full Name:'); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id('full_name'); ?>" name="<?php echo $this->get_field_name('full_name'); ?>" type="text" value="<?php _e( isset( $full_name ) ?  $full_name : '', $this->domain ); ?>" />
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('slogan'); ?>"><?php _e('Slogan:'); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id('slogan'); ?>" name="<?php echo $this->get_field_name('slogan'); ?>" type="text" value="<?php _e( isset( $slogan ) ?  $slogan : '', $this->domain ); ?>" />
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email:'); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php _e( isset( $email ) ?  $email : '', $this->domain ); ?>" />
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone:'); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php _e( isset( $phone ) ?  $phone : '', $this->domain ); ?>" />
					</p>			
					<p>
						<label for="<?php echo $this->get_field_id('website'); ?>"><?php _e('Alternate Website (optional):', $this->domain ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id( 'website' ); ?>" name="<?php echo $this->get_field_name( 'website' ); ?>" type="text" value="<?php _e( isset( $website ) ?  $website : '', $this->domain ); ?>" />
					</p>
				</div>
				
				<h3 class="jsjr-pci-toggle" >Social Media Links</h3>
				<div style="display:none;">
					<a href="#" class="jsjr-pci-question" title="<?php _e( 'Enter the internet links (URL) for your social media websites below (I.E \'http://facebook.com/myfacebookpage\')', $this->domain ) ?>" >?</a>
					<?php foreach ( $this->social_icons as $fa_class => $icon ) { ?>
					<p>
						<label for="<?php echo $this->get_field_id( $fa_class ); ?>"><?php _e( $icon.':', $this->domain ); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id( $fa_class ); ?>" name="<?php echo $this->get_field_name( $fa_class ); ?>" type="text" value="<?php _e( isset( $$fa_class ) ?  $$fa_class : '', $this->domain ); ?>" />
					</p>
					<?php } ?>								
				</div>
				
				<h3 class="jsjr-pci-toggle" >Advanced Options</h3>
				<div style="display:none;" >
					<p>
						<input id="<?php echo $this->get_field_id('fa_existing'); ?>" name="<?php echo $this->get_field_name('fa_existing'); ?>" type="checkbox" value="checked" <?php isset( $fa_existing ) ? checked( 'checked', $fa_existing) : ''; ?> />
						<label for="<?php echo $this->get_field_id('fa_existing'); ?>"><?php _e('Do not load Font Awesome.', $this->domain ); ?></label>
						<a href="#" class="jsjr-pci-question" title="<?php _e( 'Some themes may already load Font Awesome (used for the social media icons). To avoid Font Awesome from being loaded twice on your website, check this box. If you are unsure if your theme already uses Font Awesome or not, try checking this box and see if the social media icons on your widget disappear. If it does, simply uncheck the box again and save the changes.', $this->domain ) ?>" >?</a>
					</p>					
				</div>	

			</div>
			
			<?php
		}
		
	}
	add_action('widgets_init', create_function('', 'return register_widget("jsjr_personal_contact_info");'));