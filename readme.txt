Webtechnologie practicum 3 - Datingsite

===============================================================================
=                        Door:                                                =
=                          Iris Bekker   -  3470644                           = 
=                          Tom Tervoort  -  3470784                           =
=                                                                             =
=                        URL website:                                         =
=                 www.students.science.uu.nl/~3470784/webtech3                =
===============================================================================
_______________________________________________________________________________

Geteste browsers:
 - Mozilla Firefox
 - Google Chrome
 
_______________________________________________________________________________

Bestanden:
 - Een databasemodel is te vinden in dbmodel.png.
 - De databasedefinitie staat in database.sql.
 - brands.txt bevat een lijst van gebruikte merknamen.
 - De daadwerkelijke database (met de creatieve naam database.db) staat in www/database.
 - In de www-map staat alles wat op de server dient te staan. www/system bevat de CodeIgniter
    -libraries, terwijl in www/application de daadwerkelijke applicatiecode staat, op de manier die
    CodeIgniter vereist.
 - www/img bevat afbeeldingen, www/js bevat JQuery, www/css de stylesheets, www/uploads wordt als
    tijdelijke opslagruimte voor geüploade foto's gebruikt, voordat deze worden verwerkt.
 - Alle submappen van www, behalve css, img en js, worden afgeschermt door een .htaccess-bestand 
    die niemand toestaat. Hierdoor is het niet mogelijk bijvoorbeeld via de URL eens even de inhoud
    van de database te bekijken. www/.htaccess herschrijft verder de 'http' in de URL om naar 
    'https', om HTTPS af te dwingen voor de gehele website.

 
_______________________________________________________________________________

Toelichting:
 
 - Elke pagina begint met drie 'header' views, namelijk header (begintags, titel e.d.), nav (de 
    navigatiebalk), en loginbox (waarmee anonieme gebruikers kunnen inloggen of naar het 
    registratieformulier gaan en geregistreerde gebruikers kunnen uitloggen of hun account deleten).
    Elke pagina wordt ook afgesloten met een 'footer'.
    
 - De home-controller is de belangrijkste controller, die zowel het inloggen regelt als de 
    hoofdpagina weergeeft.
    
 - Voor authenticatie is de authentication-library, die weer gebruik maakt van CodeIgniter's sessie-
    mechanisme en de database. In de sessiedata van een ingelogde gebruiker wordt opgeslagen wat 
    de 'userId' van deze gebruiker is, aan de hand waarvan gegevens uit de database kunnen worden
    gehaald. CodeIgniter zorgt ervoor dat sessie-id's in de cookie van de gebruiker gematcht worden
    met de database.
    
 - Het model 'user' bevat verschillende methoden die betrekking hebben op de gebruikerstabel in de
    database, zoals het laden van gebruikers, het creeëren van net geregistreerde gebruikers en het
    toevoegen van likes.
    
 - Het model 'matching' voert matching uit met een bepaalde gebruiker. Het filteren van gebruikers 
    op basis van leeftijd(svoorkeur) en geslacht(svoorkeur) gebeurt via de query, terwijl het
    sorteren op basis van matching distance gebeurt op PHP-niveau.
    
 - De profile-view representeerd een gebruikersprofiel, dit kan zowel een kort overzicht voor 
    zoekresultaten zijn als de volledige pagina die ook nog eens bewerkbaar kan zijn voor de 
    eigenaar van het profiel.
    
 - De zoekfuncties in de search-controller (zowel zoeken op eigenschappen als matchende gebruikers
    in volgorde opvragen) leveren een lijst van user identifiers op (net als de generator van zes 
    willekeurige gebruikers uit het usermodel), deze kunnen vervolgens worden gebruikt door de 
    'profilebrowser'-controller. Deze weergeeft de eerste zes profielen en plakt alle (of tenminste 
    de eerste 600, verder zal de gebruiker niet doorklikken) opgeleverde userId's in een Javascript 
    (de searchbrowser-view). Dit script vraagt vervolgens, na het klikken op de next- of previous-
    knop, via AJAX de profielen te tonen van de volgende zes id's. Deze identifiers worden 
    simpelweg via de URL doorgegeven.
    
_______________________________________________________________________________

Testadminstrator:
 - Gebruikersnaam: admin
 - Wachtwoord: puddingtaart
 
Testgebruikers:
 - E-mail: j.jablabla@gmail.com
 - Wachtwoord: swordfish
 
 - E-mail: u.snowman@dingesmail.be
 - Wachtwoord: 0xE29883
