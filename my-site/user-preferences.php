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



<div style="margin: auto; width: 80%;" class="cards-wrapper">

        <!-- Left card. -->
        <div style="width:30%;" class="card">
            <div class="img-container a">
                <img src="images/word.png" width="100px" alt="">
            </div>
            <h1>CHOOSE TARGET LANGUAGE</h1>

            <!-- Dropdown to select category -->
            <p>&nbsp;</p>
            <div class="custom-select">
                <form method="POST" action="update-target-language.php">
                <label for="language" class="new-input-label">CHOOSE YOUR TARGET LANGUAGE</label>
                    <select name="lang_code" id="language" class="new-input-field">
                    <option value="">-- Languages --</option>    
                        <?php
                            if ($language_array->num_rows > 0 ) {
                            while ($row = $language_array->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($row['lang_code']) . '">' . htmlspecialchars($row['lang_name']) . '</option>';
                            }
                            }
                        ?>
                    </select>
                    <div class="action">
                        <button style="width: 100%;" class="btn btn--secondary" name="saveLanguage" type="submit">SAVE TARGET LANGUAGE</button><p></p>
                    </div>
                    </form>
            </div>
            <p>&nbsp;</p>
            <hr><br>
            <!-- Add new form. -->
            <form class="card-form" action="export-to-file.php" method="GET">						
                <div style="margin: auto;" class="action">
                <label class="new-input-label">EXPORT YOUR WORD LIBRARY (.csv file)</label>
                    <button style="width:100%;" class="btn btn--secondary" name="SubmitButton" type="submit">EXPORT WORDS</button>
                </div>
			</form>
        </div>

        <!-- Middle card. -->
        <div style="width:30%;" class="card">
            <div class="img-container b">
                <img src="images/lang-tips.png" width="100px" alt="">
            </div>
            <h1>ADD NEW WORD CATEGORIES</h1>

            <!-- Add new form. -->
            <form class="card-form" action="add-category.php" method="POST">						
                <div class="input">
                    <input type="text" class="new-input-field" name="category_desc" id="category_desc" required/>
                    <label class="new-input-label">ADD NEW CATEGORY</label>
                </div>
                <div class="action">
                    <button style="width:100%;" class="btn btn--secondary" name="SubmitButton" type="submit">ADD CATEGORY</button>
                </div>
			</form>

            <form style="padding-top:10px;" action="list-category.php" method="post" class="form-card">
                <div class="buttons-container">
                    <p>&nbsp;</p>
                    <button style="width:80%;" class="btn" name="AddWordButton" type="submit">EDIT / DELETE CATEGORY</button><p></p>
                </div>
            </form>

        <!-- Dropdown to select category -->
        <p>&nbsp;</p>
        <div class="custom-select">
            <form method="post">
            <label for="category" class="new-input-label">VIEW YOUR NEW CATEGORIES</label>
                <select name="category" id="category" class="new-input-field" onchange="updateInput()">
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
        <div style="width:30%;" class="card">
            <div class="img-container c">
                <img src="images/lang-tips.png" width="100px" alt="">
            </div>
            <h1>INFO</h1>
            <blockquote>You can add a new category by entering the name of your category in the field and pressing <i>"ADD CATEGORY"</i>.</blockquote>
            <blockquote>To edit any existing categories, press the <i>"EDIT / DELETE CATEGORY"</i> button.</blockquote>
            <blockquote>Deleting categories can result in words being <strong>orphaned</strong>, i.e. words whose categories have been deleted. These words can no longer be filtered. To
            move these orphaned words to new categories, press the button below.</blockquote>

            <!-- Add new form. -->
            <form class="card-form" action="list-orphaned-words.php" method="GET">						
                <div style="margin: auto;" class="action">
                    <button style="width:80%;" class="btn btn--secondary" name="SubmitButton" type="submit">ORPHANDED WORDS</button>
                </div>
			</form>
        </div>
    </div>


</main>

    <!-- Add the footer. -->
    <?php include "include/footer.php" ?>
    
</body>
</html>