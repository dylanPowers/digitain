<?php 

class scorecard{
    //Scorecard V2.0
    //$scoreid should of type array.
    function __construct($scoreid, $data){
        $this->scoreid = $scoreid;        
        $this->data = $data;
        $this->score_vals();      
    }
    function update_data($data){
        foreach ($data as $key=>$value){
            $this->data[$key] = $value;            
        }
        $this->score_vals();
    }
    function score_vals(){
         //Because $data includes key and value pairs that include
        //information such as gameids and die values, a seperate 
        //array must be made for the score values.
        $this->score_vals = array('aces'=>$this->data['aces'],
            'twos'=>$this->data['twos'],
            'threes'=>$this->data['threes'],
            'fours'=>$this->data['fours'],
            'fives'=>$this->data['fives'],
            'sixes'=>$this->data['sixes'],
            '3k'=>$this->data['3k'],
            '4k'=>$this->data['4k'],
            'full_house'=>$this->data['full_house'],
            'small_straight'=>$this->data['small_straight'],
            'large_straight'=>$this->data['large_straight'],
            'yahtzee'=>$this->data['yahtzee'],
            'chance'=>$this->data['chance'],
            'bonus'=>$this->data['bonus']
                                 );
    }
    function totals(){
        $game_complete = true;
        $total_score = 0;
        $upper_total = 0;
        $upper_bonus = false;
        $scores_null = 0;
        foreach ($this->score_vals as $key=>$value){
            if ($value == null && $key != 'bonus'){
                $game_complete = false;
                $scores_null += 1;
            }
            else{
                $total_score += $value;
                if ($key == 'aces' || $key == 'twos' || $key == 'threes' || 
                    $key == 'fours' || $key == 'fives' || $key == 'sixes'
                   ){
                    $upper_total += $value;
                }
            }
        }
        if ($upper_total >= 63){
                $total_score += 35;
                $upper_bonus = true;
        }
        if ($upper_total == 0){
                $upper_total = null;
        }
        if ($game_complete){
            return array('upper_total'=>$upper_total,
                         'upper_bonus'=>$upper_bonus,
                         'game_complete'=>$total_score, 
                         'scores_null'=>$scores_null);
        }
        else{
            return array('upper_total'=>$upper_total,
                         'upper_bonus'=>$upper_bonus,
                         'scores_null'=>$scores_null);
        }
    }	
    function __toString(){
        $totals = $this->totals();
        $pname = strtoupper($this->data['playername']);
        $string ='
        <table class="scorecard">
            <tr>
                <th class="scorecard_name" colspan="2">'
                .$pname.'</th>
            </tr>
            <tr>
                <td class="scorecard_points" colspan="2">Upper Section</td>
            </tr>';
        foreach ($this->score_vals as $key => $value){
            $points = stripslashes($value);
            $key = stripslashes($key);
            $key = str_replace("_"," ", $key);
            $key = ucwords($key);
            if ($key == '3k'){
                $key = '3 of a Kind';
            }
            elseif ($key == '4k'){
                $key = '4 of a Kind';
            }
            if ($key == 'Bonus'){
                if ($value != null){
                    $string .='
            <tr class="scorecard">
                <td class="scorecard">'.$key.'</th>
                <td class="scorecard_points">'.$value.'</td>
            </tr>';
                }
            }
            else{
                $string .='
            <tr class="scorecard">
                <td class="scorecard">'.$key.'</th>
                <td class="scorecard_points">'.$value.'</td>
            </tr>';
            }
            if ($key=="Sixes"){ 
                if ($totals['upper_bonus']){
                    $string .= '
            <tr>
                <td class="scorecard">Bonus</td>
                <td class="scorecard_points">35</td>
            </tr>';
                }			
                $string .='
            <tr>
                <td class="scorecard">Upper Total</td>
                <td class="scorecard_points">'.$totals['upper_total'].'</td>
            </tr>
            <tr class="scorecard">
                <td colspan="2" class="scorecard_points">Lower Section</td>
            <tr>';
            }
        }
        if (isset($totals['game_complete'])){
            $string .='
            <tr>
                <td class="scorecard">Total Score</td>
                <td class="scorecard_points">'.$totals['game_complete'].'</td>
            </tr>';
        }		
        $string .='
        </table>';	
        return $string; 
    }
}

class yahtzee_game{
    //Yahtzee V2.1
//    Changes from v2.0 to v2.1:
//      Fixed a bug that causes the small straight option to not work caused by
//      the score_eval function having a miss named variable. 
//          line 652: small_straignt -> small_straight
    function __construct($db_connection, $gameid, $action){
        $this->yahtzee_version = 'v2.1';
        echo $this->yahtzee_version;
        $this->gameid = $gameid;
        $this->action = $action;
        $this->raw_data = new yahtzee_game_data($db_connection, $this->gameid);
        $this->scoreid = $this->get_player_turn();
        $this->page_handler();
    }    
    //A handler for when the rare bonus yahtzee occurs.
    function bonus_page_handler($dice){
        $dice = $dice->get_dice();
        echo '
    <h2>Make Your Joker Selection</h2>
    <form action="yahtzee.php" method="post">
        <input type="hidden" name="gameid" value="'.$this->gameid.'" />';
        $descriptions = array('aces'=>'Add Ones', 'twos'=>'Add Twos', 
                              'threes'=>'Add Threes<br />',
                              'fours'=>'Add Fours', 'fives'=>'Add Fives', 
                              'sixes'=>'Add Sixes<br />',
                              '3k'=>'Three of a kind', 
                              '4k'=>'Four of a kind<br />',
                              'full_house'=>'Full House<br />',
                              'small_straight'=>'Small Straight', 
                              'large_straight'=>'Large Straight<br />',
                              'yahtzee'=>'Yahtzee', 'chance'=>'Chance<br />',
                             );    
        $upper_vals = array(1=>'aces', 2=>'twos', 3=>'threes', 4=>'fours', 5=>'fives', 6=>'sixes');
        $scoreid_data = $this->raw_data->get_scoreid_data($this->scoreid);
        $yahtzee_dice = $dice['die1'];        
        if ($scoreid_data[$upper_vals[$yahtzee_dice]] == null){
            echo '
        <input type="radio" name="bonus_option" value="'.$upper_vals[$yahtzee_dice].'" />'.
                $descriptions[$upper_vals[$yahtzee_dice]];
        }
        else{        
            foreach ($descriptions as $key=>$value){
                if ($scoreid_data[$key] == null){
                    echo '
            <input type="radio" name="bonus_option" value="'.$key.'" />'.
                    $descriptions[$key];
                }
            }
        }
        echo'<br />
        <input type="submit" value="Submit Selection" />
    </form>';
    }    
    
    //Function to output the dice reroll options element of the web
    //page.
    function dice_element($end, $dice){
        $dice_vals = $dice->get_dice();
        $roll_num = $dice->get_roll_num();
        if ($roll_num < 3 && !$end){		
            $string = '
    <h3>Select the dice '."you'd".' like to reroll<h3>
    <h5>Rolls remaining: '.(3-$roll_num).'</h5>
    <form action="yahtzee.php" method="post">
        <input type="hidden" name="gameid" value="'.$this->gameid.'" />
        <input type="checkbox" name="reroll[]" value="die1" />Die One: '.$dice_vals['die1'].'<br />
        <input type="checkbox" name="reroll[]" value="die2" />Die Two: '.$dice_vals['die2'].'<br />
        <input type="checkbox" name="reroll[]" value="die3" />Die Three: '.$dice_vals['die3'].'<br />
        <input type="checkbox" name="reroll[]" value="die4" />Die Four: '.$dice_vals['die4'].'<br />
        <input type="checkbox" name="reroll[]" value="die5" />Die Five: '.$dice_vals['die5'].'<br />
        <input type="submit" value="Reroll" /><br/>
    </form>
    <h4>.....OR.....</h4>';
        }
        else{
            $string = '
    <h3>Your Dice<h3>
    Die One: '.$dice_vals['die1'].'<br />
    Die Two: '.$dice_vals['die2'].'<br />
    Die Three: '.$dice_vals['die3'].'<br />
    Die Four: '.$dice_vals['die4'].'<br />
    Die Five: '.$dice_vals['die5'].'<br />';
        }
    echo $string;
        
    }
    //Function to handle the ending of a game.
    function end_game_handler(){
        $scorecards = array();
        $game_data = $this->raw_data->get_game_data();
        foreach ($game_data as $value){
            $scorecards[] = new scorecard($value['scoreid'], $this->raw_data->get_scoreid_data($value['scoreid']));
        }
        $this->end_game_page($scorecards);        
    }
    
    //Function to output the end game page. $gamecards should be of type array.
    function end_game_page($gamecards){
        $string = '
    <h1>End of Game</h1>
    <h4>Here are your scores:</h4>
    <table>
        <tr>';
        foreach ($gamecards as $value){
            $string .= '
            <td>'.$value.'
            </td>';			
        }
        echo $string.'
        </tr>
    </table>';		
    }
    function end_turn_page($dice, $scorecard){
        $output = $this->score_eval($dice, $scorecard, false, false);
        $string = '
    <h5>'.$output[1].'</h5>
    <form action="yahtzee.php" method="post">
        <input type="hidden" name="gameid" value="'.$this->gameid.'" />
        <input type="submit" value="End Turn" />
    </form>';
        return array($output[0], $string);
    }
     //Function to determine the player turn.    
    function get_player_turn(){	
        $max_scores_null = 0;
        $game_data = $this->raw_data->get_game_data();
        for ($i=0; $i < count($game_data); $i++){
            $row_scoreid = $game_data[$i];
            $scoreid = $row_scoreid['scoreid'];
            $player_scorecard = new scorecard($scoreid, 
                                $this->raw_data->get_scoreid_data($scoreid)
                                             );
            $totals = $player_scorecard->totals();
            if ($i == 0){
                $player_turn = $scoreid;
                $max_scores_null = $totals['scores_null'];
            }
            else{
                if ($totals['scores_null'] > $max_scores_null){
                    $max_scores_null = $totals['scores_null'];
                    $player_turn = $scoreid;
                }
            }
        }
        if ($max_scores_null == 0){
            $player_turn = 'game_complete';			
        }
        return $player_turn;		
    }
    //Page handler with all the logic behind deciding which page to show.
    function page_handler(){
        if ($this->scoreid != 'game_complete'){
            $scoreid_data = $this->raw_data->get_scoreid_data($this->scoreid);                    
            $player_name = $scoreid_data['playername'];
            $scorecard = new scorecard($this->scoreid, $scoreid_data);
            $dice = new dice($scoreid_data, $scoreid_data['rollnum'], $this->gameid);        		
        
            if (isset($this->action['reroll'])){
                $this->play_turn_head($player_name, $scorecard);
                $dice->roll_dice($this->action['reroll']);
                $this->dice_element(false, $dice);
                $this->score_opts_element($dice, $scorecard);
            }
            elseif (isset($this->action['score_option'])){                               
                if ($this->action['score_option'] == 'bonus'){                    
                    if ($scoreid_data['yahtzee'] == 50){
                        if ($scoreid_data == null){
                            $scoreid_data = 0;
                        }
                        $scoreid_data['bonus'] += 100;
                        $data_to_set['bonus'] = $scoreid_data['bonus'];
                        $scorecard->update_data($data_to_set);
                        unset($data_to_set);
                    }
                    $this->play_turn_head($player_name, $scorecard);
                    $this->bonus_page_handler($dice,$scoreid_data);
                }
                else{                   
                    $end_turn_data = $this->end_turn_page($dice, $scorecard);
                    $data_to_set = $end_turn_data[0];
                    $string = $end_turn_data[1];
                    $scorecard->update_data($data_to_set);
                    $this->play_turn_head($player_name, $scorecard);
                    $this->dice_element(true, $dice);
                    $dice->reset_rolls();
                    echo $string;
                }               			
            }
            elseif (isset($this->action['bonus_option'])){
                $data_evaled = $this->score_eval($dice, $scorecard, false, true);
                $data_to_set = $data_evaled[0];               
                if ($scoreid_data['yahtzee'] == 50){
                    if ($scoreid_data == null){
                            $scoreid_data = 0;
                    }
                    $scoreid_data['bonus'] += 100;
                    $data_to_set['bonus'] = $scoreid_data['bonus'];                       
                }
                $scorecard->update_data($data_to_set);
                $this->play_turn_head($player_name, $scorecard);
                $dice->reset_rolls();
                echo '
    <form action="yahtzee.php" method="post">
        <input type="hidden" name="gameid" value="'.$this->gameid.'" />
        <input type="submit" value="End Turn" />
    </form>';
            }
            elseif (!isset($this->action['reroll']) && !isset($this->action['score_option'])){
                $this->play_turn_head($player_name, $scorecard);
                $this->dice_element(false, $dice);
                $this->score_opts_element($dice, $scorecard);
            }
            foreach ($dice->get_dice() as $key=>$value){
                $data_to_set[$key] = $value;
            }
            $data_to_set['rollnum'] = $dice->get_roll_num();
            $success = $this->raw_data->set_db_data($data_to_set, $this->scoreid);             
            echo '
                </td>
            </tr>
        </table>';
        }
        elseif ($this->scoreid == 'game_complete'){
            $this->end_game_handler();         
        }
    }
    function play_turn_head($player_name, $scorecard){
        $string = '
        <h1>'.$player_name."'s".' Turn</h1>
        <table class="yahtzee_game_page">
            <tr>
                <td>'.$scorecard.'
                </td>
                <td>';
        echo $string;
    }
       
    //Score options element of the game web page
    function score_opts_element($dice, $scorecard){
        echo '
    <h3>Make a score selection</h3>
    <form action="yahtzee.php" method="post">
        <input type="hidden" name="gameid" value="'.$this->gameid.'" />';
        $descriptions = array('aces'=>'Add Ones', 'twos'=>'Add Twos', 
                              'threes'=>'Add Threes<br />',
                              'fours'=>'Add Fours', 'fives'=>'Add Fives', 
                              'sixes'=>'Add Sixes<br />',
                              '3k'=>'Three of a kind', 
                              '4k'=>'Four of a kind<br />',
                              'full_house'=>'Full House<br />',
                              'small_straight'=>'Small Straight', 
                              'large_straight'=>'Large Straight<br />',
                              'yahtzee'=>'Yahtzee', 'chance'=>'Chance<br />',
                              'bonus'=>'Bonus'
                             );
        $scoreid_data = $this->raw_data->get_scoreid_data($this->scoreid);
        foreach ($descriptions as $key=>$value){
            if ($scoreid_data[$key] == null && $key != 'bonus'){
                    echo '
        <input type="radio" name="score_option" value="'.$key.'" />'.
                $descriptions[$key];
            }
            elseif ($key == 'bonus' && $scoreid_data['yahtzee'] != null){ //$scoreid_data['yahtzee'] != null
                $yahtzee_true = $this->score_eval($dice, $scorecard, true, false);
                if ($yahtzee_true){
                    echo '
        <input type="radio" name="score_option" value="bonus" />
            <b class="bonus">BONUS YAHTZEE!!!!!</b>';                
                }                
            }           
        }
        echo'<br />
        <input type="submit" value="Submit Selection" />
    </form>';
    }
    
    //Function for score evaluation. $dice and $scorecard should be objects of 
    //their respective names.
    function score_eval($dice, $scorecard, $get_yahtzee, $bonus_mode){        
        $three_kind = False;        //Primer variables
        $four_kind = False;
        $full_house = False;
        $sm_straight = False;
        $lg_straight = False;
        $yahtzee = False;
        if ($bonus_mode){
            $three_kind = True;
            $four_kind = True;
            $full_house = True;
            $sm_straight = True;
            $lg_straight = True;
        }
        $aces = 0;
        $twos = 0;
        $threes = 0;
        $fours = 0;
        $fives = 0;
        $sixes = 0;
        $all_add = 0;
        $die_nums = $dice->get_dice();
        sort($die_nums);	    
        $straight_die = 0;
        $previous_die = 100;
        $data_to_set = array();
        $string = '';
        foreach ($die_nums as $die){    //The meat of the function
            if ($die != $previous_die){
                $i=0;
                foreach($die_nums as $current){
                    $match = null;
                    eval('$match = (bool)("'.$current.'" == "'.$die.'");');
                    $i = $match ? $i+1 : $i;
                }
                $die_count = $i;	                   
                if ($die_count >= 3){
                    $three_kind = True;
                    $die_pos = array_search($die, $die_nums);
                    if ($die_pos == 0){
                        if ($die_nums[3] == $die_nums[4]){
                            $full_house = True;
                        }
                    }
                    if ($die_pos == 2){
                        if ($die_nums[0] == $die_nums[1]){
                            $full_house = True;
                        }
                    }
                }	            
                if ($die_count >= 4){
                    $four_kind = True;
                }    
                if ($die_count == 5){ 
                    $yahtzee = True;                    
                }
            }
            if ($die == ($previous_die +1)){
                $straight_die = $straight_die +1;
            }
            if ($straight_die >= 3){
                $sm_straight = True;
            }    
            if ($straight_die == 4){
                $lg_straight = True;
            }	        
            if ($die == 1){
                $aces = $aces + $die;
            }            
            if ($die == 2){
                $twos = $twos + $die;
            }            
            if ($die == 3){
                $threes = $threes + $die;
            }    
            if ($die == 4){
                $fours = $fours + $die;
            }            
            if ($die == 5){
                $fives = $fives + $die;
            }
            if ($die == 6){
                $sixes = $sixes + $die;	        
            }
            $all_add = $all_add + $die;
            $previous_die = $die;
        }
        if ($get_yahtzee){
            return $yahtzee;
        }
        if ($bonus_mode){
            if ($this->action['bonus_option'] == "aces"){
                $data_to_set['aces'] = $aces;
            }
            elseif ($this->action['bonus_option'] == "twos"){
                $data_to_set['twos'] = $twos;
            }
            elseif ($this->action['bonus_option'] == "threes"){
                $data_to_set['threes'] = $threes;
            }	  
            elseif ($this->action['bonus_option'] == "fours"){
                $data_to_set['fours'] = $fours;
            }
            elseif ($this->action['bonus_option'] == "fives"){
                $data_to_set['fives'] = $fives;
            }
            elseif ($this->action['bonus_option'] == "sixes"){
                $data_to_set['sixes'] = $sixes;
            }
            elseif ($this->action['bonus_option'] == "3k"){	
                if ($three_kind == True){
                    $data_to_set['3k'] = $all_add;
                }  
                else{
                    $data_to_set['3k'] = 0;
                    $string = "You did not have a 3 of a kind so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['bonus_option'] == "4k"){
                if ($four_kind == True){
                     $data_to_set['4k'] = $all_add;
                }  
                else{
                    $data_to_set['4k'] = 0;
                    $string = "You did not have a 4 of a kind so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['bonus_option'] == "full_house"){
                if ($full_house == True){
                     $data_to_set['full_house'] = 25;
                }  
                else{
                    $data_to_set['full_house'] = 0;
                    $string = "You did not have a full house so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['bonus_option'] == "small_straight"){
                if ($sm_straight == True){
                     $data_to_set['small_straignt'] = 30;
                }  
                else{
                    $data_to_set['small_straight'] = 0;
                    $string = "You did not have a small straight so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['bonus_option'] == "large_straight"){
                if ($lg_straight == True){
                     $data_to_set['large_straight'] = 40;
                }  
                else{
                    $data_to_set['large_straight'] = 0;
                    $string = "You did not have a large straight so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['bonus_option'] == "yahtzee"){
                if ($yahtzee == True){
                     $data_to_set['yahtzee'] = 50;
                }  
                else{
                    $data_to_set['yahtzee'] = 0;
                    $string = "You did not have a yahtzee so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['bonus_option'] == "chance"){
                $data_to_set['chance'] = $all_add;
            }   
            
        }
        else{
            if ($this->action['score_option'] == "aces"){
                $data_to_set['aces'] = $aces;
            }
            elseif ($this->action['score_option'] == "twos"){
                $data_to_set['twos'] = $twos;
            }
            elseif ($this->action['score_option'] == "threes"){
                $data_to_set['threes'] = $threes;
            }	  
            elseif ($this->action['score_option'] == "fours"){
                $data_to_set['fours'] = $fours;
            }
            elseif ($this->action['score_option'] == "fives"){
                $data_to_set['fives'] = $fives;
            }
            elseif ($this->action['score_option'] == "sixes"){
                $data_to_set['sixes'] = $sixes;
            }
            elseif ($this->action['score_option'] == "3k"){	
                if ($three_kind == True){
                    $data_to_set['3k'] = $all_add;
                }  
                else{
                    $data_to_set['3k'] = 0;
                    $string = "You did not have a 3 of a kind so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['score_option'] == "4k"){
                if ($four_kind == True){
                     $data_to_set['4k'] = $all_add;
                }  
                else{
                    $data_to_set['4k'] = 0;
                    $string = "You did not have a 4 of a kind so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['score_option'] == "full_house"){
                if ($full_house == True){
                     $data_to_set['full_house'] = 25;
                }  
                else{
                    $data_to_set['full_house'] = 0;
                    $string = "You did not have a full house so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['score_option'] == "small_straight"){
                if ($sm_straight == True){
                     $data_to_set['small_straight'] = 30;
                }  
                else{
                    $data_to_set['small_straight'] = 0;
                    $string = "You did not have a small straight so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['score_option'] == "large_straight"){
                if ($lg_straight == True){
                     $data_to_set['large_straight'] = 40;
                }  
                else{
                    $data_to_set['large_straight'] = 0;
                    $string = "You did not have a large straight so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['score_option'] == "yahtzee"){
                if ($yahtzee == True){
                     $data_to_set['yahtzee'] = 50;
                }  
                else{
                    $data_to_set['yahtzee'] = 0;
                    $string = "You did not have a yahtzee so a 0 will be entered ".
                          "in its place.";
                }
            }
            elseif ($this->action['score_option'] == "chance"){
                $data_to_set['chance'] = $all_add;
            }   
        }
        return array($data_to_set, $string);        		
    }    
}

class dice{
    //Dice V2.0
    function __construct($dice_values, $roll_num, $gameid){
        $this->dice = $dice_values;
        $this->gameid = $gameid;
        if (!$roll_num || $roll_num == 0){
            $this->roll_num = 0;
            $this->roll_dice(array('die1','die2','die3','die4','die5'));
        }
        else{
            $this->roll_num = $roll_num;
        }
    }
    //Function to simulate rolling dice. $dice_to_roll should be of 
    //type array.
    function roll_dice($dice_to_roll){
        foreach ($dice_to_roll as $key=>$value){
                $this->dice[$value] = rand(1,6);			
        }	
        $this->roll_num += 1;	
    }
    function get_dice(){
        return array('die1'=>$this->dice['die1'], 'die2'=>$this->dice['die2'], 
                     'die3'=>$this->dice['die3'], 'die4'=>$this->dice['die4'],
                     'die5'=>$this->dice['die5']
                    ); 
    }
    function get_roll_num(){
        return $this->roll_num;
    }
    function reset_rolls(){
        $this->roll_num = 0;
    }
}
class yahtzee_game_data{
    //Yahtzee Game Data V1.2
    function __construct($db_connection, $gameid){
        $this->db_connection = $db_connection;
        $this->game_data = $this->get_db_data($gameid);        
    }
    function get_db_data($gameid){
		$scoreids = $this->db_connection->query_select('digitain.yahtzee_scores', array('gameid'=>$gameid), '*', 'scoreid asc');
        $num_scoreids = $scoreids->num_rows;
        for ($i=0; $i<$num_scoreids; $i++){
            $row_scoreid = $scoreids->fetch_assoc();
            $game_data[] = $row_scoreid;            			
        }	
        return $game_data;		
    }
    //Function to retrieve the game data.
    function get_game_data(){
        return $this->game_data;
    }    
    //Function to retrieve data for a specific scoreid.
    function get_scoreid_data($scoreid){
        foreach ($this->game_data as $key=>$value){
            if ($value['scoreid'] == $scoreid){
                $data = $value;
            }
        }
        return $data;       
    }
    //Function to set the database data. Returns a boolean of if it was
    //successful or not.
    function set_db_data($data_changed, $scoreid){
		$success = $this->db_connection->query_update('digitain.yahtzee_scores', $data_changed, array('scoreid'=>$scoreid));        
        return $success;
    }
}
?>