<?php 
    class DataBaseConnection
    {
        public $conn;

        function __construct($servename, $username, $password, $dbname) {
            $this->conn = new mysqli($servename, $username, $password, $dbname);
            
        }

        function __destruct() {
            $this->conn->close();
        }

        public function dotazatSePrepared($query, $Sstring, $ParamArr){
            $statement = $this->conn->prepare($query);
            $statement->bind_param($Sstring, ...$ParamArr);
            $statement->execute();
            $result = $statement->get_result();
            $FinalArr = [];
            // Process the result set
            if ($result->num_rows > 0) {
            // Output data of each row
                while($row = $result->fetch_assoc()) {
                    array_push($FinalArr, $row);
                }
                
                $statement->close();
                return $FinalArr;
            } else {
                $statement->close();
                return [];
                
            }
        }
    }



?>