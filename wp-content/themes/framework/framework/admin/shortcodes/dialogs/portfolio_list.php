<?php
return array(
	"title" => __("Portfolio list",'theme_admin'),
	"shortcode" => 'portfolio_list',
	"type" => 'self-closing',
	"options" => array(
		array(
			"name" => __("Post Count",'theme_admin'),
			"desc" => __("Use this setting to determine the number of posts to show in the widget. &nbsp;The count can vary from 1 to 30.",'theme_admin'),
			"id" => "count",
			"default" => '4',
			"min" => 0,
			"max" => 30,
			"step" => "1",
			"type" => "range"
		),
		array(
			"name" => __("Show Portfolio Thumbnail",'theme_admin'),
			"desc" => __("This setting is for the post thumbnails and the default is to have them display.",'theme_admin'),
			"id" => "thumbnail",
			"default" => true,
			"type" => "toggle"
		),
		array(
			"name" => __("Thumbnail image Size",'theme_admin'),
			"desc" => __("Use this setting to determine the size of the square thumbnail. &nbsp;The thumbnail can vary from 30x30 to 200x200 in size.",'theme_admin'),
			"id" => "thumbnail_size",
			"min" => 30,
			"max" => 200,
			"step" => "1",
			"unit" => 'px',
			"default" => 50,
			"type" => "range"
		),
		array(
			"name" => __("Length of Title",'theme_admin'),
			"desc" => __("This setting allows for determination of the post title length to show in the widget, useful in truncating long post titles. &nbsp;The default position of 0 uses the full length of the post titles (to a maximum of 80 characters).",'theme_admin'),
			"id" => "title_length",
			"default" => 0,
			"min" => 0,
			"max" => 80,
			"step" => "1",
			"type" => "range"
		),
		array(
			"name" => __("Extra Portfolio Infomation<br /> to Display",'theme_admin'),
			"desc" => __("There are several choices of what to display along with the post title and thumbnail: time of post, description of post, time and description, or nothing.<br /><br />If choosing to display the description, a setting below <b>Length of Description</b> determines how much of it will be shown.",'theme_admin'),
			"id" => "extra",
			'default' => 'time',
			"options" => array(
				"time" => __('Time','theme_admin'),
				"desc" => __('Description','theme_admin'),
				"both" => __('Time and Description','theme_admin'),
				"none" => __('None','theme_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("Length of Description",'theme_admin'),
			"desc" => __("If choosing to display the post description via the options in <b>Extra Post Infomation to Display</b> above, then this setting determines how much of it will be shown. &nbsp;The range is from 1 to 200 characters with 80 characters the default.<br /><br />The setting draws from the portfolio item excerpt field first, and if no excerpt was completed, then it draws from the initial post body content.",'theme_admin'),
			"id" => "desc_length",
			"default" => '80',
			"min" => 0,
			"max" => 200,
			"step" => "1",
			"type" => "range"
		),
		array(
			"name" => __("Filter by Category (Optional)&#x200E;",'theme_admin'),
			"desc" => __("There are 3 filtering options for determining which portfolios show in the widget: by category(s), by portfolio type, or displaying portfolios by author(s). &nbsp;This setting is for the category option. &nbsp;Leave it blank if using one of the other filter settings below.<br /><br />Select multiple categories by simply clicking on a category, then clicking in the selector field beside the category displaying, and selecting another until all the desired categories are showing in the selection field. &nbsp;Click on the &#34;X&#34; to remove any undesired categories.",'theme_admin'),
			"id" => "cat",
			"default" => array(),
			"target" => 'portfolio_category',
			"chosen" => true,
			"prompt" => __("Select Categories..",'theme_admin'),
			"type" => "multiselect",
		),
		array(
			"name" => __("Portfolio Type to Display (Optional)&#x200E;",'theme_admin'),
			"desc" => __("This setting allows for choosing a portfolio types such as Image, or Video to display.",'theme_admin'),
			"id" => "type",
			"default" => '',
			"options" => array(
				"image" => __('Image','theme_admin'),
				"gallery" => __('Gallery','theme_admin'),
				"video" => __('Video','theme_admin'),
				"doc" => __('Document','theme_admin'),
				"link" => __('Link','theme_admin'),
				"lightbox" => __('Lightbox','theme_admin'),
			),
			"prompt" => __("Select Type..",'theme_admin'),
			"type" => "select",
		),
		array(
			"name" => __("Display by Author (Optional)&#x200E;",'theme_admin'),
			"desc" => __("This setting allows for choosing posts by author or authors. &nbsp;It is a multiselector field like the above filters.",'theme_admin'),
			"id" => "author",
			"default" => array(),
			"target" => 'author',
			"chosen" => true,
			"prompt" => __("Select Authors..",'theme_admin'),
			"type" => "multiselect",
		),
		array(
			"name" => __("Link Target (Optional)&#x200E;",'theme_admin'),
			"id" => "target",
			"default" => '_self',
			"prompt" => __("Choose one..",'theme_admin'),
			"options" => array(
				"_blank" => __('Load in a new window','theme_admin'),
				"_self" => __('Load in the same frame as it was clicked','theme_admin'),
				"_parent" => __('Load in the parent frameset','theme_admin'),
				"_top" => __('Load in the full body of the window','theme_admin'),
			),
			"type" => "select",
		),
		array(
			"name" => __("# of Portfolios to Offset",'theme_admin'),
			"desc" => __("The Offset allows for skipping the newest portfolio posts of whatever filter chosen above. &nbsp;The default offset is 0 and the maximum offset is to skip the 10 newest portfolio posts.",'theme_admin'),
			"id" => "offset",
			"default" => '0',
			"min" => 0,
			"max" => 10,
			"step" => "1",
			"type" => "range"
		),
	),
);
