<?php

namespace App\Libraries;

class Icalendar 
{
    var $data  = "";
    var $start = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\n";
    var $end   = "END:VCALENDAR\n";

    public function add($start,$end,$name,$description,$location) {
        $this->data .= "BEGIN:VEVENT\nDTSTART:".date("Ymd\THis\Z",strtotime($start))."\nDTEND:".date("Ymd\THis\Z",strtotime($end))."\nLOCATION:".$location."\nTRANSP: OPAQUE\nSEQUENCE:0\nUID:\nDTSTAMP:".date("Ymd\THis\Z")."\nSUMMARY:".$name."\nDESCRIPTION:".$description."\nPRIORITY:1\nCLASS:PUBLIC\nBEGIN:VALARM\nTRIGGER:-PT10080M\nACTION:DISPLAY\nDESCRIPTION:Reminder\nEND:VALARM\nEND:VEVENT\n";
    }
    public function save() {
        return $this->getData();
    }
    public function getData() {
        return $this->start . $this->data . $this->end;
    }
}
