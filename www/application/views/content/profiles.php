<div class='profilebox'>
    <h2>{username} ({gender})<h2>
    <h3>Leeftijd:</h3><p>{age}</p>
    <h3>Beschrijving:</h3><p>
        <?php   
            if(!{big}) {
                $point = strpos({discription}, '.');
        
                if($point === false or $point > 100) {
                    $disc = substr({discription}, 0, 100). "...";
                }
                else {
                    $disc = substr({discription}, 0, $point+1);
                }
            
                # TODO vind een escape functie.
                echo $disc;
            }
            else {
                echo {discription};
            }
        ?></p><br>
    <h3>Type:</h3><p>
        <?php
            $pers;
            foreach({personality} as $key => $value) {
                $pers .= $key;
            }
            echo $pers;
        ?>
    <?php
        echo "<h2>". $username. "<h2><h3> {". $gender. ")</h3><br>";
        echo "<h3>Leeftijd:</h3> <p>". $age. "</p><br>";
        echo "<h3>Beschrijving:</h3><p>" . $description. "</p><br>";
        echo "<h3>Type:</h3><p>" . $personality. "</p><br>";
        if($big) {
            $strPref = "<h3>Preferenties:</h3><p>";
            foreach($preferences as $pref) {
                strPref .= $pref . ", ";
            }
            echo "</p>";
        }
        else {
            
        }
    
    
    'username'      => true,
        'email'         => false,
        'firstName'     => false,
        'lastName'        => false,
        'gender'        => true,
        'birthdate'        => true,
        'description'    => true,
        'minAgePref'    => true,
        'maxAgePref'    => true,
        'genderPref'    => true,
        'personalityI'    => true,
        'personalityN'    => true,
        'personalityT'    => true,
        'personalityJ'    => true,
        'preferenceI'    => true,
        'preferenceN'    => true,
        'preferenceT'    => true,
        'preferenceJ'    => true,
        'picture'        => false
    ?>
</div>