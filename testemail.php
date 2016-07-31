<?php
02	 
03	class Email_reader {
04	 
05	    // imap server connection
06	    public $conn;
07	 
08	    // inbox storage and inbox message count
09	    private $inbox;
10	    private $msg_cnt;
11	 
12	    // email login credentials
13	    private $server = 'localhost';
14	    private $user   = 'info.biotechin';
15	    private $pass   = 'bio2345tech';
16	    private $port   = 143; // adjust according to server settings
17	 
18	    // connect to the server and get the inbox emails
19	    function __construct() {
20	        $this->connect();
21	        $this->inbox();
22	    }
23	 
24	    // close the server connection
25	    function close() {
26	        $this->inbox = array();
27	        $this->msg_cnt = 0;
28	 
29	        imap_close($this->conn);
30	    }
31	 
32	    // open the server connection
33	    // the imap_open function parameters will need to be changed for the particular server
34	    // these are laid out to connect to a Dreamhost IMAP server
35	    function connect() {
36	        $this->conn = imap_open('{'.$this->server.'/notls}', $this->user, $this->pass);
37	    }
38	 
39	    // move the message to a new folder
40	    function move($msg_index, $folder='INBOX.Processed') {
41	        // move on server
42	        imap_mail_move($this->conn, $msg_index, $folder);
43	        imap_expunge($this->conn);
44	 
45	        // re-read the inbox
46	        $this->inbox();
47	    }
48	 
49	    // get a specific message (1 = first email, 2 = second email, etc.)
50	    function get($msg_index=NULL) {
51	        if (count($this->inbox) <= 0) {
52	            return array();
53	        }
54	        elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) {
55	            return $this->inbox[$msg_index];
56	        }
57	 
58	        return $this->inbox[0];
59	    }
60	 
61	    // read the inbox
62	    function inbox() {
63	        $this->msg_cnt = imap_num_msg($this->conn);
64	 
65	        $in = array();
66	        for($i = 1; $i <= $this->msg_cnt; $i++) {
67	            $in[] = array(
68	                'index'     => $i,
69	                'header'    => imap_headerinfo($this->conn, $i),
70	                'body'      => imap_body($this->conn, $i),
71	                'structure' => imap_fetchstructure($this->conn, $i)
72	            );
73	        }
74	 
75	        $this->inbox = $in;
76	    }
77	 
78	}
79	 
80	?>

