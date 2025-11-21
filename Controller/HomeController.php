<?php
class HomeController extends Controller {
    public function index() {
        $data = [
            'title' => 'Quiz - Together4Peace',
            'heading' => 'Welcome to the Quiz',
        ];
        
        $this->view('quiz', $data);
    }
}
