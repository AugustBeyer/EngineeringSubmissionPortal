<?php
session_start();
require "db_config.php";
$username = $_POST["username"];
$password = $_POST["password"];

/*
Array
(
    [count] => 1
    [0] => Array
        (
            [groupmembership] => Array
                (
                    [count] => 7
                    [0] => cn=Everyone,ou=Users,o=scu
                    [1] => cn=SCU_Faculty,ou=Users,o=scu
                    [2] => cn=COEN_Group,ou=Computer Engineering,o=scu
                    [3] => cn=FSF-Users,ou=Users,o=scu
                    [4] => cn=CSL_OSL_Awards_READ-ONLY,ou=Center for Student Leadership,o=scu
                    [5] => cn=CSL_OSL_Script_READ-ONLY,ou=Center for Student Leadership,o=scu
                    [6] => cn=VPN_AnyConnect,ou=VPN,o=scu
                )
            [0] => groupmembership
            [count] => 1
            [dn] => cn=DAtkinson,ou=Users,o=scu
        )
)
*/

/*
$ldapconn = ldap_connect("ldaps://129.210.8.7");
$attrs = array("groupMembership");

$results = ldap_search($ldapconn, "o=scu", "uid=$username", $attrs);
$info = ldap_get_entries($ldapconn, $results);

if ($info["count"] == 1)
    if (@ldap_bind($ldapconn, $info["0"]["dn"], $password))
		$authenticated = TRUE;
*/
$authenticated = TRUE;
if ($authenticated)
{
	//is a student
	if($info["0"]["groupmembership"]["1"] != 'cn=SCU_Faculty,ou=Users,o=scu')
	{
		try 
		{
		    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    //Get the student_id from scu_username in students table
		    $stmt = $dbh->prepare("SELECT student_id FROM students WHERE scu_username = :scu_username");
		    $stmt->execute(array(':scu_username' => $username));
		    $student_id_result = $stmt->fetch(PDO::FETCH_ASSOC)["student_id"];

		    //Student has not logged in yet; need to insert into our database
		    if(empty($student_id_result))
		    {
		    	$stmt = $dbh->prepare("INSERT INTO students (scu_username) VALUES (:scu_username)");
		    	$stmt->bindParam(':scu_username', $username);
		    	$stmt->execute();
		    	$student_id_result = $dbh->lastInsertId();
		    }

		    $_SESSION["user_id"] = $student_id_result;
		    $_SESSION["isAdvisor"] = 0;

		    $dbh = null;
			header('Location: ../web_front/student/home.php');
		}
		catch(PDOException $e)
		{
		    echo "Error: " . $e->getMessage();
		}
	}
	//is an advisor
	else
	{
		try 
		{
		    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    //Get the primary_advisor_id forom the advisor_username
		    $stmt = $dbh->prepare("SELECT primary_advisor_id FROM advisors WHERE advisor_username = :advisor_username");
		    $stmt->execute(array(':advisor_username' => $username));
		    $advisor_id_result = $stmt->fetch(PDO::FETCH_ASSOC)["primary_advisor_id"];

		    //Advisor has not logged in yet; need to insert into our database
		    if(empty($advisor_id_result))
		    {
		    	$stmt = $dbh->prepare("INSERT INTO advisors (advisor_username) VALUES (:advisor_username)");
		    	$stmt->bindParam(':advisor_username', $username);
		    	$stmt->execute();
		    	$advisor_id_result = $dbh->lastInsertId();
		    }

		    $_SESSION["user_id"] = $advisor_id_result;
		    $_SESSION["isAdvisor"] = 1;
		    //echo $result["primary_advisor_id"] . " success\r\n";

		    $dbh = null;
			header('Location: ../web_front/advisor/home.php');
		}
		catch(PDOException $e)
		{
		    echo "Error: " . $e->getMessage();
		}
	}
}
else
{
	echo "Username and password mismatch\n";
}
?>
