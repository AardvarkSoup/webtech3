<form method="post" accept-charset="utf-8" action = "search">
<fieldset>
    I am: 
        <select name="ownGender">
            <option value="male">Male</option>
            <option value="female" {sFemale}>Female</option>
        </select> <br/>
    I am searching for:
        <select name="genderPref">
            <option value="either">Either</option>
            <option value="male" {malePref}>Male</option>
            <option value="female" {femalePref}>Female</option>
        </select> <br/>
    My age:
        <!-- We use the HTML5 number attribute to do client-side validation of the age. If the 
              user's browser does not support this and an invalid value is entered, the server will
              generate an error message indicating the problem. -->
        <input type="number" name="ownAge" min="18" max="122" value="{ownAge}"/> <br/><br/>
    
    Age preference: <br/>
            <!-- Confirming minAge is lower than maxAge is done server-side. -->
    Between <input type="number" name="minAge" min="18" max="122" value="{minAge}"/> 
        and <input type="number" name="maxAge" min="18" max="122" value="{maxAge}"/> <br/><br/>
        
    <fieldset>
        Myers-Briggs personality type: <br/>
            Attitude:     <select name="attitude">
                              <option value="E">Extravert</option>
                              <option value="I" {sI}>Introvert</option>
                          </select>&nbsp
            Perceiving: <select name="perceiving">
                            <option value="S">Sensing</option>
                            <option value="N" {sN}>Intuition</option>
                        </select>&nbsp
            Judging:    <select name="judging">
                            <option value="T">Thinking</option>
                            <option value="F" {sF}>Feeling</option>
                        </select>&nbsp
            Lifestyle:    <select name="lifestyle">
                            <option value="J">Judging</option>
                            <option value="P" {sP}>Perception</option>
                        </select>&nbsp
    </fieldset>
    
    Brand preferences, seperated by comma's: <br/>
    <input type="text" name="brands" value="{brands}" /> <br/>
    
    <input type="submit" value="Search" />
</fieldset>
</form>

<p class="error">{error}</p>