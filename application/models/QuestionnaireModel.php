<?php
/**
 * Created by PhpStorm.
 * User: HiJack
 * Date: 2018/11/29
 * Time: 10:18
 */

class QuestionnaireModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
}