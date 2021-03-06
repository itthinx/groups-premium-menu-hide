<?php
/**
 * groups-premium-menu-hide.php
 *
 * Copyright (c) 2013 "kento" Karim Rahimpur www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Karim Rahimpur
 * @package groups-premium-menu-hide
 * @since groups-premium-menu-hide 1.0.0
 *
 * Plugin Name: Groups Premium Menu Hide
 * Plugin URI: http://www.itthinx.com/plugins/groups
 * Description: An example of how to hide menu items for members of a Premium group.
 * Version: 1.1.0
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 * Donate-Link: http://www.itthinx.com
 * License: GPLv3
 */


if ( !is_admin() ) {
	add_filter( 'wp_get_nav_menu_items', 'hide_premium_member_menu_items', 999, 3 );
}
function hide_premium_member_menu_items( $items = null, $menu = null, $args = null ) {

	$result = array();
	
	// hide from one or more groups, indicate the group name(s)
	$hide_from_groups = array( 'Premium' );

	// hide menu items for these posts when the visitor is in the premium group
	$hide_from_premium_post_ids = array();
	$hide_from_premium_page_titles = array(
		'Ahhh wait a second!',
		'Premium Membership Benefits',
		'Hide from Premium'
	);
	foreach( $hide_from_premium_page_titles as $title ) {
		if ( $post = get_page_by_title( $title ) ) {
			$hide_from_premium_post_ids[] = (int) $post->ID;
		}
	}

	$premium = false;
	$user_id = get_current_user_id();
	foreach( $hide_from_groups as $hide_from_group ) {
		if ( $group = Groups_Group::read_by_name( $hide_from_group ) ) {
			if ( Groups_User_Group::read( $user_id, $group->group_id ) ) {
				$premium = true;
				break;
			}
		}
	}

	foreach ( $items as $item ) {
		if ( ! ( $premium && in_array( (int) $item->object_id, $hide_from_premium_post_ids ) ) ) {
			$result[] = $item;
		}
	}
	return $result;
}
