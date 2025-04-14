<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 08-03-2025

    HIGHLEVEL DESCRIPTION: 
    This page offers the user control over certain aspects of their experiences.
        1. Set a target language that they are learning.
        2. Add new words categories.
        3. Edit / Delete word categories.
        4. Manage orphaned words, i.e. words whose category has been deleted.

    DETAILS:

    #############################
    ## CATEGORIES MAJOR CHANGE ##
    #############################
    The way Categories work have been altered to cater for users editing / deleting categories. This is because I need to ensure that if a user makes a change
    to a category, it is only to their version and it won't impact on other users. The changes are:
        1. Created two new tables:
            tb_categories - holds all the original categories, never changes
            tb_user_categories - holds the users list of categories, can change
        2. When a new user is created, the rows on tb_categories are copied into tb_user_categories. It is this version that the user can update / delete and
        it will be this table that populates category drop-downs on the site.


    ##############################
    ## PREFERENCES PROCESS FLOW ##
    ##############################       
    
    1. Add New Category | user-preferences.php > add-category.php
    2. Delete Cateogry  | user-preferences.php > list-category.php > delete-category.php
    3. Edit Category    | user-preferences.php > list-category.php > edit-category.php > update-category.php
    4. Orphaned Words   | user-preferences.php > list-orphaned-words.php > edit-orphaned-category.php > update-orphaned-category.php
    5. Target Langauge  | user-preferences.php > update-target-language.php

    CHANGE HISTORY:

    20-03-25:   Testing a new alternative look to buttons and input fields. Using new css elements begining with 'alternate-'

    

-->
<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;
    $user_id = $_SESSION['user_id'];

	include("include/connection.php");
	include("include/functions.php");
	include("include/error-logging.php");
    include("include/badge-record-functions.php");

    $user_data = check_login($conn); //if logged in, this variable will contain the user data

    //Populate drop-down of categories
    $result = populate_category_dropdown();

    //Populate drop-down of languages.
    $language_array = populate_language_dropdown();

    //Find users preference for case sensitivty during a test.
    $case_sensitive = '';
    $case_sensitive = check_case_sensitivity();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Up: User Preferences</title>
    <link rel="stylesheet" href="css/index-stylin.css">
</head>

<script>
    function updateInput() {
        var selectedValue = document.getElementById("category").value;
        document.getElementById("category_desc").value = selectedValue;
    }
</script>

<body>

<header>
  <?php include "include/nav.php" ?>
</header>

<main>

<div class="main-section">
        <div class="page-title">
            <h1>USER PREFERENCES</h1>
        </div>
</div>

    <!-- Notification of successful category input. -->
    <?php 
        if(isset($_GET['category-added'])){ 
    ?>
    <div class="alert success">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        Success: A new category has been added.
    </div>
    <?php 
        //New category has been added so refresh category drop-down
        $result = populate_category_dropdown();
    } ?>

    <!-- Notification of successful category input. -->
    <?php 
    if(isset($_GET['category-not-added'])){ 
    ?>
    <div class="alert error">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        Warning: A new category has not been added.
    </div>
    <?php } ?>

    <!-- Notification of successful TL input. -->
    <?php 
    if(isset($_GET['language-updated'])){ 
    ?>
    <div class="alert success">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        Success: Your target language has been set.
    </div>
    <?php  } ?>

    <!-- Notification of unsuccessful TL input. -->
    <?php 
    if(isset($_GET['language-not-updated'])){ 
    ?>
    <div class="alert error">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        Warning: A target language has not been set.
    </div>
    <?php } ?>

    <!-- Notification of successful TL input. -->
    <?php 
    if(isset($_GET['streaks-updated'])){ 
    ?>
    <div class="alert success">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        Success: Your targets have been set.
    </div>
    <?php  } ?>

    <!-- Notification of unsuccessful TL input. -->
    <?php 
    if(isset($_GET['streaks-not-updated'])){ 
    ?>
    <div class="alert error">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        Warning: Your targets have not been set.
    </div>
    <?php } ?>

    <!-- Notification of successful case sensitivity update. -->
    <?php 
    if(isset($_GET['case-sens-updated'])){ 
    ?>
    <div class="alert success">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        Success: Your case sensitivity setting for tests has been updated.
    </div>
    <?php  } ?>

    <!-- Notification of unsuccessful TL input. -->
    <?php 
    if(isset($_GET['case-sens-failed-updated'])){ 
    ?>
    <div class="alert error">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        Warning: The update for case sensitivty settings has failed.
    </div>
    <?php } ?>



<div style="margin: auto; width: 90%;" class="cards-wrapper">

        <!-- Left card. -->
        <div  class="card">
            <div class="img-container a">
                <!-- <img src="images/translation-icon.png" alt=""> -->
            </div>
            <h1>CHOOSE TARGET LANGUAGE</h1>

            <!-- Dropdown to select category -->
            <p>&nbsp;</p>
            <div class="card-form">
                <form method="POST" action="update-target-language.php">
                <label for="language" class="alternate-input-label">CHOOSE YOUR TARGET LANGUAGE</label>
                    <select name="lang_code" id="language" class="alternate-input-field">
                    <option value="">-- Languages --</option>    
                        <?php
                            if ($language_array->num_rows > 0 ) {
                            while ($row = $language_array->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($row['lang_code']) . '">' . htmlspecialchars($row['lang_name']) . '</option>';
                            }
                            }
                        ?>
                    </select>
                    <div class="alternate-button-wrap">
                        <button class="alternate-button" name="saveLanguage" type="submit">SAVE LANGUAGE</button><p></p>
                    </div>
                    </form>
            </div>
            <p>&nbsp;</p>
        </div>

        <!-- Middle card. -->
        <div  class="card">
            <div class="img-container b">
                <!-- <img src="images/edit-list-icon.png" alt=""> -->
            </div>
            <h1>ADD NEW WORD CATEGORIES</h1>

            <!-- Add new form. -->
            <form class="card-form" action="add-category.php" method="POST">						
                <div class="alternate-input">
                    <input type="text" class="alternate-input-field" name="category_desc" id="category_desc" required/>
                    <label class="alternate-input-label" for="category_desc">ADD NEW CATEGORY</label>
                </div>
                <div class="alternate-button-wrap">
                    <button class="alternate-button" name="SubmitButton" type="submit">ADD CATEGORY</button>
                </div>
			</form>
            <div style="display:flex;align-items:center;justify-content:center; border: 0px solid blue; max-height: 20px;">
                <p>OR</p>
            </div>
            <form action="list-category.php" method="post" class="form-card">
                <div class="alternate-button-wrap">
                    <button class="alternate-button" name="AddWordButton" type="submit">EDIT / DELETE CATEGORY</button><p></p>
                </div>
            </form>

        <!-- Dropdown to select category -->
        <p>&nbsp;</p>
        <div class="card-form">
            <form method="post">
            <label for="category" class="alternate-input-label">VIEW YOUR CATEGORIES</label>
                <select name="category" id="category" class="alternate-input-field" onchange="updateInput()">
                <option value="">-- Category --</option>    
                    <?php
                        if ($result->num_rows > 0 ) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($row['category_desc']) . '">' . htmlspecialchars($row['category_desc']) . '</option>';
                        }
                        }
                    ?>
                </select>
            </form>
        </div>

        </div>

        <!-- Right card. -->
        <div  class="card">
            <div class="img-container c">
                <!-- <img src="images/round-information-icon.png" alt=""> -->
            </div>
            <h1>INFO</h1>
            <blockquote>You can add a new category by entering the name in the field and pressing <i>"ADD CATEGORY"</i>.</blockquote>
            <blockquote>To edit any existing categories, press the <i>"EDIT / DELETE CATEGORY"</i> button.</blockquote>
            <blockquote>Deleting categories can result in words being <strong>orphaned</strong>, i.e. words whose categories have been deleted. These words can no longer be filtered. To
            move these orphaned words to new categories, press the button below.</blockquote>

            <!-- Add new form. -->
            <form class="card-form" action="list-orphaned-words.php" method="GET">						
                <div class="alternate-button-wrap">
                    <button style="width:80%;" class="alternate-button" name="SubmitButton" type="submit">ORPHANDED WORDS</button>
                </div>
			</form>
        </div>
    </div>


    <div style="margin: auto; width: 80%;" class="cards-wrapper">

        <!-- New card. -->
        <div class="card">
            <div class="img-container a">
                <!-- <img src="images/download-csv-icon.png" alt=""> -->
            </div>
            <h1>EXPORT YOUR LIBRARY</h1>
            <!-- Add new form. -->
            <form class="card-form" action="export-to-file.php" method="GET">						
                <div class="card-form">
                <label for="download" class="alternate-input-label">DOWNLOAD A COPY OF YOUR LIBRARY (.csv file)</label>
                    <div class="alternate-button-wrap">
                        <button id="download" class="alternate-button" name="SubmitButton" type="submit">EXPORT LIBRARY</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- New card. -->
        <div class="card">
            <div class="img-container a">
                <!-- <img src="images/download-csv-icon.png" alt=""> -->
            </div>
            <h1>SET CASE SENSITIVITY FOR TESTS</h1>
            <!-- Add new form. -->
            <form class="card-form" action="update-case-sens.php" method="POST">						
                <div class="switch-sudo-label">
                <p>CASE SENSITIVE TESTS ON/OFF?</p>
                </div>
                <div class="master-switch">
                    <input id="switch-1" type="checkbox" class="master-switch-input" name="case_sensitive" class="checkbox_inp" value="Y" <?php echo ($case_sensitive === 'Y') ? 'checked' : ''; ?>>
                    <label for="switch-1" class="master-switch-label"></label>
                </div> 
                <div class="alternate-button-wrap">
                        <button class="alternate-button" name="SubmitButton" type="submit">SAVE</button>
                </div> 
            </form>
        </div>

        <!-- NEW card. -->
        <div  class="card">
            <div class="img-container b">
                <!-- <img src="images/target-icon-3.png" alt=""> -->
            </div>
            <h1>SET YOUR TARGET GOALS</h1>
            <!-- Add new form. -->
            <div class="set-streak-container">
                <form class="card-form" action="update-streak-target.php" method="POST">						
                    <div class="alternate-input">
                        <input style="width:40%;" class="alternate-input-field" type="number" id="test_streak" name="test_streak" min="1" max="1000" required>
                        <label for="test_streak" class="alternate-input-label">TARGET # DAYS FOR TESTING</label>
                        <input style="width:40%;" class="alternate-input-field" type="number" id="word_streak" name="word_streak" min="1" max="1000" required>
                        <label for="word_streak" class="alternate-input-label">TARGET # DAYS FOR ADDING WORDS</label>
                    </div>
                    <div class="alternate-button-wrap">
                        <button class="alternate-button" name="SubmitButton" type="submit">SET STREAK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    
</main>

    <!-- Add the footer. -->
    <?php include "include/footer.php" ?>
    
</body>
</html>