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
           try{
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
            catch (Throwable $e) {
                error_log((string) $e);
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage(), // Temporary debugging only
                ]);
            }
        }

        public function vlozitPrepared($query, $Sstring, $ParamArr) {
            try{
                $statement = $this->conn->prepare($query);
                $statement->bind_param($Sstring, ...$ParamArr);
                $statement->execute();
                $result = $statement->get_result();
                
                return [
                    'affected_rows' => $statement->affected_rows,
                    'insert_id' => $statement->insert_id,
                ];
                
            }
            catch (Throwable $e) {
                error_log((string) $e);
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage(), // Temporary debugging only
                ]);
            }
        }
    }



?>