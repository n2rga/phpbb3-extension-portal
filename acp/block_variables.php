<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O�Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

namespace phpbbireland\portal\acp;

class block_variables
{
	var $u_action;

	function main($module_id, $mode)
	{
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx, $k_config, $table_prefix, $phpEx;

		include_once($phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $phpEx);
		include_once($phpbb_root_path . 'ext/phpbbireland/portal/helpers/tables.' . $phpEx);

		if (!class_exists($sgp_functions_admin))
		{
			include_once($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions_admin.' . $phpEx);
		}
		$sgp_functions_admin = new sgp_functions_admin();

		$user->add_lang_ext('phpbbireland/portal', 'k_variables');
		$this->tpl_name = 'acp_vars';
		$this->page_title = $user->lang['ACP_BLOCK_VARS'];
		add_form_key('blocks_vars');

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('blocks_vars'))
			{
				$submit = false;
				$mode = '';
				trigger_error('FORM_INVALID');
			}
			$submit = ture;
		}

		$choice = request_variable('switch', '');
		$block = request_variable('block', 0);
		$mode	= request_variable('mode', '');
		$switch = request_variable('switch', '');

		if ($mode = 'config' && $choice == '')
		{
			$choice = 'config';
		}

		if (isset($block))
		{
			$sql = "SELECT id, title, var_file_name
				FROM ". K_BLOCKS_TABLE . "
				WHERE id = " . (int)$block;
			$result = $db->sql_query($sql);

			$row = $db->sql_fetchrow($result);

			$title = strtoupper($row['title']);
			$title = str_replace(' ','_', $row['title']);

			$block_id = $row['id'];
			$var_file_name = $row['var_file_name'];

			$db->sql_freeresult($result);
			get_all_groups();
			get_teams_sort();
		}

		$block = !empty($block) ? $block : 0;
		$action = request_variable('action', '');
		$submit = (isset($_POST['submit'])) ? true : false;

		// swicth to other var setups 11 March 2010
		if ($switch)
		{
			get_reserved_words();
			$var_file_name = $switch;
		}

		$template->assign_vars(array( 'S_SWITCH' => $var_file_name ));


		if ($submit && !check_form_key($form_key))
		{
			trigger_error($user->lang['FORM_INVALID']);
		}

		$sql = 'SELECT config_name, config_value
			FROM ' . K_VARS_TABLE;

		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$k_config[$row['config_name']] = $row['config_value'];

			$template->assign_var('S_' . (strtoupper($row['config_name'])), $row['config_value']);
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'S_OPT' => 'config',
			'MESSAGE' => '',
		));

		if ($submit)
		{
			$mode = 'save';
		}
		else
		{
			$mode = 'reset';
		}

		switch ($mode)
		{
			case 'save':
			{
				$k_allow_acronyms                = request_variable('k_allow_acronyms', 0);
				$k_allow_rand_avatar             = request_variable('k_allow_rand_avatar', 0);
				$k_allow_rotating_logos          = request_variable('k_allow_rotating_logos', 1);
				$k_blocks_display_globally       = request_variable('k_blocks_display_globally', 1);
				$k_footer_images_allow           = request_variable('k_footer_images_allow', 1);
				$k_links_forum_id                = request_variable('k_links_forum_id', '');
				$k_ma_avatar_max_width           = request_variable('k_ma_avatar_max_width', '90');
				$k_ma_columns                    = request_variable('k_ma_columns', '5');
				$k_ma_display_logged_in_only     = request_variable('k_ma_display_logged_in_only', '0');
				$k_ma_max_avatars                = request_variable('k_ma_max_avatars', '0');
				$k_ma_rows                       = request_variable('k_ma_rows', '1');
				$k_ma_user_active                = request_variable('k_ma_user_active', '0');
				$k_ma_user_has_posted            = request_variable('k_ma_user_has_posted', '0');
				$k_max_block_avatar_height       = request_variable('k_max_block_avatar_height', 0);
				$k_max_block_avatar_width        = request_variable('k_max_block_avatar_width', 0);
				$k_max_block_avatar_width        = request_variable('k_max_block_avatar_width', 0);
				$k_post_types                    = request_variable('k_post_types', '');
				$k_recent_topics_search_exclude  = request_variable('k_recent_topics_search_exclude', '');
				$k_quick_reply                   = request_variable('k_quick_reply', 1);
				$k_smilies_show                  = request_variable('k_smilies_show', 1);
				$k_tooltips_active               = request_variable('k_tooltips_active', 1);
				$k_tooltips_which                = request_variable('k_tooltips_which', 0);
				$k_announce_type                 = request_variable('k_announce_type', '');
				$k_announce_allow                = request_variable('k_announce_allow', 1);
				$k_announce_item_max_length      = request_variable('k_announce_item_max_length', 0);
				$k_announce_to_display           = request_variable('k_announce_to_display', 5);
				$k_bots_to_display               = request_variable('k_bots_to_display', 10);
				$k_block_cache_time_default      = request_variable('k_block_cache_time_default', 600);
				$k_block_cache_time_fast         = request_variable('k_block_cache_time_fast', 10);
				$k_block_cache_time_short        = request_variable('k_block_cache_time_short', 10);
				$k_bot_display_allow             = request_variable('k_bot_display_allow', 1);
				$k_donations_max                 = request_variable('k_donations_max', '100');
				$k_donations_years               = request_variable('k_donations_years', '2000');
				$k_last_online_max               = request_variable('k_last_online_max', 5);
				$k_links_to_display              = request_variable('k_links_to_display', 5);
				$k_news_type                     = request_variable('k_news_type', '');
				$k_news_allow                    = request_variable('k_news_allow', 1);
				$k_news_items_to_display         = request_variable('k_news_items_to_display', 5);
				$k_news_item_max_length          = request_variable('k_news_item_max_length', 0);
				$k_recent_topics_to_display      = request_variable('k_recent_topics_to_display', 10);
				$k_recent_topics_per_forum       = request_variable('k_recent_topics_per_forum', 5);
				$k_recent_search_days            = request_variable('k_recent_search_days', 7);
				$k_referrals_to_display          = request_variable('k_referrals_to_display', '');
				$k_teams                         = request_variable('k_teams', '');
				$k_teams_display_this_many       = request_variable('k_teams_display_this_many', 1);
				$k_teampage_memberships          = request_variable('k_teampage_memberships', 0);
				$k_teams_sort                    = request_variable('k_teams_sort', '');
				$k_top_downloads_per_forum       = request_variable('k_top_downloads_per_forum', 0);
				$k_top_downloads_search_days     = request_variable('k_top_downloads_search_days', 0);
				$k_top_downloads_search_exclude  = request_variable('k_top_downloads_search_exclude', '');
				$k_top_downloads_to_display      = request_variable('k_top_downloads_to_display', 5);
				$k_top_downloads_types           = request_variable('k_top_downloads_types', '');
				$k_top_posters_to_display        = request_variable('k_top_posters_to_display', 5);
				$k_top_topics_days               = request_variable('k_top_topics_days', 7);
				$k_top_topics_max                = request_variable('k_top_topics_max', 5);
				$k_yourtube_link                 = request_variable('k_yourtube_link', '');
				$k_yourtube_auto                 = request_variable('k_yourtube_auto', 1);
				$k_yourtube_link_limit           = request_variable('k_yourtube_link_limit', 5);

				if ($k_ma_rows <= 0)
				{
					$k_ma_rows = 1;
				}

				if ($k_max_block_avatar_width == 0 || $k_max_block_avatar_height == 0)
				{
					$k_max_block_avatar_width = $config['avatar_max_width'];
					$k_max_block_avatar_height = $config['avatar_max_height'];
				}
				else if ($k_max_block_avatar_width > $config['avatar_max_width'] || $k_max_block_avatar_height > $config['avatar_max_height'])
				{
					$k_max_block_avatar_width = $config['avatar_max_width'];
					$k_max_block_avatar_height = $config['avatar_max_height'];
				}

				switch ($k_announce_type)
				{
					case 2:  $k_announce_type = POST_ANNOUNCE;
					break;

					case 3:  $k_announce_type = POST_GLOBAL;
					break;

					default: $k_announce_type = 0;
					break;
				}
				switch ($k_news_type)
				{
					case 4:  $k_news_type = POST_NEWS;
					break;

					case 5:  $k_news_type = POST_NEWS_GLOBAL;
					break;

					default: $k_news_type = 0;
					break;
				}

				// all data is escaped in sgp_acp_set_config //
				sgp_acp_set_config('k_allow_rotating_logos', $k_allow_rotating_logos);
				sgp_acp_set_config('k_announce_allow', $k_announce_allow);
				sgp_acp_set_config('k_bot_display_allow', $k_bot_display_allow);
				sgp_acp_set_config('k_footer_images_allow', $k_footer_images_allow);
				sgp_acp_set_config('k_announce_type', $k_announce_type);
				sgp_acp_set_config('k_blocks_display_globally', $k_blocks_display_globally);
				sgp_acp_set_config('k_smilies_show', $k_smilies_show);
				sgp_acp_set_config('k_announce_to_display', $k_announce_to_display);
				sgp_acp_set_config('k_bots_to_display', $k_bots_to_display);
				sgp_acp_set_config('k_announce_item_max_length', $k_announce_item_max_length);
				sgp_acp_set_config('k_links_forum_id', $k_links_forum_id);
				sgp_acp_set_config('k_links_to_display', $k_links_to_display);
				sgp_acp_set_config('k_news_allow', $k_news_allow);
				sgp_acp_set_config('k_news_type', $k_news_type);
				sgp_acp_set_config('k_news_items_to_display', $k_news_items_to_display);
				sgp_acp_set_config('k_news_item_max_length', $k_news_item_max_length);
				sgp_acp_set_config('k_post_types', $k_post_types);
				sgp_acp_set_config('k_recent_topics_to_display', $k_recent_topics_to_display);
				sgp_acp_set_config('k_recent_topics_per_forum', $k_recent_topics_per_forum);
				sgp_acp_set_config('k_recent_topics_search_exclude', $k_recent_topics_search_exclude);
				sgp_acp_set_config('k_recent_search_days', $k_recent_search_days);
				sgp_acp_set_config('k_teams', $k_teams);
				sgp_acp_set_config('k_teams_display_this_many', $k_teams_display_this_many);
				sgp_acp_set_config('k_teams_sort', $k_teams_sort);
				sgp_acp_set_config('k_top_posters_to_display', $k_top_posters_to_display);
				sgp_acp_set_config('k_top_topics_max', $k_top_topics_max);
				sgp_acp_set_config('k_top_topics_days', $k_top_topics_days);
				sgp_acp_set_config('k_referrals_to_display', $k_referrals_to_display);
				sgp_acp_set_config('k_last_online_max', $k_last_online_max);
				sgp_acp_set_config('k_quick_reply', $k_quick_reply);
				sgp_acp_set_config('k_block_cache_time_default', $k_block_cache_time_default);
				sgp_acp_set_config('k_block_cache_time_fast', $k_block_cache_time_fast);
				sgp_acp_set_config('k_yourtube_link', $k_yourtube_link);
				sgp_acp_set_config('k_yourtube_auto', $k_yourtube_auto);
				sgp_acp_set_config('k_yourtube_link_limit', $k_yourtube_link_limit);
				sgp_acp_set_config('k_block_cache_time_short', $k_block_cache_time_short);
				sgp_acp_set_config('k_allow_acronyms', $k_allow_acronyms);
				sgp_acp_set_config('k_max_block_avatar_width', $k_max_block_avatar_width);
				sgp_acp_set_config('k_max_block_avatar_height', $k_max_block_avatar_height);
				sgp_acp_set_config('k_teampage_memberships', $k_teampage_memberships);
				sgp_acp_set_config('k_tooltips_active', $k_tooltips_active);
				sgp_acp_set_config('k_tooltips_which', $k_tooltips_which);
				sgp_acp_set_config('k_allow_rand_avatar', $k_allow_rand_avatar);
				sgp_acp_set_config('k_donations_years', $k_donations_years);
				sgp_acp_set_config('k_donations_max', $k_donations_max);
				sgp_acp_set_config('k_top_downloads_to_display', $k_top_downloads_to_display);
				sgp_acp_set_config('k_top_downloads_search_exclude', $k_top_downloads_search_exclude);
				sgp_acp_set_config('k_top_downloads_search_days', $k_top_downloads_search_days);
				sgp_acp_set_config('k_top_downloads_per_forum', $k_top_downloads_per_forum);
				sgp_acp_set_config('k_top_downloads_types', $k_top_downloads_types);
				sgp_acp_set_config('k_ma_max_avatars', $k_ma_max_avatars);
				sgp_acp_set_config('k_ma_columns', $k_ma_columns);
				sgp_acp_set_config('k_ma_rows', $k_ma_rows);
				sgp_acp_set_config('k_ma_avatar_max_width', $k_ma_avatar_max_width);
				sgp_acp_set_config('k_ma_display_logged_in_only', $k_ma_display_logged_in_only);
				sgp_acp_set_config('k_ma_user_has_posted', $k_ma_user_has_posted);
				sgp_acp_set_config('k_ma_user_active', $k_ma_user_active);

				$mode = 'reset';

				$template->assign_vars(array(
					'S_OPT' => 'saving',
					'MESSAGE' => $user->lang['SAVED'],
				));

				$cache->destroy('_k_config');
				$cache->destroy('sql', K_VARS_TABLE);

				if ($block)
				{
					meta_refresh (0, append_sid("{$phpbb_admin_path}index.$phpEx", "i=k_vars&amp;mode=config&amp;block=" . $block));
				}
				else
				{
					meta_refresh (0, append_sid("{$phpbb_admin_path}index.$phpEx", "i=k_vars&amp;mode=config&amp;switch=" . $switch));
				}
				return;
			}
			case 'default': break;
		}

		switch ($action)
		{
			case 'submit':  $mode = 'reset';
			break;

			case 'default':
			break;
		}

	}
}
