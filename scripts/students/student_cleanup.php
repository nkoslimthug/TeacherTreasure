<?php
function student_cleanup()
{
	if (isset($_SESSION['question_number']))
	{
		unset ($_SESSION['question_number']);
	}
	if (isset($_SESSION['question_counter']))
	{
		unset ($_SESSION['question_counter']);
	}
	if (isset($_SESSION['batch_size']))
	{
		unset ($_SESSION['batch_size']);
	}
	if (isset($_SESSION['story_id']))
	{
		unset ($_SESSION['story_id']);
	}
	if (isset($_SESSION['image_id']))
	{
		unset ($_SESSION['image_id']);
	}
	if (isset($_SESSION['story_title']))
	{
		unset ($_SESSION['story_title']); 
	}
	if (isset($_SESSION['story_content']))
	{
		unset ($_SESSION['story_content']); 
	}
	if (isset($_SESSION['subject_name']))
	{
		unset ($_SESSION['subject_name']);
	}
	if (isset($_SESSION['grade']))
	{
		unset ($_SESSION['grade']);
	}
	if (isset($_SESSION['paper_type']))
	{
		unset ($_SESSION['paper_type']);
	}
	if (isset($_SESSION['topic_name']))
	{
		unset ($_SESSION['topic_name']);
	}
	if (isset($_SESSION['paper_description']))
	{
		unset ($_SESSION['paper_description']);
	}
	if (isset($_SESSION['subject_code']))
	{
		unset ($_SESSION['subject_code']);
	}
	if (isset($_SESSION['question_score']))
	{
		unset ($_SESSION['question_score']);
	}
	if (isset($_SESSION['topic_id']))
	{
		unset ($_SESSION['topic_id']);
	}
	if (isset($_SESSION['start_timestamp']))
	{
		unset ($_SESSION['start_timestamp']);
	}
	if (isset($_SESSION['start_time']))
	{
		unset ($_SESSION['start_time']);
	}
	if (isset($_SESSION['true_answer']))
	{
		unset ($_SESSION['true_answer']);
	}
	if (isset($_SESSION['question_complete']))
	{
		unset ($_SESSION['question_complete']);
	}
	if (isset($_SESSION['answer_T']))
	{
		unset ($_SESSION['answer_T']);
	}
	if (isset($_SESSION['answered_counter']))
	{
		unset ($_SESSION['answered_counter']);
	}
	if (isset($_SESSION['correct_answer']))
	{
		unset ($_SESSION['correct_answer']);
	}
	if (isset($_SESSION['end_time']))
	{
		unset ($_SESSION['end_time']);
	}
	if (isset($_SESSION['end_timestamp']))
	{
		unset ($_SESSION['end_timestamp']);
	}
	if (isset($_SESSION['duration']))
	{
		unset ($_SESSION['duration']);
	}
	if (isset($_SESSION['size']))
	{
		unset ($_SESSION['size']);
	}
	if (isset($_SESSION['message_batch_complete']))
	{
		unset ($_SESSION['message_batch_complete']);
	}
	if (isset($_SESSION['percentage']))
	{
		unset ($_SESSION['percentage']);
	}
	if (isset($_SESSION['score_log_msg']))
	{
		unset ($_SESSION['score_log_msg']);
	}
	if (isset($_SESSION['score_counter']))
	{
		unset ($_SESSION['score_counter']);
	}
	if (isset($_SESSION['question']))
	{
		unset ($_SESSION['question']);
	}
	if (isset($_SESSION['display_question']))
	{
		unset($_SESSION['display_question']);  
	}
	if (isset($_SESSION['interchangeable']))
	{
		unset($_SESSION['interchangeable']); 
	}
	if (isset($_SESSION['total_paper_marks']))
	{
		unset ($_SESSION['total_paper_marks']);
	}
	if (isset($_SESSION['start_timestamp']))
	{
		unset ($_SESSION['start_timestamp']);
	}
	if (isset($_SESSION['start_time']))
	{
		unset ($_SESSION['start_time']);
	}
	if (isset($_SESSION['true_answer']))
	{
		unset ($_SESSION['true_answer']);
	}
	if (isset($_SESSION['question_complete']))
	{
		unset ($_SESSION['question_complete']);
	}
	if (isset($_SESSION['answer_T']))
	{
		unset ($_SESSION['answer_T']);
	}
	if (isset($_SESSION['answered_counter']))
	{
		unset ($_SESSION['answered_counter']);
	}
	if (isset($_SESSION['section_id']))
	{
		unset ($_SESSION['section_id']);
	}
	if (isset($_SESSION['topic_id']))
	{
		unset ($_SESSION['topic_id']);
	}
	if (isset($_SESSION['section_quota']))
	{
		unset ($_SESSION['section_quota']);
	}
	if (isset($_SESSION['quota_tracker']))
	{
		unset ($_SESSION['quota_tracker']);
	}
	if (isset($_SESSION['section_name']))
	{
		unset ($_SESSION['section_name']);
	}
	if (isset($_SESSION['section_count']))
	{
		unset ($_SESSION['section_count']);
	}
	if (isset($_SESSION['section_counter']))
	{
		unset ($_SESSION['section_counter']);
	}
}
?>