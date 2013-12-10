<?php                                    
                        //Verbindungsherstellung Datenbank
                        //Wird per include in /include/login.php eingebunden
                        //da login.php als Erstes in jedes Dokument eingebunden wird kann von dort aus auf
                        //alle folgenden Variablen zugegriffen werden
                    
                        import_request_variables('gP');
                        $tag[0] = 'Sonntag';
                        $tag[1] = 'Montag';
                        $tag[2] = 'Dienstag';
                        $tag[3] = 'Mittwoch';
                        $tag[4] = 'Donnerstag';
                        $tag[5] = 'Freitag';
                        $tag[6] = 'Samstag';

                        $tagnummer = date('w'); // Tag ermitteln
                        $wochentag = $tag[$tagnummer];
                        $time = time();

                        $datum = date('Y-m-d');
                        $datum_morgen1 = strtotime(date('Y-m-d', strtotime($datum)) . ' +1 day');
                        $datum_morgen = date('Y-m-d',$datum_morgen1) ;
                        $uhrzeit = date('H.i');
                        $db = mysql_connect('10.21.55.15', 'root', '49Blaatin');
                        $db_sel = mysql_select_db('kfztalk');
                        $server = 'http://10.211.55.15';                                                                
?> 