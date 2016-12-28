<?php

/**
 * @file
 * scoreboard module.
 */

/*
 * Implementation of hook_permission()
 */
function scoreboard_permission() {
  return array(
    'access scoreboard' => array(
      'title' => t('Access Scoreboard'),
      'description' => t('Perform administration tasks for my module.'),
    ),
  );
}


/*
* Implements hook_view
*/
function scoreboard_view() {

  $block = array();

  switch($delta) 
  	{
    case 'scoreboard' :
      $block['content'] = array(
      	'#markup'=>_scoreboard_function(),
      	'#path'=>'scoreboard'
      	);
      break;
  	}

  return $block;
}

  function scoreboard_menu() {
    $items['scoreboard'] = array(
      'title'=>'Scoreboard',
      'type' => MENU_NORMAL_ITEM,
      'access arguments' => array('access content'),
      'page callback' => '_scoreboard_view',
    );
    return $items;
  }

/**
 * Custom function to assemble renderable array for block content.
 * Returns a renderable array with the block content.
 * @return
 *   returns a renderable array of block content.
 */

function _scoreboard_view() {

  //This is the json array
  $json = "http://i.turner.ncaa.com/sites/default/files/external/test/scoreboard.json";

  //retreive and parse the json from URL.
  $request = drupal_http_request($json);
  $scoreboard_array = drupal_json_decode($request->data);

  $live = '';
  $pre = '';
  $final = '';
  $i = 0;

  //loop through the json data. 
  foreach($scoreboard_array as $games => $game ) 
  {
	if($game['state'] == 'live')
		{
		$live .= '<div class="game-content">
					
					<div class="game-status">'. $game['state'] . '</div>
					<div class="team">
						<span class="team-logo team'.$i++.' sprites"></span>
					<span class="team-rank">'. 
						($game['home']['rank'] == 0 ? "" :  $game['home']['rank']) .'
					</span>
						<div class="team-info">
							<span class="team-name">'.$game['home']['name'].'</span>
							<span class="team-travel">(home)</span><br>
							<span class="team-record">'. $game['home']['record'] .'</span>
						</div>
						<span class="point-total">'. $game['home']['score'] .'</span>
					</div>';
					
		$live .= '	<div class="team">
						<span class="team-logo team'.$i++.' sprites"></span>
						<span class="team-rank">'. 
							($game['away']['rank'] == 0 ? "" :  $game['away']['rank']) .'
						</span>
						<div class="team-info">
							<span class="team-name">'.$game['away']['name'].'</span>
							<span class="team-travel">(away)</span><br>
							<span class="team-record">'. $game['away']['record'] .'</span>
						</div>
						<span class="point-total">'. $game['away']['score'] .'</span>
					</div>

				</div><!-- end .game-content -->';
		}
	elseif ($game['state'] == 'pre') 
		{
		$pre .= '<div class="game-content">

					<div class="game-status">'. $game['state'] . '</div>
					<div class="team">
						<span class="team-logo team'.$i++.' sprites"></span>
						<span class="team-rank">'. 
							($game['home']['rank'] == 0 ? "" :  $game['home']['rank']) .'
						</span>
						
						<div class="team-info">
							<span class="team-name">'.$game['home']['name'].'</span>
							<span class="team-travel">(home)</span><br>
							<span class="team-record">'. $game['home']['record'] .'</span>
						</div>
						
						<span class="point-total">'. 
							($game['home']['score'] == 0 ? "" :  "score: ". $game['home']['score']) .'
						</span>
					</div>';
					
		$pre .= '	<div class="team">
						<span class="team-logo team'.$i++.' sprites"></span>
						<span class="team-rank">'. 
							($game['away']['rank'] == 0 ? "" :  $game['away']['rank']) .'
						</span>						

						<div class="team-info">
							<span class="team-name">'.$game['away']['name'].'</span>
							<span class="team-travel">(away)</span><br>
							<span class="team-record">'. $game['away']['record'] .'</span>
						</div>
						<span class="point-total">'. 
							($game['away']['score'] == 0 ? "" :  "score: ". $game['away']['score']) .'
						</span>
					</div>
				</div><!-- end .game-content -->';
	}
	elseif ($game['state'] == 'final') {
		$final .= '<div class="game-content">
					
					<div class="game-status">'. $game['state'] . '</div>
					<div class="team">
						<span class="team-logo team'.$i++.' sprites"></span>
						<span class="team-rank">'. 
							($game['home']['rank'] == 0 ? "" :  $game['home']['rank']) .'
						</span>
						
						<div class="team-info">
							<span class="team-name">'.$game['home']['name'].'</span>
							<span class="team-travel">(home)</span><br>
							<span class="team-record">'. $game['home']['record'] .'</span>
						</div>
						<span class="point-total">'. 
							($game['home']['score'] == 0 ? "" :  "score: ". $game['home']['score']) .'
						</span>
					</div>';

		$final .= '<div class="team">
						<span class="team-logo team'.$i++.' sprites"></span>
						<span class="team-rank">'. 
							($game['away']['rank'] == 0 ? "" :  $game['away']['rank']) .'
						</span>						

						<div class="team-info">
							<span class="team-name">'.$game['away']['name'].'</span>
							<span class="team-travel">(away)</span><br>
							<span class="team-record">'. $game['away']['record'] .'</span>
						</div>
							<span class="point-total">'. 
								($game['away']['score'] == 0 ? "" :  "score: ". $game['away']['score']).'
						</span>
					</div>
				</div><!-- end .game-content -->';	
			}
  }
  $all_games = '<div class="status-content">'.$live.'</div><!-- end .status-content -->
  				<div class="status-content">'.$pre.'</div><!-- end .status-content -->
  				<div class="status-content">'.$final.'</div><!-- end .status-content -->';

  return $all_games;
}

