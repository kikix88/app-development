<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dashboard extends CI_Controller {

    private $username;
    private $email;
    private $role;
    private $church;

    function __construct() {
  	    parent::__construct();

        if($this->session->userdata('meetings')) {
            $this->username = $this->session->userdata['meetings']['name'];
            $this->email = $this->session->userdata['meetings']['email'];
            $this->role = "" . $this->session->userdata['meetings']['role'];

            if($this->role == 2) {
                $this->church = $this->session->userdata['meetings']['church'];
            }

        } else {
            header("Location: http://churchbuild.net/index.php/login");
        }
 	}

    public function index() {
        require_once APPPATH . 'third_party/firebase/vendor/autoload.php';

        $factory = (new Factory)->withServiceAccount(APPPATH.'/church-en-firebase-adminsdk-pzrsd-7b7e16235d.json');
        $database = $factory->createDatabase();

        // $history=$database->getReference("history")->orderByChild("start")->getValue();
        // foreach ($history as $key => $value) {
        //     var_dump("<pre>", $value); exit;
        // }


        $host_meeting_church = $this->get_hosts_meetings_churches_count($database);
        $data['hosts'] = $host_meeting_church['host'];
        $data['churches'] = $host_meeting_church['church'];
        $data['meeting_types'] = $host_meeting_church['type'];
        $this->load->view('dashboard', $data);
    }

    public function date_based_filter($from_date, $to_date, $for="meetings") {

        $from_timestamp = strtotime($from_date);
        $to_timestamp = strtotime($to_date);

        if($to_timestamp < $from_timestamp) {
            $tmp = $from_timestamp;
            $from_timestamp = $to_timestamp;
            $to_timestamp = $tmp;
        }

        $dates = $this->get_dates_between($from_timestamp, $to_timestamp);
        $meetings_and_stats = $this->get_meetings_and_stats_with_date($dates);

        if($for = "meetings") {
            return $meetings_and_stats["meetings"];
        } elseif("stats") {
            return $meetings_and_stats["stats"];
        } else {
            return NULL;
        }
    }

    public function get_meetings_and_stats_with_date($dates = array()) {

        if(empty($dates)) {
            return array("meetings" => NULL, "stats" => NULL);
        }

        require_once APPPATH . 'third_party/firebase/vendor/autoload.php';

        $factory = (new Factory)->withServiceAccount(APPPATH.'/church-en-firebase-adminsdk-pzrsd-7b7e16235d.json');
        $database = $factory->createDatabase();

        $history_datewise = array();
        $meetings_datewise = array();
        foreach ($dates as $key => $date) {
            $today_start = $date . "-00:00";
            $today_end = $date . "-23:59";
            $history_datewise[$date] = $database->getReference("history")->orderByChild("start")->startAt($today_start)->endAt($today_end . "\uf8ff")->getValue();
            $meetings_datewise[$date] = $database->getReference("meeting")->orderByChild("start")->startAt($today_start)->endAt($today_end . "\uf8ff")->getValue();
        }

        $all_meetings = array();
        $mcount = array();
        $pcount = array();

        foreach ($history_datewise as $meeting_date => $meetings) {

            if(!isset($mcount[$meeting_date])) {
                $mcount[$meeting_date] = 0;
                $pcount[$meeting_date] = 0;
            }

            $mcount[$meeting_date] += count($meetings);

            foreach ($meetings as $mkey => $meeting) {
                $all_meetings[] = $meeting;
                $pcount[$meeting_date] += substr_count($meeting["attendee"],",")+1;
            }
        }

        foreach ($meetings_datewise as $meeting_date => $meetings) {

            if(!isset($mcount[$meeting_date])) {
                $mcount[$meeting_date] = 0;
                $pcount[$meeting_date] = 0;
            }

            $mcount[$meeting_date] += count($meetings);

            foreach ($meetings as $mkey => $meeting) {
                $all_meetings[] = $meeting;
                $pcount[$meeting_date] += substr_count($meeting["attendee"],",")+1;
            }
        }

        ksort($mcount);
        ksort($pcount);

        $stats = array("meetings" => $mcount, "participants" => $pcount);
        return array("meetings" => $all_meetings, "stats" => $stats);
    }

    public function get_dates_between($from_timestamp, $to_timestamp) {
        if((!$from_timestamp) || (!$to_timestamp)) {
            return NULL;
        }

        $dates = array();
        $current_timestamp = $from_timestamp;
        $step_val = '+1 day';

        while( $current_timestamp <= $to_timestamp ) {
            $dates[] = date("Y.n.j", $current_timestamp);
            $current_timestamp = strtotime($step_val, $current_timestamp);
        }
        return $dates;
    }

    public function get_churches_count($database=NULL) {
        $churches = explode(",", $database->getReference("church_all")->getValue());

        foreach ($churches as $ckey => $church) {

            if(!isset($counts_broad[$church])) {
                $counts_broad[$church] = 0;
            }

            $counts_broad[$church] += count($database->getReference("history")->orderByChild("church")->startAt($church)->endAt($church . "\uf8ff")->getValue());
            $counts_broad[$church] += count($database->getReference("meeting")->orderByChild("church")->startAt($church)->endAt($church . "\uf8ff")->getValue());
        }

        $church_details = array();
        foreach ($counts_broad as $church => $count) {
            $church_details[] = array($count, $church);
        }
        usort($church_details,"cmp_zero_index");
        return $church_details;
    }

    public function get_hosts_meetings_churches_count($database) {

        if(($this->role === NULL) || (!$this->username)) {
            session_start();
            session_destroy();
            unset($_SESSION['meetings']);
            header("Location: http://churchbuild.net/index.php/login");
        } elseif(($this->role == "0") || ($this->role == 1)) {
            $all_meetings = $this->get_meeting_data("host", $this->username);

            foreach ($all_meetings as $mkey => $meeting) {
                if(!isset($types[$meeting["type"]])) {
                    $types[$meeting["type"]] = 0;
                }

                $types[$meeting["type"]] += 1;
            }

            $type_details = array();
            foreach ($types as $type => $count) {
                $type_details[] = array($count, $type);
            }
            usort($type_details,"cmp_zero_index");

            return array("host" => NULL, "type" => $type_details, "church" => NULL);

        } elseif(($this->role == 2) && (isset($this->church))) {

            $all_meetings = $this->get_meeting_data("church", $this->church);
            foreach ($all_meetings as $mkey => $meeting) {

                if(!isset($hosts[$meeting["host"]])) {
                    $hosts[$meeting["host"]] = 0;
                }

                if(!isset($types[$meeting["type"]])) {
                    $types[$meeting["type"]] = 0;
                }

                $hosts[$meeting["host"]] += 1;
                $types[$meeting["type"]] += 1;
            }

            $type_details = array();
            foreach ($types as $type => $count) {
                $type_details[] = array($count, $type);
            }
            usort($type_details,"cmp_zero_index");

            $host_details = array();
            foreach ($hosts as $host => $count) {
                $host_details[] = array($count, $host);
            }
            usort($host_details,"cmp_zero_index");

            return array("host" => $host_details, "type" => $type_details, "church" => NULL);

        } elseif($this->role == 3) {

            $meetings = explode(",", $database->getReference("all_meeting")->getValue());
            $types = explode(",", $database->getReference("meeting_all")->getValue());

            $count_types = array();
            $hosts = array();

            foreach ($meetings as $mkey => $mname) {
                $is_invalid = FALSE;
                $chars = str_split(".$#[]");
                foreach($chars as $invalid_char){
                    if(strpos($mname, $invalid_char)) {
                        $mname = str_replace(".","**",$mname);
                    }
                }

                $version = $database->getReference("meeting/$mname/version")->getValue();

                $name = substr($mname, strrpos($mname, '-') + 1);
                if(!isset($hosts[$name])) {
                    $hosts[$name] = $version;
                } else {
                    $hosts[$name] += $version;
                }

                foreach ($types as $tkey => $type) {
                    if(starts_with($mname, $type)) {
                        if(!isset($count_types[$type])) {
                            $count_types[$type] = $version;
                        } else {
                            $count_types[$type] += $version;
                        }
                    break;
                    }
                }
            }

            $host_details = array();
            foreach ($hosts as $host => $count) {
                $host_details[] = array($count, $host);
            }
            usort($host_details,"cmp_zero_index");

            $type_details = array();
            foreach ($count_types as $type => $count) {
                $type_details[] = array($count, $type);
            }
            usort($type_details,"cmp_zero_index");

            $zero_types = array_diff($types, array_column($type_details, 1));
            foreach ($zero_types as $zkey => $type) {
                $type_details[] = array(0, $type);
            }

            return array("host" => $host_details, "type" => $type_details, "church" => $this->get_churches_count($database));

        } else {
            die("Bad request");
        }
    }

    public function graphs($all_meetings = NULL) {

        foreach ($all_meetings as $mkey => $meeting) {

            $count = substr_count($meeting["attendee"],",")+1;
            if(!isset($name_version[$meeting["name"] . ":" . $meeting["time"]][$meeting['start']])) {
                $name_version[$meeting["name"] . "-" . $meeting["time"]][$meeting['start']] = 0;
            }

            $name_version[$meeting["name"] . "-" . $meeting["time"]][$meeting['start']] += $count;

            $participants = explode(",", $meeting["attendee"]);
            foreach ($participants as $pkey => $participant) {
                $participants[$pkey] = explode("(", rtrim($participant, ")"));
            }

            $emails = array_column($participants, 1);
            foreach ($emails as $ekey => $email) {

                if(!isset($name[$meeting["name"]][$email])) {
                    $name[$meeting["name"]][$email] = 0;
                }

                if(!isset($host[$meeting["host"]][$email])) {
                    $host[$meeting["host"]][$email] = 0;
                }

                if(!isset($church[$meeting["church"]][$email])) {
                    $church[$meeting["church"]][$email] = 0;
                }

                if(!isset($name_time[$meeting["name"] . "-" . $meeting["time"]][$email])) {
                    $name_time[$meeting["name"] . "-" . $meeting["time"]][$email] = 0;
                }

                $name_time[$meeting["name"] . "-" . $meeting["time"]][$email] += $count;
                $name[$meeting["name"]][$email] += $count;
                $host[$meeting["host"]][$email] += $count;
                $church[$meeting["church"]][$email] += $count;
            }
        }

        foreach ($name_time as $key => $value) {
            $u_name_time[$key] = count($value);
        }

        foreach ($name as $key => $value) {
            $u_name[$key] = count($value);
        }

        foreach ($host as $key => $value) {
            $u_host[$key] = count($value);
        }

        foreach ($church as $key => $value) {
            $u_church[$key] = count($value);
        }

        $data['name_version'] = $name_version;
        $data['unique_name_time'] = $u_name_time;
        $data['unique_name'] = $u_name;
        $data['unique_host'] = $u_host;
        $data['unique_church'] = $u_church;
        $this->load->view('graphs', $data);
    }

    public function filter() {

        $button = trim($this->input->post("button"));

        if(!in_array($button, array("excel", "graphs", "tables"))) {
            die("Bad request");
        }

        $from_date = $this->input->post("from_date");
        $to_date = $this->input->post("to_date");

        $date_filter = FALSE;

        if($from_date || $to_date) {
            $date_filter = TRUE;

            if(!$from_date) {
                $from_date = "2020-04-22";
            }

            if(!$to_date) {
                $to_date = date("Y-m-d");
            }
        }

        $church = trim($this->input->post("church"));
        $meeting_type = trim($this->input->post("meeting_type"));
        $host = trim($this->input->post("host"));

        // echo "<pre>";
        // var_dump($from_date, $to_date, $church, $meeting_type, $host, $button);
        // echo "<br><br><br>";
        // $from_timestamp = strtotime($from_date);
        // $to_timestamp = strtotime($to_date);

        // if($to_timestamp < $from_timestamp) {
        //     $tmp = $from_timestamp;
        //     $from_timestamp = $to_timestamp;
        //     $to_timestamp = $tmp;
        // }

        // $dates = $this->get_dates_between($from_timestamp, $to_timestamp);
        // print_r($dates);
        // exit;

        if($this->role === NULL) {
            session_start();
            session_destroy();
            unset($_SESSION['meetings']);
            header("Location: http://churchbuild.net/index.php/login");
        } elseif(($this->role == "0") || ($this->role == 1)) {

            $all_meetings = $this->get_meeting_data("host", $this->username);
            $filename = $this->username;

            if($date_filter) {
                $all_meetings = $this->client_side_date_filter($all_meetings, $from_date, $to_date);
            }

            if($meeting_type) {
                $all_meetings = $this->client_side_filter($all_meetings, "type", $meeting_type);
            }

        } elseif($this->role == 2) {
            $all_meetings = $this->get_meeting_data("church", $this->church);
            $filename = $this->church;

            if($date_filter) {
                $all_meetings = $this->client_side_date_filter($all_meetings, $from_date, $to_date);
            }

            if($meeting_type) {
                $all_meetings = $this->client_side_filter($all_meetings, "type", $meeting_type);
            }

            if($host) {
                $all_meetings = $this->client_side_filter($all_meetings, "host", $host);
            }

        } elseif($this->role == 3) {
            $all_meetings = NULL;
            $data_fetched = FALSE;

            if($church) {
                $all_meetings = $this->get_meeting_data("church", $church);
                $filename = $church;
                $data_fetched = TRUE;
            }

            if($meeting_type) {
                if($data_fetched) {
                    $all_meetings = $this->client_side_filter($all_meetings, "type", $meeting_type);
                } else {
                    $all_meetings = $this->get_meeting_data("type", $meeting_type);
                    $filename = $meeting_type;
                    $data_fetched = TRUE;
                }
            }

            if($host) {
                if($data_fetched) {
                    $all_meetings = $this->client_side_filter($all_meetings, "host", $host);
                } else {
                    $all_meetings = $this->get_meeting_data("host", $host);
                    $filename = $host;
                    $data_fetched = TRUE;
                }
            }

            if($date_filter) {
                if($data_fetched) {
                    $all_meetings = $this->client_side_date_filter($all_meetings, $from_date, $to_date);
                } else {
                    $all_meetings = $this->date_based_filter($from_date, $to_date, $for="meetings");
                    $filename = "date_filtered";
                    $data_fetched = TRUE;
                }
            }

            if(!$data_fetched) {
                $all_meetings = $this->get_meeting_data("all", NULL);
                $filename = "all";
            }

        }

        if($button === "excel") {
            $tables = $this->frequency_table_data($all_meetings);
            $this->download_sheet($tables, $filename);
        } elseif($button === "tables") {
            $tables = $this->frequency_table_data($all_meetings);
            $data['tables'] = $tables;
            $this->load->view('frequency_tables', $data);
        } elseif($button === "graphs") {
            $this->graphs($all_meetings);
        }
    }

    public function cdate() {
        $all_meetings = $this->get_meeting_data("all", NULL);
        $from_date = "2020-05-05";
        $to_date = "2020-05-05";
        echo "<pre>";
        print_r(array_column($this->client_side_date_filter_test($all_meetings, $from_date, $to_date), "start"));
    }

    public function client_side_date_filter_test($all_meetings, $from_date, $to_date) {
        $from_timestamp = strtotime($from_date . " 00:00:00");
        $to_timestamp = strtotime($to_date . " 23:59:59");

        if(empty($all_meetings) || (!$from_timestamp) || (!$to_timestamp)) {
            return array();
        }

        $filtered_meetings = array();

        foreach ($all_meetings as $mkey => $meeting) {

            $start_unix = (int)strtotime(str_replace(".", "-", str_replace("-", " ", $meeting["start"] . ":00")));
            $end_unix = (int)strtotime(str_replace(".", "-", str_replace("-", " ", $meeting["end"] . ":00")));

            if((!$start_unix) || (!$end_unix)) {
                continue;
            } elseif(($from_timestamp <= $start_unix) && ($end_unix <= $to_timestamp)) {

            var_dump("<br><hr>Taken this<br>",$from_timestamp ."  >=  ". $start_unix . "       ".$end_unix. "  <=  " . $to_timestamp);
            var_dump($from_date ."  >=  ". $meeting["start"] . "       ".$meeting["end"]. "  <=  " . $to_date);
                $filtered_meetings[] = $meeting;
            } else {
                var_dump("<br><hr><hr>Skipped this<br>",$from_timestamp ."  >=  ". $start_unix . "       ".$end_unix. "  <=  " . $to_timestamp);
                var_dump($from_date ."  >=  ". $meeting["start"] . "       ".$meeting["end"]. "  <=  " . $to_date);
            }
        }

        return $filtered_meetings;
    }

    public function client_side_date_filter($all_meetings, $from_date, $to_date) {
        $from_timestamp = strtotime($from_date . " 00:00:00");
        $to_timestamp = strtotime($to_date . " 23:59:59");

        if(empty($all_meetings) || (!$from_timestamp) || (!$to_timestamp)) {
            return array();
        }

        $filtered_meetings = array();

        foreach ($all_meetings as $mkey => $meeting) {

            $start_unix = (int)strtotime(str_replace(".", "-", str_replace("-", " ", $meeting["start"] . ":00")));
            $end_unix = (int)strtotime(str_replace(".", "-", str_replace("-", " ", $meeting["end"] . ":00")));

            if((!$start_unix) || (!$end_unix)) {
                continue;
            } elseif(($from_timestamp <= $start_unix) && ($end_unix <= $to_timestamp)) {
                $filtered_meetings[] = $meeting;
            }
        }

        return $filtered_meetings;
    }

    public function client_side_filter($all_meetings, $field, $field_value) {
        if(empty($all_meetings)) {
            return array();
        }

        $filtered_meetings = array();
        foreach ($all_meetings as $mkey => $meeting) {
            if($meeting[$field] === $field_value) {
                $filtered_meetings[] = $meeting;
            }
        }

        return $filtered_meetings;
    }

    public function get_time_diff($start, $end) {
        $start_unix = (int)strtotime(str_replace(".", "-", str_replace("-", " ", $start . ":00")));
        $end_unix = (int)strtotime(str_replace(".", "-", str_replace("-", " ", $end . ":00")));

        if((!$start_unix) || (!$end_unix)) {
            return NULL;
        } else {
            return ($end_unix - $start_unix)/60 . " mins";
        }
    }

    public function get_meeting_data($field, $field_value) {
        $indexed_fields = array("type", "host", "church", "all");
        if(array_search($field, $indexed_fields) === FALSE) {
            return NULL;
        }

        require_once APPPATH . 'third_party/firebase/vendor/autoload.php';
        $factory = (new Factory)->withServiceAccount(APPPATH.'/church-en-firebase-adminsdk-pzrsd-7b7e16235d.json');
        $database = $factory->createDatabase();

        if($field == "all") {
            $meeting = $database->getReference("meeting")->getValue();
            $history = $database->getReference("history")->getValue();
        } else {
            $meeting = $database->getReference("meeting")->orderByChild($field)->startAt($field_value)->endAt($field_value . "\uf8ff")->getValue();
            $history = $database->getReference("history")->orderByChild($field)->startAt($field_value)->endAt($field_value . "\uf8ff")->getValue();
        }

        foreach ($meeting as $mkey => $mvalue) {
            $history[$mkey] = $mvalue;
        }

        return $history;
    }

    public function frequency_table_data($meetings = NULL) {
        if(empty($meetings)) {
            return NULL;
        }

        $collect = array();

        foreach ($meetings as $mkey => $meeting) {
            $participants = explode(",", $meeting["attendee"]);
            foreach ($participants as $pkey => $participant) {
                $participants[$pkey] = explode("(", rtrim($participant, ")"));
            }

            $collect[$meeting['host']][$meeting['name']][$meeting['version']]["started_at"] = $meeting['start'];
            $collect[$meeting['host']][$meeting['name']][$meeting['version']]["ended_at"] = $meeting['end'];
            $collect[$meeting['host']][$meeting['name']][$meeting['version']]["time"] = $this->get_time_diff($meeting['start'], $meeting['end']);
            $collect[$meeting['host']][$meeting['name']][$meeting['version']]["total_attended"] = count($participants);

            foreach ($participants as $pkey => $pvalue) {
                if(!isset($pvalue[1])) {
                    unset($participants[$pkey]);
                    continue;
                }

                $attendance[$meeting['host']][$pvalue[1]][$meeting['name']][$meeting['version']] = 1;
                $attendance[$meeting['host']][$pvalue[1]]['name'] = $pvalue[0];
            }
        }

        $rows = array();
        $tables = array();

        $i = 0;

        $info_start = NULL;
        $table_data_start = NULL;

        foreach ($collect as $hkey => $host) {
            foreach ($host as $nkey => $name) {

                ksort($name);

                $info_start = $i;

                $row = [NULL, NULL, "Meeting Name"];
                foreach ($name as $version => $meeting) {
                    $row[] = $nkey;
                }
                $rows[] = $row;
                $i++;

                $row = [NULL, NULL, "Version"];
                foreach ($name as $version => $meeting) {
                    $row[] = $version;
                }
                $rows[] = $row;
                $i++;

                $row = [NULL, NULL, "Start"];
                foreach ($name as $version => $meeting) {
                    $row[] = $meeting['started_at'];
                }
                $rows[] = $row;
                $i++;

                $row = [NULL, NULL, "End"];
                foreach ($name as $version => $meeting) {
                    $row[] = $meeting['ended_at'];
                }
                $rows[] = $row;
                $i++;

                $row = [NULL, NULL, "Time"];
                foreach ($name as $version => $meeting) {
                    $row[] = $meeting['time'];
                }
                $rows[] = $row;
                $i++;

                $row = [NULL, NULL, "Total Attended"];
                foreach ($name as $version => $meeting) {
                    $row[] = $meeting['total_attended'];
                }
                $rows[] = $row;
                $i++;

                $row = ["Host", "Attendee", "Email"];
                foreach ($name as $version => $meeting) {
                    $row[] = "attended?";
                }
                $row[] = "Total Mtgs Attended";
                $rows[] = $row;
                $i++;

                $table['info'] = array_slice($rows, $info_start);
                $table_data_start = $i;

                foreach ($attendance[$hkey] as $participant_email => $participant) {
                    $row = [$hkey, $participant['name'], $participant_email];
                    foreach ($name as $version => $meeting) {
                        $row[] = "" . (int)isset($participant[$nkey][$version]);
                    }

                    $count = 0;
                    foreach ($participant as $ckey => $cvalue) {
                        if($ckey == $nkey) {
                            $count += count($cvalue);
                        }
                    }

                    if(!$count) {
                        continue;
                    }

                    $row[] = "". $count;
                    $rows[] = $row;
                    $i++;
                }
                $table['data'] = array_slice($rows, $table_data_start);

                $tables[] = $table;
                $table = array();
                $rows[] = array(); $i++;
                $rows[] = array(); $i++;
                $rows[] = array(); $i++;
            }
        }
        return $tables;
    }

    public function download_sheet($tables, $filename = "meetings") {
        $rows = array();
        foreach ($tables as $tkey => $table) {
            foreach ($table['info'] as $ti_key => $ti_row) {
                $rows[] = $ti_row;
            }

            foreach ($table['data'] as $td_key => $td_row) {
                $rows[] = $td_row;
            }

            $rows[] = array();
            $rows[] = array();
            $rows[] = array();
        }

        require_once APPPATH . 'third_party/excel/vendor/autoload.php';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        if(empty($rows)) {
            $rows = array(array("No Meetings with the selected options."));
        }
        $spreadsheet->getActiveSheet()->fromArray($rows,NULL,'A1');
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=$filename.xlsx");
        $writer->save('php://output'); exit;
    }
}
