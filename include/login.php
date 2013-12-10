<?php
    //Folgendes Skript wird zum Anmelden benutzt und �berpr�ft nach der Anmeldung   
    //bei jedem Seitenaufruf ob die in der Session gespeicherten Variablen stimmen.
    //Skript wird in jeder Ausgabeseite als erstes eingebunden 
    
    //Konfigurationsdatei wird eingebunden  
    include('include/config.php');
    
    //Variablen werden aus Session abgefragt wenn Sessionvariablen gesetzt sind und logout nicht gesetzt ist 
    if(isset($_SESSION['user']) AND !isset($logout))
    {
        $user = $_SESSION['user'];
        $pw = $_SESSION['pw'];
    }
    
   
    //Ausgabe Loginbereich wenn weder Passwort �ber Anmeldeformular (pw1) eingegeben wurde, noch verschl�sseltes (pw) �bergeben wird.
    //bzw. wenn Logout gedr�ckt wurde
    if((!isset($pw)  AND !isset($pw1) AND !isset($_SESSION['user'])) OR isset($logout) )
    {
        echo "
                  <form action='index.php' method='post'>
                    <table><tr>
                    <td><img src='images/benutzer.png' alt='benutzer' title='Benutzername' style='width:100%'/></td>
                    <td><input type='text' name='user' style='width:90%; height:90%'  size='35'/></td>
                    <td><img src='images/pw.png' alt='passwort' title='Passwort' style='width:100%' /></td>
                    <td><input type='password' name='pw1'style='width:90%; height:90%'  size='35'/></td>
                    <td><input type='image' src='images/anmelden.png' alt='Absenden' style='width:100%'/></td>
                    </tr></table>
                  </form>         
             ";
    }    
    //Sollten Anmeldeinformationen ausgef�llt worden sein oder Sessionvariablen vorhanden sein
    else
    {           
                //Sollte Passwort (pw1) �ber Loginbereich eingegeben werden,
                //wird dieses automatisch verschl�sselt und als pw gespeichert     
                if(isset($pw1))
                    $pw = md5($pw1);
    
                //Abfrage Benutzer wo email = email und verschl�sseltes pw = pw
                    $sql = "SELECT * FROM edv_user WHERE (user = '$user' AND password = '$pw')";
                    $res = mysql_query($sql);
                    $num = mysql_num_rows($res);
                    
                //Wenn Abfrage erfolgreich -> Richtige Daten wurden eingegeben 
                //Speichern der Benutzerinformationen als globale Variablen
                //Es kann sp�ter in jedem Skript auf die folgenden Variablen zugegriffen werden um damit zu arbeiten
                //Wichtig!!!: Die Variablen d�rfen im weiteren Verlauf nicht �berschrieben werden, da dies sonst zu Fehlern 
                //f�hren kann   
                if($num > 0)
                {                    
                    //E-Mail des Benutzers
                    $user = mysql_result($res,0,'user');
                    //Passwort des Benutzers (Wird bisher nicht weiter verwendet)
                    $password = mysql_result($res,0,'password');
                    //Nachname des Benutzers
                    $name = mysql_result($res,0,'name');
                    //Vorname des Benutzers
                    $vorname = mysql_result($res,0,'vorname');
                    //ID des Benutzers zur individuellen Zuordnung
                    $id_user = mysql_result($res,0,'id');
                    //Handelt es sich bei dem Benutzer um einen Administrator (0=Nein,1=Ja)
                    $admin = mysql_result($res,0,'admin');
                    //Account des Benutzers aktiviert? (0=Nein,1=Ja)
                    $activation_status = mysql_result($res,0,'activation_status');
                    //Jahrgang des Benutzers
                    $user_jahrgang =mysql_result($res,0,'jahrgang');
                    
                    
                    //Wenn Benutzeraccount aktiviert ist und $navi noch nicht in der Session vorhanden ist
                    if($activation_status == 1)
                        if(!isset($_SESSION['navi']))
                            $_SESSION['navi'] = true; // Sorgt f�r die Ausgabe des Benutzermen�s wenn Benutzer aktiviert ist
                    
                    //Abfrage der IP-Adresse des Benutzers
                    $ip = $_SERVER['REMOTE_ADDR'];
                    
                    //Eintrag des aktuellen Zeitstempels und der IP des Benutzers 
                    //Bei jedem Seitenaufruf
                    $sql_datum = "UPDATE edv_user SET timestamp = '$time', ip_user = '$ip' WHERE $id_user = id;";
                    mysql_query($sql_datum);
                    
                    //Wenn Benutzer aktiviert wurde und Variablen noch nicht in der Session gespeichert wurden
                    //(Sprich direkt nach Login)
                    if($activation_status == 1 AND !isset($_SESSION["user"]) AND !isset($_SESSION["pw"]) )
                    {
                        $_SESSION["user"] = "$user";
                        $_SESSION["pw"] = "$pw";    
                    }
                    
                    //�berpr�ft ob Benutzer Mails �ber Server verschickt hat.
                    //Wenn ja un das Versenden l�nger als eine Stunde her ist wird Variable gel�scht
                    // -> Erneutes Versenden m�glich
                    if(isset($_SESSION['mailsend']))
                        if($_SESSION['mailsend']+(60*60) <= time())
                            unset($_SESSION['mailsend']);
                                                 
                }
                //Wenn kein Benutzer mit den passenden eingegebenen Daten gefunden wird
                //Ausgabe Fehlermeldung
                else
                {
                    echo "<div class='warning'>Anmeldung fehlgeschlagen! <a href=''>Erneut versuchen!</a></div>" ;
                    
                }
                //Ausgabe einer Fehlermeldung falls der Benutzer nocht nicht aktiviert ist
                //Je nach Konfiguration erfolgt dies �ber den Admin, �ber die Best�tigung per Mail oder automatisch
                if(isset($activation_status))
                {
                    if($activation_status == 0)
                    echo "<div class='warning'>Account nocht nicht aktiviert.</div>" ;    
                }
                
    }
  

        
         

?>