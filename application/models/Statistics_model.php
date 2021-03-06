<?php

class Statistics_model extends CI_Model{
    
    public function __construct() {
		parent::__construct();
	}
        
        
        
    function getResidentAnswersFromCategory( $residentID, $categorySetID) {
		$query = $this->db->query(
			"SELECT option_set, question_weight"
			. " FROM a16_webapps_3.answers"
                        . " INNER JOIN a16_webapps_3.question_sets"
                        . " ON a16_webapps_3.answers.question_set = a16_webapps_3.question_sets.id"
			. " WHERE resident_id = '$residentID' AND category_set = '$categorySetID'"
		);
		return $query->result();
    }
    
    function getResidentAnswersFromCategoryOfSession( $residentID, $categorySetID, $session) {
		$query = $this->db->query(
			"SELECT option_set, question_weight"
			. " FROM a16_webapps_3.answers"
                        . " INNER JOIN a16_webapps_3.question_sets"
                        . " ON a16_webapps_3.answers.question_set = a16_webapps_3.question_sets.id"
			. " WHERE resident_id = '$residentID' AND category_set = '$categorySetID' AND session = '$session'"
		);
		return $query->result();
    }
    
    function getScoresCategoryofSession( $residentID, $categorySetID, $session) {
        
                $resident = $this->Resident_model->getResidentById($residentID);
                $answers = $this->getResidentAnswersFromCategoryOfSession( $residentID, $categorySetID, $session);
                $categoryScore = 0;
                $categoryAverageScore = 0;

                //for all questions
                        foreach ($answers as $answer) {
                                $option = $answer->option_set;
                                $weight = $answer->question_weight;
                                $categoryScore += $option/5*100*$weight;
                        }
                if(count($answers) > 0) {
                                $categoryAverageScore = $categoryScore/count($answers); //Change count($answers) to sum of weights
                        }
                return $categoryAverageScore;
    }
    
    function getScoreCategory($residentID, $categorySetID) {

        $answers = $this->getResidentAnswersFromCategory( $residentID, $categorySetID);
        $categoryScore = 0;
        $categoryAverageScore = 0;

        //for all questions
		foreach ($answers as $answer) {
                        $option = $answer->option_set;
                        $weight = $answer->question_weight;
			$categoryScore += $option/5*100*$weight;
		}
        if(count($answers) > 0) {
			$categoryAverageScore = $categoryScore/count($answers); //Change count($answers) to sum of weights
		}
        return $categoryAverageScore;
    }
    
    
    
    function getAvarageScoreCategory($categorySetID) {
        $totalScore = 0;
        $residents = $this->Resident_model->getAllResidents();
        //for all residents
        foreach($residents as $resident) {
            $totalScore += $this->getScoreCategory($resident->id, $categorySetID);
        }

        $avarageScore = $totalScore/count($residents);
        return $avarageScore;
    }
    
    function getAllCategoriesBelow($residentID, $score){
        $categories = $this->Question_model->getAllCategories();
        $returnCategories = [];
        foreach($categories as $category){
            $categoryScore = $this->getScoreCategory($residentID, $category->id);
            if($categoryScore < $score && $categoryScore > 0){
                $categoryName = $this->Question_model->getCategoryName($category->id, $this->session->language);
                array_push($returnCategories, $categoryName[0]->category);
            }
        }
        return $returnCategories;
    }
    
    function generateResidentComment($resident, $averageScore = 50, $goodScore = 70){
        $categories = $this->Question_model->getAllCategories();
        $comment = "hasn't completed any questionaires yet.";
        $good = [];
        $bad = [];
        $average = [];
        foreach($categories as $category){
            $categoryScore = $this->getScoreCategory($resident, $category->id);
            if($categoryScore < $averageScore && $categoryScore > 0){
                $categoryName = $this->Question_model->getCategoryName($category->id, $this->session->language);
                array_push($bad, $categoryName[0]->category);
            }
            elseif($categoryScore >= $averageScore && $categoryScore < $goodScore){
                $categoryName = $this->Question_model->getCategoryName($category->id, $this->session->language);
                array_push($average, $categoryName[0]->category);
            }
            elseif($categoryScore >= $goodScore){
                $categoryName = $this->Question_model->getCategoryName($category->id, $this->session->language);
                array_push($good, $categoryName[0]->category);
            }
        }
 
        $total = count($bad)+count($average)+count($good);
        if($total == 0) return $comment;
        if(count($bad)/$total < 0.2){ //if not many bad categories
            if(count($good)/$total > 0.2){ //if some good categories
                if(count($good)/$total < 0.7){ 
                    $comment = "is doing very well, (s)he scores very good on <ul>";
                    foreach($good as $goodCat){   
                        $comment .= "<li> " . $goodCat;
                        $comment .= $this->generateTrendComment($resident, $goodCat);
                        $comment .= "</li>";
                    }
                    $comment .= "</ul> ";
                    if(count($bad) > 0){ //if some good categories but also bad categories
                        $comment .= "There is however alot of room for improvement on <ul>";
                        foreach($bad as $badCat){
                            $comment .= "<li> " . $badCat;
                            $comment .= "</li>";
                        }
                        $comment .= "</ul>";
                    }
                }
                else{ //if many good categories
                    $comment = "is doing excellent. ";
                    if(count($bad) > 0){ //if many good categories but also bad categories
                        $comment .= "However, (s)he can do alot better on <ul>";
                        foreach($bad as $badCat){
                            $comment .= "<li> " . $badCat;
                            $comment .= "</li>";
                        }
                        $comment .= "</ul> ";
                        if(count($average) > 0){ //if some good categories but also bad and average categories
                            $comment .= "The following topics can also be improved <ul>";
                            foreach($average as $averageCat){
                                $comment .= "<li> " . $averageCat;
                                $comment .= $this->generateTrendComment($resident, $averageCat);
                                $comment .= "</li>";
                            }
                            $comment .= "</ul> ";
                        }
                    }
                    else if(count($average) > 0){ //if some good categories but also average categories
                        $comment .= "However, there is room for improvement on <ul>";
                        foreach($average as $averageCat){                   
                            $comment .= "<li> " . $averageCat;
                            $comment .= $this->generateTrendComment($resident, $averageCat);
                            $comment .= "</li>";
                        }
                        $comment .= "</ul> ";
                    }
                }
            }
            else{ // some bad categories and mostly average
                $comment = "is generally doing well. ";
                if(count($bad) > 0){
                    $comment .= "However, (s)he can do alot better on <ul>";
                    foreach($bad as $badCat){
                        $comment .= "<li> " . $badCat;
                        $comment .= "</li>";
                    }
                    $comment .= "</ul> ";
                    if(count($average) > 0){
                        $comment .= "The following topics can also be improved <ul>";
                        foreach($average as $averageCat){
                            $comment .= "<li> " . $averageCat;
                            $comment .= $this->generateTrendComment($resident, $averageCat);
                            $comment .= "</li>";
                        }
                        $comment .= "</ul> ";
                    }
                }
                
                else if(count($average) > 0){
                    $comment .= "However, there is room for improvement on <ul>";
                    foreach($average as $averageCat){
                        $comment .= "<li> " . $averageCat;
                        $comment .= $this->generateTrendComment($resident, $averageCat);
                        $comment .= "</li>";
                    }
                    $comment .= "</ul> ";
                }
            }
        }
        else{ // if many bad categories
            $comment = "is not doing well, (s)he scores bad on <ul>";
            if(count($bad) > 0){
                foreach($bad as $badCat){
                    $comment .= "<li> " . $badCat;
                    $comment .= "</li>";
                }
                $comment .= "</ul> ";
                if(count($average) > 0){
                    $comment .= "The following topics can also be improved <ul>";
                    foreach($average as $averageCat){
                        $comment .= "<li> " . $averageCat;
                        $comment .= $this->generateTrendComment($resident, $averageCat);
                        $comment .= "</li>";
                    }
                    $comment .= "</ul> ";
                }
            }
        }
        return $comment;        
    }
    
    function generateTrendComment($resident, $category){
        $comment = ", no known trend about " . $category;
        return $comment;
        $scoreLastSession = 20;
        $scoreNextToLastSession = 100;
        
        if($scoreNextToLastSession - $scoreLastSession > 60){
            $comment = ", there is something wrong with " . $category;
        }
        elseif($scoreNextToLastSession - $scoreLastSession > 40){
            $comment = ", " . $category . " is on a very bad trend";
        }
        elseif($scoreNextToLastSession - $scoreLastSession > 20){
            $comment = ", " . $category . " is on a negative trend";
        }
               
        elseif($scoreNextToLastSession - $scoreLastSession > -20){
            $comment = ", " . $category . " is on a positive trend";
        }
        elseif($scoreNextToLastSession - $scoreLastSession > -40){
            $comment = ", " . $category . " is on a very good trend";
        }
        
        
        return $comment;
    }

    function getTotalScoreResident($resident){
        $categorySets = $this->Question_model->getAllCategories(); // as ID
        $totalScore = 0;
        $nonZeroCategories = 0;
        $totalAvarageScore = 0;
        //for all categories
        foreach($categorySets as $categorySet) {
            $scoreCategory = $this->getScoreCategory($resident, $categorySet->id);
            $totalScore += $scoreCategory;
            if($scoreCategory > 0){
                $nonZeroCategories++;
            }
        }
//      
        if($nonZeroCategories > 0){
            $totalAvarageScore = $totalScore/$nonZeroCategories;
        }
        return $totalAvarageScore;
    }
    
    function getTotalScoreCategory($residents, $categorySet){
        $totalScore = 0;
        $nonZeroCategories = 0;
        $totalAvarageScore = 0;
        //for all categories
        foreach($residents as $resident) {
            $scoreCategory = $this->getScoreCategory($resident->id, $categorySet);
            $totalScore += $scoreCategory;
            if($scoreCategory > 0){
                $nonZeroCategories++;
            }
        }
//      
        if($nonZeroCategories > 0){
            $totalAvarageScore = $totalScore/$nonZeroCategories;
        }
        return $totalAvarageScore;
    }
}
