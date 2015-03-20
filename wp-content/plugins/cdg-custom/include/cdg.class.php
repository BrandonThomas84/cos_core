<?php 

class cdg {
	public $page;

	public function cdg(){

		//figure out what class to start
		$this->getPage();

		//check for any page submissions before page load
		$this->checkSubmissions();

		//start class
		$this->display();

	}

	public function getPage(){

		//setting the page variable that dictates whats shown
		if( !empty( $_GET['page'] ) ){

			if( $_GET['page'] == 'ChicoDesignGroup' ){

				$this->page = 'home';

			} else {
				
				$this->page = $_GET['page'];
			}		

			//get the class file
			require_once('cdg_' . $this->page . '.class.php');

			//set the class name
			$className = 'cdg_' . $this->page;

			//call the class
			$this->class = new $className;
		}
	}

	public function checkSubmissions(){

		//check page submissions
		$this->class->checkSubmission();
	}

	public function display(){

		//display the called class
		$this->class->display();
	}
}

?>