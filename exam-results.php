<?php 
/**
 * Plugin Name: QTL exam results Harvester
 * Description: Harvest exam results for a user based on his username and expose data via WordPress REST API
 * Author: Heracles Michailidis
 * Version: 0.1
 */
 

function harvest_usr($request_data) {

  $results["meta"]["code"] = 200;
  global $wpdb;

  // Get info from url (user)
  $parametersGET = $request_data->get_query_params();
  $access_token = $parametersGET["access_token"];
  $user = $parametersGET["username"];

  $user_token = ''; //todo : fugure out access token business

  if(md5($access_token) === $user_token){ //only strict checks 

    $table_quiz = $wpdb->prefix . "AI_Quiz_tblQuizzes";
    $table_question = $wpdb->prefix . "AI_Quiz_tblQuestions";
    $table_question_pots = $wpdb->prefix . "AI_Quiz_tblQuestionPots";

/*  TODO : write db business...
  $quizes = $wpdb->get_var( 'SELECT COUNT(quizID) FROM '.$table_quiz.' WHERE username=`'.$user.'`');
  $questions = $wpdb->get_var( 'SELECT COUNT(questionID) FROM '.$table_question.' WHERE username=`'.$user.'`');
  $question_pots = $wpdb->get_var( 'SELECT COUNT(potID) FROM '.$table_question_pots.' WHERE username=`'.$user.'`');
*/

  $harvested_exam_data = array('quizes' => $quizes);
  $results["data"] =  $harvested_exam_data;

  return $results;
  }
  else{
    return new WP_Error( '401', 'Authentication Error', array( 'status' => 401 ) );
  }
  
}
// Add WP API custom route/endpoint
// postman test URL ---> /wp-json/exams/v1/examresults?access_token={-access token from dialogflow-}&username={-username-}

add_action( 'rest_api_init', function () {
  register_rest_route( 'exams/v1', 'examsresults', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'harvest_usr',
  ) );
} );
