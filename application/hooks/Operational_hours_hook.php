<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Operational_hours_hook
{
    public function sync()
    {
        $CI =& get_instance();
        $CI->load->database();
        $CI->load->model('Operasional_model');

        date_default_timezone_set('Asia/Jakarta');
        $CI->Operasional_model->sync_operasional_harian();
    }
}
